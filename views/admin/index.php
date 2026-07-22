<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des clients</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <?php $active = 'clients'; include_once __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include_once __DIR__ . '/../partials/topbar.php'; ?>

        <main class="page-content">
            <div class="page-header">
                <h2>Liste de clients</h2>
                <div class="page-header-actions">
                    <a href="<?= BASE_URL ?>/clients/create" class="btn-new"><i class="fas fa-plus"></i> Ajouter</a>
                </div>
            </div>

            <div class="filter-bar">
                <form method="GET" action="<?= BASE_URL ?>/clients">
                    <input type="text" name="nom" placeholder="Rechercher par nom" value="<?= htmlspecialchars($nom) ?>">
                    <select name="etat">
                        <option value="">Tous les états</option>
                        <option value="nouveau" <?= $etat === 'nouveau' ? 'selected' : '' ?>>Nouveau</option>
                        <option value="solvable" <?= $etat === 'solvable' ? 'selected' : '' ?>>Solvable</option>
                        <option value="non solvable" <?= $etat === 'non solvable' ? 'selected' : '' ?>>Non solvable</option>
                    </select>
                    <button type="submit" class="btn-filter"><i class="fas fa-search"></i> Rechercher</button>
                </form>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Prénom</th>
                            <th>Nom</th>
                            <th>État</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($clients)): ?>
                            <?php foreach ($clients as $c): ?>
                                <?php $etatClass = 'etat-' . str_replace(' ', '-', $c['etat_client']); ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['prenom']) ?></td>
                                    <td><?= htmlspecialchars($c['nom']) ?></td>
                                    <td><span class="etat <?= $etatClass ?>"><?= htmlspecialchars($c['etat_client']) ?></span></td>
                                    <td class="actions-cell">
                                        <a class="action-link" href="<?= BASE_URL ?>/clients/show?id=<?= $c['id'] ?>">Voir fiche</a>
                                        <a class="action-link" href="<?= BASE_URL ?>/clients/edit?id=<?= $c['id'] ?>">Modifier</a>
                                        <a class="action-link danger" href="<?= BASE_URL ?>/clients/delete?id=<?= $c['id'] ?>" onclick="return confirm('Supprimer ce client ?');">Supprimer</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #7f8c8d; padding: 24px;">Aucun client trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="pagination">
                    <?php
                        $qs = fn($p) => '?' . http_build_query(['nom' => $nom, 'etat' => $etat, 'page' => $p]);
                    ?>
                    <?php if ($page > 1): ?>
                        <a href="<?= BASE_URL ?>/clients<?= $qs($page - 1) ?>">&laquo;</a>
                    <?php endif; ?>

                    <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                        <?php if ($p === $page): ?>
                            <span class="current"><?= $p ?></span>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/clients<?= $qs($p) ?>"><?= $p ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($page < $totalPages): ?>
                        <a href="<?= BASE_URL ?>/clients<?= $qs($page + 1) ?>">&raquo;</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
