<?php
namespace App\Core; // Ce fichier appartient au namespace App\Core (dossier app/Core)

use PDO;          // On importe la classe PDO (accès générique aux bases de données en PHP)
use PDOException; // On importe l'exception levée par PDO en cas d'erreur de connexion/requête

// Classe responsable de la connexion à la base de données PostgreSQL
class Database {
    private static $instance = null; // Contiendra l'unique instance de Database (pattern Singleton)
    private $conn;                   // Contiendra l'objet PDO représentant la connexion active

    // Constructeur privé : on ne peut pas faire "new Database()" depuis l'extérieur,
    // ce qui force à passer par getInstance() et garantit une seule connexion ouverte
    private function __construct() {
        $config = require __DIR__ . '/../../config/database.php'; // Charge le tableau de paramètres de connexion
        try {
            // Remplacement du DSN MySQL par PostgreSQL
            $dsn = "pgsql:host=" . $config['host'] . 
                   ";port=" . ($config['port'] ?? '5432') . 
                   ";dbname=" . $config['db_name'] . 
                   ";options='--client_encoding=" . $config['charset'] . "'";

            $this->conn = new PDO($dsn, $config['username'], $config['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,           // Les erreurs SQL lèvent des exceptions PHP
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      // Les résultats sont renvoyés en tableaux associatifs
                PDO::ATTR_EMULATE_PREPARES => false,                  // Utilise les vraies requêtes préparées du SGBD (plus sûr)
            ]);
        } catch (PDOException $e) { // Si la connexion échoue (mauvais mot de passe, serveur injoignable, etc.)
            die("Database connection failed: " . $e->getMessage()); // On arrête tout et on affiche l'erreur
        }
    }

    // Point d'accès unique à la connexion : crée l'instance la première fois, puis la réutilise
    public static function getInstance() {
        if (!self::$instance) {              // Si aucune instance n'a encore été créée
            self::$instance = new Database(); // On en crée une (ouvre la connexion)
        }
        return self::$instance; // On renvoie toujours la même instance (une seule connexion pour toute l'app)
    }

    // Renvoie l'objet PDO à utiliser pour exécuter des requêtes SQL
    public function getConnection() {
        return $this->conn;
    }
}