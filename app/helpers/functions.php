<?php

/**
 * Fonction de débogage
 */
function dd($data) {
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die('Arrêt du débogage');
}

/**
 * Exécute une requête SELECT
 */
function executeSelect($sql, $params = [], $single = false) {
    try {
        $db = App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $single ? $stmt->fetch(PDO::FETCH_ASSOC) : $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur SQL: " . $e->getMessage());
    }
}

/**
 * Exécute une requête d'écriture (INSERT, UPDATE, DELETE)
 */
function executeUpdate($sql, $params = []) {
    try {
        $db = App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->rowCount();
    } catch (PDOException $e) {
        die("Erreur SQL: " . $e->getMessage());
    }
}

/**
 * Exécute un INSERT et retourne l'ID
 */
function executeInsert($sql, $params = []) {
    try {
        $db = App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $db->lastInsertId();
    } catch (PDOException $e) {
        die("Erreur SQL: " . $e->getMessage());
    }
}

/**
 * Génère une URL avec WEBROOT
 */
function url($path = '') {
    return WEBROOT . ltrim($path, '/');
}

/**
 * Génère un chemin pour un contrôleur/action
 */
function path($controller, $action = 'index', $params = []) {
    $url = $controller . '/' . $action;
    if (!empty($params)) {
        $url .= '/' . implode('/', $params);
    }
    return url($url);
}

/**
 * Redirige vers une URL
 */
function redirect($url) {
    header('Location: ' . url($url));
    exit();
}

/**
 * Redirige vers un contrôleur/action
 */
function redirectTo($controller, $action = 'index', $params = []) {
    redirect($controller . '/' . $action . (!empty($params) ? '/' . implode('/', $params) : ''));
}

/**
 * Charge une vue avec layout
 */
function view($view, $data = [], $layout = 'base') {
    extract($data);
    
    ob_start();
    require_once ROOT . 'views/' . $view . '.php';
    $content = ob_get_clean();
    
    $layoutFile = ROOT . 'views/layouts/' . $layout . '.layout.php';
    if (file_exists($layoutFile)) {
        require_once $layoutFile;
    } else {
        echo $content;
    }
}

/**
 * Vérifie si l'utilisateur est connecté
 */
function isConnected() {
    return isset($_SESSION['user']);
}

/**
 * Vérifie l'authentification
 */
function auth() {
    if (!isConnected()) {
        redirectTo('auth', 'login');
    }
}

/**
 * Vérifie le rôle
 */
function hasRole($role) {
    return isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === $role;
}

/**
 * Messages flash
 */
function flash($key, $message = null) {
    if ($message === null) {
        if (isset($_SESSION[$key])) {
            $msg = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $msg;
        }
        return null;
    }
    $_SESSION[$key] = $message;
}

function hasFlash($key) {
    return isset($_SESSION[$key]);
}

/**
 * Compte les enregistrements
 */
function countTable($table) {
    $result = executeSelect("SELECT COUNT(*) as total FROM $table", [], true);
    return $result ? $result['total'] : 0;
}