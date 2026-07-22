<!DOCTYPE html><!-- Déclare qu'il s'agit d'un document HTML5 -->
<html lang="fr"><!-- Racine du document, langue française -->
<head><!-- En-tête : métadonnées, non affiché à l'écran -->
    <meta charset="UTF-8"><!-- Encodage des caractères de la page -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- Rend la page adaptée aux mobiles -->
    <title>Liste des clients</title><!-- Titre affiché dans l'onglet du navigateur -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css"><!-- Charge la feuille de style commune de l'application -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"><!-- Charge les icônes Font Awesome -->
</head>
<body>
    <?php $active = 'clients'; include_once __DIR__ . '/../partials/sidebar.php'; ?><!-- Indique quel lien de la sidebar est actif, puis inclut la sidebar -->

    <div class="main-wrapper"><!-- Conteneur de tout ce qui est à droite de la sidebar -->
        <?php include_once __DIR__ . '/../partials/topbar.php'; ?><!-- Inclut la barre du haut (nom de l'utilisateur connecté) -->

        <main class="page-content"><!-- Zone principale scrollable de la page -->
            <div class="page-header"><!-- Bandeau blanc avec titre + bouton d'action -->
                <h2>Liste de clients</h2><!-- Titre de la page -->
                <div class="page-header-actions"><!-- Conteneur du bouton "Ajouter" -->
                    <a href="<?= BASE_URL ?>/clients/create" class="btn-new"><i class="fas fa-plus"></i> Ajouter</a><!-- Lien vers le formulaire de création d'un client -->
                </div>
            </div>

            <div class="filter-bar"><!-- Barre verte de recherche/filtre -->
                <form method="GET" action="<?= BASE_URL ?>/clients"><!-- Formulaire en GET : les critères apparaissent dans l'URL -->
                    <input type="text" name="nom" placeholder="Rechercher par nom" value="<?= htmlspecialchars($nom) ?>"><!-- Champ de recherche par nom, préremplit avec la recherche précédente -->
                    <select name="etat"><!-- Liste déroulante de filtre par état -->
                        <option value="">Tous les états</option><!-- Option par défaut : aucun filtre -->
                        <option value="nouveau" <?= $etat === 'nouveau' ? 'selected' : '' ?>>Nouveau</option><!-- Présélectionnée si c'est le filtre actif -->
                        <option value="solvable" <?= $etat === 'solvable' ? 'selected' : '' ?>>Solvable</option><!-- Présélectionnée si c'est le filtre actif -->
                        <option value="non solvable" <?= $etat === 'non solvable' ? 'selected' : '' ?>>Non solvable</option><!-- Présélectionnée si c'est le filtre actif -->
                    </select>
                    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Rechercher</button><!-- Bouton de soumission du formulaire de recherche -->
                </form>
            </div>

            <div class="table-wrapper"><!-- Carte blanche qui enveloppe le tableau -->
                <table class="data-table"><!-- Tableau listant les clients -->
                    <thead><!-- En-tête du tableau (noms des colonnes) -->
                        <tr>
                            <th>Photo</th><!-- Colonne miniature de la photo de profil -->
                            <th>Prénom</th><!-- Colonne prénom -->
                            <th>Nom</th><!-- Colonne nom -->
                            <th>État</th><!-- Colonne état du client -->
                            <th>Actions</th><!-- Colonne des liens d'action -->
                        </tr>
                    </thead>
                    <tbody><!-- Corps du tableau, une ligne par client -->
                        <?php if (!empty($clients)): // S'il y a au moins un client à afficher ?>
                            <?php foreach ($clients as $c): // Boucle sur chaque client de la page courante ?>
                                <?php $etatClass = 'etat-' . str_replace(' ', '-', $c['etat_client']); // Classe CSS du badge d'état (ex: etat-non-solvable) ?>
                                <tr><!-- Une ligne de tableau par client -->
                                    <td>
                                        <div class="avatar-sm"><!-- Petit cercle contenant la photo ou l'icône par défaut -->
                                            <?php if (!empty($c['photo'])): // Si ce client a une photo enregistrée ?>
                                                <img src="<?= BASE_URL ?>/uploads/clients/<?= htmlspecialchars($c['photo']) ?>" alt="<?= htmlspecialchars($c['prenom']) ?>"><!-- Affiche la photo réelle -->
                                            <?php else: // Sinon, pas de photo ?>
                                                <i class="fas fa-user"></i><!-- Icône générique en remplacement -->
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($c['prenom']) ?></td><!-- Affiche le prénom (échappé contre le XSS) -->
                                    <td><?= htmlspecialchars($c['nom']) ?></td><!-- Affiche le nom (échappé contre le XSS) -->
                                    <td><span class="etat <?= $etatClass ?>"><?= htmlspecialchars($c['etat_client']) ?></span></td><!-- Badge coloré selon l'état -->
                                    <td class="actions-cell">
                                        <a class="action-link" href="<?= BASE_URL ?>/clients/show?id=<?= $c['id'] ?>">Voir fiche</a><!-- Lien vers la fiche détaillée du client -->
                                        <?php /* Modifier / Supprimer désactivés pour l'instant (demande du prof : seule l'action "Voir fiche" est disponible) */ ?>
                                        <?php if (false): // Bloc désactivé volontairement (jamais exécuté) ?>
                                        <a class="action-link" href="<?= BASE_URL ?>/clients/edit?id=<?= $c['id'] ?>">Modifier</a><!-- Lien vers la modification (désactivé) -->
                                        <a class="action-link danger" href="<?= BASE_URL ?>/clients/delete?id=<?= $c['id'] ?>" onclick="return confirm('Supprimer ce client ?');">Supprimer</a><!-- Lien de suppression avec confirmation JS (désactivé) -->
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: // Aucun client ne correspond à la recherche ?>
                            <tr>
                                <td colspan="5" style="text-align: center; color: #7f8c8d; padding: 24px;">Aucun client trouvé.</td><!-- Message affiché à la place du tableau vide -->
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): // On n'affiche la pagination que s'il y a plus d'une page ?>
                <div class="pagination"><!-- Barre de navigation entre les pages -->
                    <?php
                        $qs = fn($p) => '?' . http_build_query(['nom' => $nom, 'etat' => $etat, 'page' => $p]); // Fonction qui reconstruit l'URL en conservant les filtres actifs
                    ?>
                    <?php if ($page > 1): // S'il existe une page précédente ?>
                        <a href="<?= BASE_URL ?>/clients<?= $qs($page - 1) ?>">&laquo;</a><!-- Lien "page précédente" -->
                    <?php endif; ?>

                    <?php for ($p = 1; $p <= $totalPages; $p++): // Parcourt chaque numéro de page ?>
                        <?php if ($p === $page): // Si c'est la page actuellement affichée ?>
                            <span class="current"><?= $p ?></span><!-- Affiché en texte simple (non cliquable) -->
                        <?php else: // Sinon, c'est une autre page ?>
                            <a href="<?= BASE_URL ?>/clients<?= $qs($p) ?>"><?= $p ?></a><!-- Lien cliquable vers cette page -->
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): // S'il existe une page suivante ?>
                        <a href="<?= BASE_URL ?>/clients<?= $qs($page + 1) ?>">&raquo;</a><!-- Lien "page suivante" -->
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
