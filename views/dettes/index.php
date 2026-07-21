<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des Filières</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f6f9; color: #333; }
        h1 { color: #2c3e50; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #34495e; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #34495e; font-weight: bold; }
        .nav a:hover { color: #2ecc71; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="<?= BASE_URL ?>/classes">Classes</a> | 
        <a href="<?= BASE_URL ?>/filieres">Filières</a> | 
        <a href="<?= BASE_URL ?>/niveaux">Niveaux</a>
    </div>

    <h1>Gestion des Filières</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Libellé</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($filieres)): ?>
                <?php foreach ($filieres as $filiere): ?>
                    <tr>
                        <td><?= htmlspecialchars($filiere['id']) ?></td>
                        <td><?= htmlspecialchars($filiere['libelle']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2" style="text-align: center; color: #7f8c8d;">Aucune filière enregistrée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
