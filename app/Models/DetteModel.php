<?php
namespace App\Models;

use App\Core\Model;

class DetteModel extends Model {
    protected $table = 'dettes';
    
    public function __construct() {
        parent::__construct();
    }
    
    // Récupérer toutes les dettes avec infos client
    public function getAllWithClients() {
        $sql = "
            SELECT d.*, c.nom, c.prenom, c.email, c.telephone
            FROM {$this->table} d
            JOIN clients c ON d.client_id = c.id
            ORDER BY d.date DESC
        ";
        return executeSelect($sql);
    }
    
    // Récupérer les dettes d'un client
    public function getByClientId($client_id) {
        $sql = "
            SELECT * FROM {$this->table} 
            WHERE client_id = ? 
            ORDER BY date DESC
        ";
        return executeSelect($sql, [$client_id]);
    }
    
    // Récupérer les dettes non soldées
    public function getNonSoldees() {
        $sql = "
            SELECT d.*, c.nom, c.prenom 
            FROM {$this->table} d
            JOIN clients c ON d.client_id = c.id
            WHERE d.etat = 'non_soldee'
            ORDER BY d.date DESC
        ";
        return executeSelect($sql);
    }
    
    // Générer un numéro de dette unique
    public function generateNumero() {
        $sql = "SELECT MAX(numero) as max_numero FROM {$this->table}";
        $result = executeSelect($sql, [], true);
        $max = $result ? $result['max_numero'] : 'DET-0000';
        $num = intval(substr($max, 4)) + 1;
        return 'DET-' . str_pad($num, 4, '0', STR_PAD_LEFT);
    }
    
    // Créer une nouvelle dette
    public function createDette($data) {
        if (!isset($data['numero']) || empty($data['numero'])) {
            $data['numero'] = $this->generateNumero();
        }
        if (!isset($data['etat'])) {
            $data['etat'] = 'non_soldee';
        }
        if (!isset($data['date'])) {
            $data['date'] = date('Y-m-d');
        }
        
        return $this->create($data);
    }
    
    // Marquer une dette comme soldée
    public function marquerSoldee($id) {
        $sql = "UPDATE {$this->table} SET etat = 'soldee' WHERE id = ?";
        return executeUpdate($sql, [$id]);
    }
}