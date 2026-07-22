<?php
namespace App\Models; // Ce fichier appartient au namespace App\Models

use App\Core\Model; // Classe mère (fournit $db, find(), delete(), all())

// Modèle représentant la table "dette" (une dette appartient toujours à un utilisateur/client)
class DetteModel extends Model {
    protected $table = 'dette'; // Nom de la table SQL utilisée par la classe parente Model

    /**
     * Liste des dettes d'un utilisateur (client) donné, la plus récente en premier.
     */
    public function findByUtilisateur($idUtilisateur) {
        $stmt = $this->db->prepare( // Prépare la requête : seules les dettes de ce client précis nous intéressent
            "SELECT * FROM {$this->table} WHERE id_utilisateur = :id ORDER BY date DESC" // Tri du plus récent au plus ancien
        );
        $stmt->execute([':id' => $idUtilisateur]); // Exécute en liant l'id du client recherché
        return $stmt->fetchAll(); // Renvoie toutes les dettes trouvées sous forme de tableau
    }
}
