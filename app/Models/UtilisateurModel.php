<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class UtilisateurModel extends Model {
    // On indique à la classe parente sur quelle table travailler
    protected $table = 'utilisateur';

    /**
     * Recherche des clients par nom et/ou par état (etat_client), avec pagination.
     * Les deux critères sont optionnels : on filtre seulement ce qui est fourni.
     */
    public function search($nom = '', $etat = '', $page = 1, $perPage = 2) {
        [$where, $params] = $this->buildFilter($nom, $etat);

        $offset = max(0, ($page - 1) * $perPage);

        $sql = "SELECT * FROM {$this->table} WHERE {$where} ORDER BY nom ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int) $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Compte le nombre total de clients correspondant aux critères (pour la pagination).
     */
    public function countSearch($nom = '', $etat = '') {
        [$where, $params] = $this->buildFilter($nom, $etat);

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE {$where}");
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }
 
    /**
     * Construit la clause WHERE + les paramètres communs à search() et countSearch().
     */
    private function buildFilter($nom, $etat) {
        $sql = "role = 'client'";
        $params = [];

        // Filtre par nom (recherche partielle, insensible à la casse avec ILIKE - propre à PostgreSQL)
        if (!empty($nom)) {
            $sql .= " AND nom ILIKE :nom";
            $params[':nom'] = '%' . $nom . '%';
        }

        // Filtre par état du client
        if (!empty($etat)) {
            $sql .= " AND etat_client = :etat";
            $params[':etat'] = $etat;
        }

        return [$sql, $params];
    }

    /**
     * Crée un nouveau client.
     * $data = ['nom'=>, 'prenom'=>, 'email'=>, 'mot_de_passe'=>, 'telephone'=>, 'etat_client'=>]
     */
    public function create($data) {
        $sql = "INSERT INTO {$this->table} 
                    (nom, prenom, email, mot_de_passe, telephone, role, etat_client)
                VALUES 
                    (:nom, :prenom, :email, :mot_de_passe, :telephone, 'client', :etat_client)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom'          => $data['nom'],
            ':prenom'       => $data['prenom'],
            ':email'        => $data['email'],
            ':mot_de_passe' => $data['mot_de_passe'],
            ':telephone'    => $data['telephone'] ?? null,
            ':etat_client'  => $data['etat_client'] ?? 'nouveau',
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
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
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
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Vérifie les identifiants de connexion.
     * Renvoie l'utilisateur si l'email existe et que le mot de passe correspond, sinon false.
     */
    public function verifyLogin($email, $motDePasse) {
        $user = $this->findByEmail($email);
        // ATTENTION : comparaison en clair (tests locaux uniquement, jamais en production)
        if ($user && $motDePasse === $user['mot_de_passe']) {
            return $user;
        }
        return false;
    }
}
