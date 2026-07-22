<?php
// Autoloader fallback (permet de se passer de composer au besoin)
// Cette fonction sera appelée automatiquement par PHP dès qu'on utilise une classe non encore chargée
spl_autoload_register(function ($class) {
    $prefix = 'App\\';           // Toutes nos classes maison commencent par le namespace "App\"
    $base_dir = __DIR__ . '/../app/'; // Elles vivent physiquement dans le dossier app/

    $len = strlen($prefix); // Longueur du préfixe "App\" (utile pour comparer/couper la chaîne)
    if (strncmp($prefix, $class, $len) !== 0) { // Si le nom de la classe ne commence pas par "App\"
        return; // Ce n'est pas une classe de notre application : on ne fait rien (autre autoloader ou erreur)
    }

    $relative_class = substr($class, $len); // Retire le préfixe "App\" (ex: "Controllers\UtilisateurController")
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php'; // Transforme les "\" en "/" et ajoute ".php"

    if (file_exists($file)) { // Si le fichier correspondant existe bien sur le disque
        require_once $file;   // On l'inclut : la classe devient utilisable
    }
});

// Déterminer le chemin de base (BASE_URL) pour une compatibilité absolue
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';  // Chemin du script actuellement exécuté (ex: /gesDette/public/index.php)
$baseDir = dirname($scriptName);              // Dossier parent du script (ex: /gesDette/public)
$baseDir = str_replace('\\', '/', $baseDir);  // Normalise les antislashs Windows en slashs
$baseDir = rtrim($baseDir, '/');              // Retire un éventuel "/" final pour éviter les doublons
define('BASE_URL', $baseDir);                 // Constante globale réutilisée partout (vues, redirections, liens)

// Analyse de l'URI de la requête
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';       // URL complète demandée par le navigateur (avec ?query éventuelle)
$path = parse_url($requestUri, PHP_URL_PATH);       // Ne garde que le chemin, sans la chaîne de requête (?...)

// Nettoyer l'URL par rapport au dossier racine si sous-dossier (ex: /gesclasse/public/)
$url = substr($path, strlen($baseDir)); // Retire la partie correspondant à BASE_URL (utile si le site n'est pas à la racine)
$url = '/' . trim($url, '/');           // Uniformise le format (toujours un seul "/" au début, aucun à la fin)
if ($url === '') {  // Cas particulier : après nettoyage, la chaîne est vide
    $url = '/';      // On considère alors que c'est la page d'accueil
}

// Chargement des routes
$routes = require_once __DIR__ . '/../routes/web.php'; // Récupère le tableau ['/url' => [Controleur, methode], ...]

// Dispatcher simple
$matched = false; // Deviendra true dès qu'une route correspondante aura été exécutée
foreach ($routes as $routePath => $handler) { // On parcourt chaque route définie dans routes/web.php
    if ($url === $routePath) {                // Si l'URL demandée correspond exactement à cette route
        $controllerName = $handler[0]; // Nom complet de la classe contrôleur (ex: App\Controllers\UtilisateurController)
        $actionName = $handler[1];     // Nom de la méthode à appeler sur ce contrôleur (ex: 'index')

        if (class_exists($controllerName)) { // Vérifie que la classe existe (sinon l'autoloader n'a rien trouvé)
            $controller = new $controllerName(); // Instancie le contrôleur (déclenche son constructeur)
            if (method_exists($controller, $actionName)) { // Vérifie que la méthode demandée existe bien
                // Récupérer l'identifiant optionnel 'id' depuis la requête GET
                $id = $_GET['id'] ?? null; // Utilisé par les pages type "show/edit/delete?id=..."
                if ($id !== null) {              // Si un id est présent dans l'URL
                    $controller->$actionName($id); // On appelle la méthode en lui passant cet id
                } else {                          // Sinon (pages sans id, ex: liste, formulaire de connexion...)
                    $controller->$actionName();    // On appelle la méthode sans argument
                }
                $matched = true; // On note qu'une route a bien été trouvée et exécutée
                break;           // On arrête la boucle : inutile de continuer à chercher
            }
        }
    }
}

if (!$matched) { // Si aucune route ne correspondait à l'URL demandée
    header("HTTP/1.0 404 Not Found"); // On informe le navigateur que la page n'existe pas
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>404 - Page non trouvée</title>
        <style>
            body { font-family: sans-serif; text-align: center; padding-top: 100px; background-color: #f7f9fa; }
            h1 { color: #e74c3c; font-size: 48px; }
            p { font-size: 18px; color: #555; }
            a { color: #3498db; text-decoration: none; font-weight: bold; }
        </style>
    </head>
    <body>
        <h1>404 Not Found</h1>
        <p>La page demandée n'existe pas ou a été déplacée.</p>
        <p><a href='" . BASE_URL . "/classes'>Retour à l'accueil</a></p>
    </body>
    </html>"; // Page HTML minimale affichée en cas de route introuvable
}
