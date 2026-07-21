<?php
// Table de routage simple
return [
    // Routes des dettes
    '/dettes'                                => ['App\Controllers\DetteController', 'index'],
    '/dettes/non-soldees'                    => ['App\Controllers\DetteController', 'nonSoldees'],
    '/dettes/client/:id'                     => ['App\Controllers\DetteController', 'client'],
    '/dettes/ajouter'                        => ['App\Controllers\DetteController', 'ajouter'],
    '/dettes/enregistrer'                    => ['App\Controllers\DetteController', 'enregistrer'],
    '/dettes/modifier/:id'                   => ['App\Controllers\DetteController', 'modifier'],
    '/dettes/mettre-a-jour/:id'              => ['App\Controllers\DetteController', 'mettreAJour'],
    '/dettes/supprimer/:id'                  => ['App\Controllers\DetteController', 'supprimer'],
    '/dettes/soldeer/:id'                    => ['App\Controllers\DetteController', 'soldeer'],
    
];
