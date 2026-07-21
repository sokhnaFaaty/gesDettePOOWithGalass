<?php
namespace App\Core;

class Controller {
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
}
