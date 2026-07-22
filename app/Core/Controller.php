<?php
namespace App\Core; // Ce fichier appartient au namespace App\Core

// Classe mère de tous les contrôleurs (UtilisateurController, DetteController, ...)
// Elle fournit les outils communs : démarrage de session, affichage d'une vue, redirection, contrôle d'accès
 class Controller {

    // Constructeur : exécuté automatiquement à chaque "new XxxController()"
    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) { // Si aucune session PHP n'est encore démarrée
            session_start();                          // On la démarre (nécessaire pour utiliser $_SESSION)
        }
    }

    // Charge un fichier de vue et lui transmet des données
    protected function view($view, $data = []) {

        extract($data); // Transforme chaque clé du tableau $data en variable PHP utilisable dans la vue
                         // (ex: ['client' => $c] devient une variable $client dans le fichier de vue)

        $viewFile = __DIR__ . '/../../views/' . $view . '.php'; // Construit le chemin du fichier de vue (ex: views/admin/index.php)
        if (file_exists($viewFile)) {   // Si le fichier de vue existe bien
            require_once $viewFile;     // On l'inclut : son contenu HTML/PHP est exécuté et affiché
        } else {                        // Sinon (faute de frappe dans le nom de la vue, fichier manquant...)
            die("View '{$view}' not found."); // On arrête l'exécution avec un message d'erreur explicite
        }
    }

    // Redirige le navigateur vers une autre URL de l'application
    protected function redirect($url) {
        header("Location: " . BASE_URL . $url); // Envoie l'en-tête HTTP de redirection (BASE_URL = racine de l'app)
        exit; // Arrête immédiatement le script : rien ne doit s'exécuter après une redirection
    }

    /**
     * Vérifie que l'utilisateur est connecté (et, si précisé, qu'il a le bon rôle).
     * Redirige vers /login sinon. $roles peut être une chaîne ou un tableau de rôles autorisés.
     */
    protected function requireAuth($roles = null) {
        if (empty($_SESSION['user'])) { // Si aucun utilisateur n'est stocké en session (= pas connecté)
            $this->redirect('/login');  // On renvoie vers la page de connexion
        }

        if ($roles !== null) { // Si un ou plusieurs rôles précis sont exigés pour accéder à la page
            $roles = (array) $roles; // On s'assure de manipuler un tableau, même si un seul rôle (string) a été passé
            if (!in_array($_SESSION['user']['role'], $roles, true)) { // Si le rôle de l'utilisateur connecté n'est pas autorisé
                $this->redirect('/login'); // On le renvoie aussi vers la connexion (accès refusé)
            }
        }

        return $_SESSION['user']; // Renvoie les infos de l'utilisateur connecté (utile dans les contrôleurs)
    }
}
