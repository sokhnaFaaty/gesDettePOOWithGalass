<?php
namespace App\Core; // Ce fichier appartient au namespace App\Core

// Classe mère de tous les modèles (UtilisateurModel, DetteModel, ...)
// Elle regroupe les opérations communes à toutes les tables (connexion, find, delete, all)
 class Model {
    protected $db;    // Connexion PDO, accessible dans toutes les classes filles
    protected $table; // Nom de la table SQL correspondant au modèle (défini dans chaque classe fille)

    // Constructeur : récupère la connexion PDO partagée (singleton Database) dès qu'un modèle est créé
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // Renvoie toutes les lignes de la table associée au modèle
    public function all() {
        $stmt = $this->db->query("SELECT * FROM {$this->table}"); // Exécute directement le SELECT (pas de paramètre à lier)
        return $stmt->fetchAll(); // Renvoie toutes les lignes sous forme de tableau associatif
    }

    // Recherche une ligne par son id
    public function find($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?"); // Prépare la requête avec un marqueur "?"
        $stmt->execute([$id]);  // Exécute la requête en injectant $id à la place du "?" (protège des injections SQL)
        return $stmt->fetch();  // Renvoie la ligne trouvée (ou false si aucune)
    }

    // Supprime une ligne par son id
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?"); // Prépare la requête de suppression
        return $stmt->execute([$id]); // Exécute la suppression et renvoie true/false selon le succès
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