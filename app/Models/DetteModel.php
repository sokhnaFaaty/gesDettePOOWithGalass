<?php
namespace App\Models;

use App\Core\Model;
use PDO;

class DetteModel extends Model {
    // Schéma partagé : table "dette", clients = utilisateur (role = 'client')
    protected $table = 'dette';

    /**
     * Liste des dettes avec les infos du client (utilisateur), paginée.
     * Filtres optionnels : état du client (etat_client) et/ou état de la dette (etat_dette).
     */
    public function getAllWithClients($etatClient = '', $etatDette = '', $page = 1, $perPage = 5) {
        [$where, $params] = $this->buildFilter($etatClient, $etatDette);
        $offset = max(0, ($page - 1) * $perPage);

        $sql = "SELECT d.*, u.nom, u.prenom, u.email, u.telephone
                FROM {$this->table} d
                JOIN utilisateur u ON d.id_utilisateur = u.id
                WHERE {$where}
                ORDER BY d.date DESC
                LIMIT :limit OFFSET :offset";

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
     * Nombre total de dettes correspondant aux filtres (pour la pagination).
     */
    public function countAll($etatClient = '', $etatDette = '') {
        [$where, $params] = $this->buildFilter($etatClient, $etatDette);

        $stmt = $this->db->prepare(
            "SELECT COUNT(*)
             FROM {$this->table} d
             JOIN utilisateur u ON d.id_utilisateur = u.id
             WHERE {$where}"
        );
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Construit la clause WHERE + les paramètres communs à getAllWithClients() et countAll().
     * Les deux filtres sont optionnels : on ne filtre que ce qui est fourni.
     */
    private function buildFilter($etatClient, $etatDette) {
        $conditions = ['1 = 1'];
        $params = [];

        if (!empty($etatClient)) {
            $conditions[] = "u.etat_client = :etat_client";
            $params[':etat_client'] = $etatClient;
        }
        if (!empty($etatDette)) {
            $conditions[] = "d.etat_dette = :etat_dette";
            $params[':etat_dette'] = $etatDette;
        }

        return [implode(' AND ', $conditions), $params];
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
     * Dettes soldées, paginées.
     */
    public function getSoldees($page = 1, $perPage = 5) {
        $offset = max(0, ($page - 1) * $perPage);

        $sql = "SELECT d.*, u.nom, u.prenom
                FROM {$this->table} d
                JOIN utilisateur u ON d.id_utilisateur = u.id
                WHERE d.etat_dette = 'soldee'
                ORDER BY d.date DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int) $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Nombre de dettes soldées.
     */
    public function countSoldees() {
        return (int) $this->db->query(
            "SELECT COUNT(*) FROM {$this->table} WHERE etat_dette = 'soldee'"
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
