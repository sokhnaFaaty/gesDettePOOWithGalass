<?php
namespace App\Models; // Ce fichier appartient au namespace App\Models

use App\Core\Model; // Classe mère (fournit $db, find(), delete(), all())
use PDO;             // Utilisé ci-dessous pour préciser le type d'un paramètre (PDO::PARAM_INT)

// Modèle représentant la table "utilisateur" (admins ET clients, distingués par la colonne "role")
class UtilisateurModel extends Model {
    // On indique à la classe parente sur quelle table travailler
    protected $table = 'utilisateur';

    /**
     * Recherche des clients par nom et/ou par état (etat_client), avec pagination.
     * Les deux critères sont optionnels : on filtre seulement ce qui est fourni.
     */
    public function search($nom = '', $etat = '', $page = 1, $perPage = 2) {
        [$where, $params] = $this->buildFilter($nom, $etat); // Construit la clause WHERE + ses paramètres liés

        $offset = max(0, ($page - 1) * $perPage); // Calcule le décalage SQL (page 1 -> offset 0, page 2 -> offset perPage, ...)

        $sql = "SELECT * FROM {$this->table} WHERE {$where} ORDER BY nom ASC LIMIT :limit OFFSET :offset"; // Requête paginée
        $stmt = $this->db->prepare($sql); // Prépare la requête (le SQL est figé, seules les valeurs varient)
        foreach ($params as $key => $value) { // Lie chaque paramètre du filtre (:nom, :etat) à sa valeur
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int) $perPage, PDO::PARAM_INT);  // Lie explicitement en entier (sinon LIMIT peut échouer)
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);  // Idem pour OFFSET
        $stmt->execute();       // Exécute la requête préparée
        return $stmt->fetchAll(); // Renvoie la page de résultats sous forme de tableau
    }

    /**
     * Compte le nombre total de clients correspondant aux critères (pour la pagination).
     */
    public function countSearch($nom = '', $etat = '') {
        [$where, $params] = $this->buildFilter($nom, $etat); // Même filtre que search(), sans LIMIT/OFFSET

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE {$where}"); // Requête de comptage
        $stmt->execute($params);              // Exécute en liant directement le tableau de paramètres
        return (int) $stmt->fetchColumn();    // Renvoie le nombre total (une seule valeur), casté en entier
    }

    /**
     * Construit la clause WHERE + les paramètres communs à search() et countSearch().
     */
    private function buildFilter($nom, $etat) {
        $sql = "role = 'client'"; // Base commune : on ne liste jamais les comptes admin dans cette recherche
        $params = [];              // Tableau des paramètres à lier (rempli selon les filtres actifs)

        // Filtre par nom (recherche partielle, insensible à la casse avec ILIKE - propre à PostgreSQL)
        if (!empty($nom)) {                        // Si un nom a été saisi dans le formulaire de recherche
            $sql .= " AND nom ILIKE :nom";          // Ajoute la condition à la requête
            $params[':nom'] = '%' . $nom . '%';     // Les "%" permettent de chercher le nom n'importe où dans la chaîne
        }

        // Filtre par état du client
        if (!empty($etat)) {                // Si un état a été sélectionné dans le formulaire
            $sql .= " AND etat_client = :etat"; // Ajoute la condition d'égalité stricte
            $params[':etat'] = $etat;           // Valeur à lier au marqueur :etat
        }

        return [$sql, $params]; // Renvoie la clause WHERE construite et les paramètres associés
    }

    /**
     * Crée un nouveau client.
     * $data = ['nom'=>, 'prenom'=>, 'email'=>, 'mot_de_passe'=>, 'telephone'=>, 'etat_client'=>]
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table}
                    (nom, prenom, email, mot_de_passe, telephone, role, etat_client, photo)
                VALUES
                    (:nom, :prenom, :email, :mot_de_passe, :telephone, 'client', :etat_client, :photo)"; // 'client' est fixe : ce formulaire ne crée jamais d'admin

        $stmt = $this->db->prepare($sql); // Prépare la requête d'insertion
        return $stmt->execute([           // Exécute en liant chaque valeur à son marqueur nommé
            ':nom'          => $data['nom'],
            ':prenom'       => $data['prenom'],
            ':email'        => $data['email'],
            ':mot_de_passe' => $data['mot_de_passe'],
            ':telephone'    => $data['telephone'] ?? null,       // null si aucun téléphone fourni
            ':etat_client'  => $data['etat_client'] ?? 'nouveau', // 'nouveau' par défaut si non précisé
            ':photo'        => $data['photo'] ?? null,           // null si aucune photo envoyée
        ]);
    }

    /**
     * Met à jour un client existant.
     */
    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET
                    nom = :nom,
                    prenom = :prenom,
                    email = :email,
                    telephone = :telephone,
                    etat_client = :etat_client
                WHERE id = :id"; // Le WHERE id = :id est essentiel : sans lui, TOUTES les lignes seraient modifiées

        $stmt = $this->db->prepare($sql); // Prépare la requête de mise à jour
        return $stmt->execute([           // Exécute en liant les nouvelles valeurs + l'id de la ligne ciblée
            ':nom'         => $data['nom'],
            ':prenom'      => $data['prenom'],
            ':email'       => $data['email'],
            ':telephone'   => $data['telephone'] ?? null,
            ':etat_client' => $data['etat_client'] ?? 'nouveau',
            ':id'          => $id,
        ]);
    }

    /**
     * Recherche un utilisateur par email (utile pour la connexion / éviter les doublons).
     */
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email"); // Requête préparée par email
        $stmt->execute([':email' => $email]); // Exécute en liant l'email recherché
        return $stmt->fetch(); // Renvoie l'utilisateur trouvé (ou false si aucun email ne correspond)
    }

    /**
     * Vérifie les identifiants de connexion.
     * Renvoie l'utilisateur si l'email existe et que le mot de passe correspond, sinon false.
     */
    public function verifyLogin($email, $motDePasse) {
        $user = $this->findByEmail($email); // Récupère l'utilisateur correspondant à cet email (ou false)
        // ATTENTION : comparaison en clair (tests locaux uniquement, jamais en production)
        if ($user && $motDePasse === $user['mot_de_passe']) { // Vérifie que l'utilisateur existe ET que le mot de passe correspond
            return $user; // Connexion réussie : on renvoie les infos de l'utilisateur
        }
        return false; // Email inconnu ou mot de passe incorrect : connexion refusée
    }
}
