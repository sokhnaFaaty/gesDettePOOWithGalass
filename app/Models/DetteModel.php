<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class DetteModel extends Model {
    // Schéma partagé : table "dette", clients = utilisateur (role = 'client')
    protected $table = 'dette';

    /**
     * Toutes les dettes avec les infos du client (utilisateur), paginées.
     */
    public function getAllWithClients($page = 1, $perPage = 5) {
        $offset = max(0, ($page - 1) * $perPage);

        $sql = "SELECT d.*, u.nom, u.prenom, u.email, u.telephone
                FROM {$this->table} d
                JOIN utilisateur u ON d.id_utilisateur = u.id
                ORDER BY d.date DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Nombre total de dettes (pour calculer le nombre de pages).
     */
    public function countAll() {
        return (int) $this->db->query("SELECT COUNT(*) FROM {$this->table}")->fetchColumn();
    }

    /**
     * Dettes non soldées, paginées.
     */
    public function getNonSoldees($page = 1, $perPage = 5) {
        $offset = max(0, ($page - 1) * $perPage);

        $sql = "SELECT d.*, u.nom, u.prenom
                FROM {$this->table} d
                JOIN utilisateur u ON d.id_utilisateur = u.id
                WHERE d.etat_dette = 'non soldee'
                ORDER BY d.date DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Nombre de dettes non soldées.
     */
    public function countNonSoldees() {
        return (int) $this->db->query(
            "SELECT COUNT(*) FROM {$this->table} WHERE etat_dette = 'non soldee'"
        )->fetchColumn();
    }

    /**
     * Dettes d'un client (utilisateur) donné, la plus récente en premier.
     */
    public function getByClientId($idUtilisateur) {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE id_utilisateur = :id ORDER BY date DESC"
        );
        $stmt->execute([':id' => $idUtilisateur]);
        return $stmt->fetchAll();
    }
}
