<?php
namespace App\Core;

class Controller {
    /**
     * Affiche une vue avec les données
     */
    protected function view($view, $data = []) {
        // Extraire les données pour les rendre disponibles dans la vue
        extract($data);
        
        $viewFile = ROOT . 'views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("Vue '{$view}' introuvable.");
        }
    }

    /**
     * Redirige vers une URL spécifique
     */
    protected function redirect($url) {
        // Utiliser BASE_URL si défini, sinon WEBROOT
        $base = defined('BASE_URL') ? BASE_URL : WEBROOT;
        header("Location: " . $base . '/' . ltrim($url, '/'));
        exit;
    }
    
    /**
     * Redirige vers un contrôleur/action
     */
    protected function redirectTo($controller, $action = 'index', $params = []) {
        $url = $controller . '/' . $action;
        if (!empty($params)) {
            $url .= '/' . implode('/', $params);
        }
        $this->redirect($url);
    }
}