<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class UtilisateurModel extends Model {
    // On indique à la classe parente sur quelle table travailler
    protected $table = 'utilisateur';

    /**
     * Recherche des clients par nom et/ou par état (etat_client).
     * Les deux critères sont optionnels : on filtre seulement ce qui est fourni.
     */
    public function search($nom = '', $etat = '') {
        $sql = "SELECT * FROM {$this->table} WHERE role = 'client'";
        $params = [];

        // Filtre par nom (recherche partielle, insensible à la casse avec ILIKE)
        if (!empty($nom)) {
            $sql .= " AND nom ILIKE :nom";
            $params[':nom'] = '%' . $nom . '%';
        }

        // Filtre par état du client
        if (!empty($etat)) {
            $sql .= " AND etat_client = :etat";
            $params[':etat'] = $etat;
        }

        $sql .= " ORDER BY nom ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Récupère uniquement les utilisateurs ayant le rôle 'client'.
     */
    public function allClients() {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE role = 'client' ORDER BY nom ASC");
        $stmt->execute();
        return $stmt->fetchAll();
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
            // On hache le mot de passe : on ne le stocke JAMAIS en clair
            ':mot_de_passe' => password_hash($data['mot_de_passe'], PASSWORD_DEFAULT),
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
        // password_verify compare le mot de passe saisi au hash stocké
        if ($user && password_verify($motDePasse, $user['mot_de_passe'])) {
            return $user;
        }
        return false;
    }
}
