<?php
// Paramètres de connexion à la base de données (PostgreSQL)
// Ce tableau est chargé par App\Core\Database (require) pour construire la connexion PDO
return [
    'host'     => '127.0.0.1',       // Adresse du serveur PostgreSQL (ici : la machine locale)
    'port'     => '5432',            // Port d'écoute de PostgreSQL (5432 = port par défaut)
    'db_name'  => 'gestion_de_dette',// Nom de la base de données à utiliser
    'username' => 'postgres',        // Nom d'utilisateur PostgreSQL
    'password' => 'ngd',             // Mot de passe de cet utilisateur
    'charset'  => 'UTF8',            // Encodage des caractères utilisé pour la connexion
];
