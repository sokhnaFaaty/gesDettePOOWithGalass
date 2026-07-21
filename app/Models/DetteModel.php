<?php
namespace App\Models;

use App\Core\Model;

class DetteModel extends Model {
    protected $table = 'dette';

    /**
     * Liste des dettes d'un utilisateur (client) donné, la plus récente en premier.
     */
    public function findByUtilisateur($idUtilisateur) {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE id_utilisateur = :id ORDER BY date DESC"
        );
        $stmt->execute([':id' => $idUtilisateur]);
        return $stmt->fetchAll();
    }
}
