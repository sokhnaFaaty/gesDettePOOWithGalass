<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ma fiche</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f6f9; color: #333; }
        h1 { color: #2c3e50; }
        .nav { margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; }
        .nav a { text-decoration: none; color: #34495e; font-weight: bold; }
        .nav a:hover { color: #2ecc71; }
        .fiche { display: flex; gap: 25px; background: #fff; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-bottom: 25px; align-items: center; }
        .avatar { width: 80px; height: 80px; border-radius: 50%; background: #ecf0f1; display: flex; align-items: center; justify-content: center; font-size: 32px; color: #95a5a6; flex-shrink: 0; }
        .infos p { margin: 4px 0; }
        .infos .label { color: #7f8c8d; display: inline-block; width: 90px; }
        .etat { padding: 3px 8px; border-radius: 10px; font-size: 12px; color: #fff; }
        .etat-solvable { background-color: #2ecc71; }
        .etat-non-solvable { background-color: #e74c3c; }
        .etat-nouveau { background-color: #95a5a6; }
        table { width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #34495e; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .etat-dette { padding: 3px 8px; border-radius: 10px; font-size: 12px; color: #fff; }
        .etat-dette-soldee { background-color: #2ecc71; }
        .etat-dette-non-soldee { background-color: #e67e22; }
    </style>
</head>
<body>
    <div class="nav">
        <div><strong>Bienvenue, <?= htmlspecialchars($client['prenom']) ?></strong></div>
        <a href="<?= BASE_URL ?>/logout">Déconnexion</a>
    </div>

    <h1>Ma fiche</h1>

    <div class="fiche">
        <div class="avatar">&#128100;</div>
        <div class="infos">
            <p><span class="label">Nom</span> <?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?></p>
            <p><span class="label">Email</span> <?= htmlspecialchars($client['email']) ?></p>
            <p><span class="label">Téléphone</span> <?= htmlspecialchars($client['telephone'] ?? '-') ?></p>
            <p><span class="label">État</span>
                <?php $etatClass = 'etat-' . str_replace(' ', '-', $client['etat_client']); ?>
                <span class="etat <?= $etatClass ?>"><?= htmlspecialchars($client['etat_client']) ?></span>
            </p>
        </div>
    </div>

    <h2>Liste de mes dettes</h2>
    <table>
        <thead>
            <tr>
                <th>Numéro</th>
                <th>Montant</th>
                <th>Date</th>
                <th>État</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($dettes)): ?>
                <?php foreach ($dettes as $d): ?>
                    <?php $etatDetteClass = 'etat-dette-' . str_replace(' ', '-', $d['etat_dette']); ?>
                    <tr>
                        <td><?= htmlspecialchars($d['numero']) ?></td>
                        <td><?= number_format((float) $d['montant'], 0, ',', ' ') ?></td>
                        <td><?= htmlspecialchars((new DateTime($d['date']))->format('d/m/y')) ?></td>
                        <td><span class="etat-dette <?= $etatDetteClass ?>"><?= htmlspecialchars($d['etat_dette']) ?></span></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center; color: #7f8c8d;">Aucune dette enregistrée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
