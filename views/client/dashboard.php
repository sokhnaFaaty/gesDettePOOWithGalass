<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma fiche</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <?php $active = 'profil'; include_once __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include_once __DIR__ . '/../partials/topbar.php'; ?>

        <main class="page-content">
            <div class="page-header">
                <h2>Ma fiche</h2>
            </div>

            <div class="fiche">
                <div class="avatar-lg"><i class="fas fa-user"></i></div>
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

            <span class="section-title">Liste de mes dettes</span>

            <div class="table-wrapper">
                <table class="data-table">
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
                                <td colspan="4" style="text-align: center; color: #7f8c8d; padding: 24px;">Aucune dette enregistrée.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
