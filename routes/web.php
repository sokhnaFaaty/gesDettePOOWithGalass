<?php
// Table de routage de l'application de gestion de dettes
// Chaque clé est une URL, chaque valeur est un tableau [NomDeLaClasseControleur, nomDeLaMethode]
// C'est public/index.php qui lit ce tableau pour savoir quel contrôleur appeler
return [
    // Page d'accueil -> connexion : quand on visite "/", on affiche directement le formulaire de connexion
    '/'                 => ['App\Controllers\UtilisateurController', 'login'],

    // -------- DETTES (lecture seule pour l'instant) --------
    '/dettes'                 => ['App\Controllers\DetteController', 'index'],
    '/dettes/non-soldees'     => ['App\Controllers\DetteController', 'nonSoldees'],
    '/dettes/soldees'         => ['App\Controllers\DetteController', 'soldees'],
    '/dettes/client/:id'      => ['App\Controllers\DetteController', 'client'],

    // -------- CLIENTS (CRUD + recherche) - réservé à l'admin --------
    '/clients'          => ['App\Controllers\UtilisateurController', 'index'],   // Liste des clients (recherche + pagination)
    '/clients/create'   => ['App\Controllers\UtilisateurController', 'create'],  // Affiche le formulaire d'ajout d'un client
    '/clients/store'    => ['App\Controllers\UtilisateurController', 'store'],   // Traite la soumission du formulaire d'ajout (POST)
    
    // '/clients/edit'     => ['App\Controllers\UtilisateurController', 'edit'],    // Affiche le formulaire de modification d'un client
    // '/clients/update'   => ['App\Controllers\UtilisateurController', 'update'],  // Traite la soumission du formulaire de modification (POST)
    // '/clients/delete'   => ['App\Controllers\UtilisateurController', 'delete'],  // Supprime un client
    '/clients/show'     => ['App\Controllers\UtilisateurController', 'show'],    // Affiche la fiche d'un client (infos + ses dettes)

    // -------- ESPACE CLIENT --------
    '/profil'           => ['App\Controllers\UtilisateurController', 'profil'], // Fiche du client connecté (ses infos + ses dettes)

    // -------- CONNEXION --------
    '/login'            => ['App\Controllers\UtilisateurController', 'login'],
    '/authenticate'     => ['App\Controllers\UtilisateurController', 'authenticate'],
    '/logout'           => ['App\Controllers\UtilisateurController', 'logout'],
];
