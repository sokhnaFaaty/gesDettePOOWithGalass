<?php
namespace App\Core;

 class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function all() {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }

    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Insérer un nouvel enregistrement et renvoyer son id
    public function create($data) {
        $colonnes = array_keys($data);
        $placeholders = array_fill(0, count($colonnes), '?');

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $colonnes) . ") 
                VALUES (" . implode(', ', $placeholders) . ") 
                RETURNING id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));

        $row = $stmt->fetch();
        return $row ? $row['id'] : false;
    }

    // Mettre à jour un enregistrement existant
    public function update($id, $data) {
        $sets = array_map(fn($col) => "{$col} = ?", array_keys($data));

        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        $values = array_values($data);
        $values[] = $id;

        return $stmt->execute($values);
    }
}