<?php
namespace App\Core;

 class Controller {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Render a view and pass data to it
    protected function view($view, $data = []) {
        // Extract data to make variables available in the view
        extract($data);

        $viewFile = __DIR__ . '/../../views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View '{$view}' not found.");
        }
    }

    // Redirect to a specific URL
    protected function redirect($url) {
        header("Location: " . BASE_URL . $url);
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
