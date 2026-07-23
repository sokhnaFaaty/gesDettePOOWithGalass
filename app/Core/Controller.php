<?php
namespace App\Core; // Ce fichier appartient au namespace App\Core

class Controller {
    /**
     * Constructeur de base : permet aux contrôleurs enfants d'appeler
     * parent::__construct() sans erreur "Cannot call constructor".
     */
    public function __construct() {
        // Rien de particulier pour l'instant.
    }

    /**
     * Affiche une vue avec les données
     */
    protected function view($view, $data = []) {
        // Extract data to make variables available in the view
        extract($data);
        
        $viewFile = __DIR__ . '/../../views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Vue '{$view}' introuvable.");
        }
    }

    // Redirect to a specific URL
    protected function redirect($url) {
        // Utiliser BASE_URL si défini, sinon WEBROOT
        $base = defined('BASE_URL') ? BASE_URL : WEBROOT;
        header("Location: " . $base . '/' . ltrim($url, '/'));
        exit;
    }

    /**
     * Vérifie que l'utilisateur est connecté (et, si précisé, qu'il a le bon rôle).
     * Redirige vers /login sinon. $roles peut être une chaîne ou un tableau de rôles autorisés.
     */
    protected function requireAuth($roles = null) {
        if (empty($_SESSION['user'])) {
            $this->redirect('/login');
        }

        if ($roles !== null) {
            $roles = (array) $roles;
            if (!in_array($_SESSION['user']['role'], $roles, true)) {
                $this->redirect('/login');
            }
        }

        return $_SESSION['user'];
    }
}
