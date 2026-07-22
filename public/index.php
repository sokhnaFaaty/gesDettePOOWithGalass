<?php
// Démarrer la session
session_start();

// Définir les constantes
define('ROOT', dirname(__DIR__) . '/');
define('WEBROOT', '/gesDette-poo/public/'); // ⚠️ Adapter selon votre structure

// Déterminer BASE_URL automatiquement
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$baseDir = dirname($scriptName);
$baseDir = str_replace('\\', '/', $baseDir);
$baseDir = rtrim($baseDir, '/');
define('BASE_URL', $baseDir);

// Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = ROOT . 'app/';
    
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    
    $file = $base_dir . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Charger les helpers
require_once ROOT . 'app/helpers/functions.php';

// Récupérer et nettoyer l'URL
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);

// Enlever le dossier public de l'URL
$baseDir = dirname($_SERVER['SCRIPT_NAME']);
$path = substr($path, strlen($baseDir));
$path = '/' . trim($path, '/');
if ($path === '') $path = '/';

// Charger les routes
$routes = require_once ROOT . 'routes/web.php';

// Router
$matched = false;

foreach ($routes as $route => $handler) {
    $pattern = '#^' . preg_replace('/\/:([a-z]+)/', '/([^/]+)', $route) . '$#';
    
    if (preg_match($pattern, $path, $matches)) {
        $controller = new $handler[0]();
        $action = $handler[1];
        $params = array_slice($matches, 1);
        
        $controller->$action(...$params);
        $matched = true;
        break;
    }
}

// Si aucune route trouvée
if (!$matched) {
    header('HTTP/1.0 404 Not Found');
    echo "<!DOCTYPE html>
    <html>
    <head><title>404 - Page non trouvée</title></head>
    <body>
        <h1>404 Not Found</h1>
        <p>La page demandée n'existe pas.</p>
        <p><a href='" . BASE_URL . "/clients'>Retour à l'accueil</a></p>
    </body>
    </html>"; // Page HTML minimale affichée en cas de route introuvable
}