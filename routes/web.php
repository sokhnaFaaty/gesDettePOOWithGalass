<?php
// Table de routage de l'application de gestion de dettes
return [
    // Page d'accueil -> liste des clients
    '/'                 => ['App\Controllers\UtilisateurController', 'index'],

    // -------- CLIENTS (CRUD + recherche) --------
    '/clients'          => ['App\Controllers\UtilisateurController', 'index'],
    '/clients/create'   => ['App\Controllers\UtilisateurController', 'create'],
    '/clients/store'    => ['App\Controllers\UtilisateurController', 'store'],
    '/clients/edit'     => ['App\Controllers\UtilisateurController', 'edit'],
    '/clients/update'   => ['App\Controllers\UtilisateurController', 'update'],
    '/clients/delete'   => ['App\Controllers\UtilisateurController', 'delete'],

    // -------- CONNEXION / ADMIN --------
    '/login'            => ['App\Controllers\UtilisateurController', 'login'],
    '/authenticate'     => ['App\Controllers\UtilisateurController', 'authenticate'],
    '/logout'           => ['App\Controllers\UtilisateurController', 'logout'],
];