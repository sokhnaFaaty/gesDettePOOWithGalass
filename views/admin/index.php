<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des clients</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f6f9; color: #333; }
        h1 { color: #2c3e50; }
        .nav { margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .nav a { margin-right: 15px; text-decoration: none; color: #34495e; font-weight: bold; }
        .nav a:hover { color: #2ecc71; }
        .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; flex-wrap: wrap; gap: 10px; }
        .toolbar form { display: flex; gap: 10px; align-items: center; }
        .toolbar input, .toolbar select { padding: 7px 10px; border: 1px solid #ccc; border-radius: 4px; }
        .btn { display: inline-block; padding: 7px 14px; border-radius: 4px; text-decoration: none; font-size: 14px; border: none; cursor: pointer; }
        .btn-primary { background-color: #2ecc71; color: #fff; }
        .btn-primary:hover { background-color: #27ae60; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #34495e; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .action-link { margin-right: 10px; text-decoration: none; color: #2980b9; }
        .action-link.danger { color: #e74c3c; }
        .etat { padding: 3px 8px; border-radius: 10px; font-size: 12px; color: #fff; }
        .etat-solvable { background-color: #2ecc71; }
        .etat-non-solvable { background-color: #e74c3c; }
        .etat-nouveau { background-color: #95a5a6; }
        .pagination { margin-top: 20px; text-align: center; }
        .pagination a, .pagination span { display: inline-block; padding: 6px 12px; margin: 0 3px; border-radius: 4px; text-decoration: none; color: #34495e; }
        .pagination a:hover { background-color: #eee; }
        .pagination .current { background-color: #34495e; color: #fff; }
    </style>
</head>
<body>
    <div class="nav">
        <div><strong>Espace admin</strong> — <?= htmlspecialchars($_SESSION['user']['nom'] ?? '') ?></div>
        <div><a href="<?= BASE_URL ?>/logout">Déconnexion</a></div>
    </div>

    <h1>Liste de clients</h1>

    <div class="toolbar">
        <form method="GET" action="<?= BASE_URL ?>/clients">
            <input type="text" name="nom" placeholder="Nom" value="<?= htmlspecialchars($nom) ?>">
            <select name="etat">
                <option value="">Tous les états</option>
                <option value="nouveau" <?= $etat === 'nouveau' ? 'selected' : '' ?>>Nouveau</option>
                <option value="solvable" <?= $etat === 'solvable' ? 'selected' : '' ?>>Solvable</option>
                <option value="non solvable" <?= $etat === 'non solvable' ? 'selected' : '' ?>>Non solvable</option>
            </select>
            <button type="submit" class="btn" style="background:#34495e;color:#fff;">Rechercher</button>
        </form>
        <a href="<?= BASE_URL ?>/clients/create" class="btn btn-primary">+ Ajouter</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Prénom</th>
                <th>Nom</th>
                <th>État</th>
                <th>Action</th>
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
                        <td>
                            <a class="action-link" href="<?= BASE_URL ?>/clients/show?id=<?= $c['id'] ?>">Voir fiche</a>
                            <a class="action-link" href="<?= BASE_URL ?>/clients/edit?id=<?= $c['id'] ?>">Modifier</a>
                            <a class="action-link danger" href="<?= BASE_URL ?>/clients/delete?id=<?= $c['id'] ?>" onclick="return confirm('Supprimer ce client ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #7f8c8d;">Aucun client trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

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
</body>
</html>
