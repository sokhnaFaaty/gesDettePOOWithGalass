<?php
// Table de routage de l'application de gestion de dettes
// Chaque clé est une URL, chaque valeur est un tableau [NomDeLaClasseControleur, nomDeLaMethode]
// C'est public/index.php qui lit ce tableau pour savoir quel contrôleur appeler
return [
    // Page d'accueil -> connexion : quand on visite "/", on affiche directement le formulaire de connexion
    '/'                 => ['App\Controllers\UtilisateurController', 'login'],

    // -------- CLIENTS (CRUD + recherche) - réservé à l'admin --------
    '/clients'          => ['App\Controllers\UtilisateurController', 'index'],   // Liste des clients (recherche + pagination)
    '/clients/create'   => ['App\Controllers\UtilisateurController', 'create'],  // Affiche le formulaire d'ajout d'un client
    '/clients/store'    => ['App\Controllers\UtilisateurController', 'store'],   // Traite la soumission du formulaire d'ajout (POST)
    // Modifier / Supprimer désactivés pour l'instant (demande du prof : seule l'action "Voir fiche" est disponible)
    // '/clients/edit'     => ['App\Controllers\UtilisateurController', 'edit'],    // Affiche le formulaire de modification d'un client
    // '/clients/update'   => ['App\Controllers\UtilisateurController', 'update'],  // Traite la soumission du formulaire de modification (POST)
    // '/clients/delete'   => ['App\Controllers\UtilisateurController', 'delete'],  // Supprime un client
    '/clients/show'     => ['App\Controllers\UtilisateurController', 'show'],    // Affiche la fiche d'un client (infos + ses dettes)

    // -------- ESPACE CLIENT --------
    '/profil'           => ['App\Controllers\UtilisateurController', 'profil'], // Fiche du client connecté (ses infos + ses dettes)

    // -------- CONNEXION --------
    '/login'            => ['App\Controllers\UtilisateurController', 'login'],        // Affiche le formulaire de connexion
    '/authenticate'     => ['App\Controllers\UtilisateurController', 'authenticate'], // Traite la soumission du formulaire de connexion (POST)
    '/logout'           => ['App\Controllers\UtilisateurController', 'logout'],       // Déconnecte l'utilisateur (détruit la session)
]; // Fin du tableau des routes, renvoyé à public/index.php via require_once
