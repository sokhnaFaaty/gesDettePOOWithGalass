<?php
// Table de routage de l'application de gestion de dettes
return [
    // Page d'accueil -> connexion
    '/'                 => ['App\Controllers\UtilisateurController', 'login'],

    // -------- DETTES (lecture seule pour l'instant) --------
    '/dettes'                 => ['App\Controllers\DetteController', 'index'],
    '/dettes/non-soldees'     => ['App\Controllers\DetteController', 'nonSoldees'],
    '/dettes/soldees'         => ['App\Controllers\DetteController', 'soldees'],
    '/dettes/client/:id'      => ['App\Controllers\DetteController', 'client'],

    // -------- CLIENTS (CRUD + recherche) - réservé à l'admin --------
    '/clients'          => ['App\Controllers\UtilisateurController', 'index'],
    '/clients/create'   => ['App\Controllers\UtilisateurController', 'create'],
    '/clients/store'    => ['App\Controllers\UtilisateurController', 'store'],
    '/clients/edit'     => ['App\Controllers\UtilisateurController', 'edit'],
    '/clients/update'   => ['App\Controllers\UtilisateurController', 'update'],
    '/clients/delete'   => ['App\Controllers\UtilisateurController', 'delete'],
    '/clients/show'     => ['App\Controllers\UtilisateurController', 'show'],

    // -------- ESPACE CLIENT --------
    '/profil'           => ['App\Controllers\UtilisateurController', 'profil'],

    // -------- CONNEXION --------
    '/login'            => ['App\Controllers\UtilisateurController', 'login'],
    '/authenticate'     => ['App\Controllers\UtilisateurController', 'authenticate'],
    '/logout'           => ['App\Controllers\UtilisateurController', 'logout'],
];
