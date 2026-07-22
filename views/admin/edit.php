<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un client</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <?php $active = 'clients'; include_once __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include_once __DIR__ . '/../partials/topbar.php'; ?>

        <main class="page-content">
            <div class="page-header">
                <h2>Modifier <?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?></h2>
                <div class="page-header-actions">
                    <a href="<?= BASE_URL ?>/clients" class="btn-restore"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
                </div>
            </div>

            <div class="card">
                <form method="POST" action="<?= BASE_URL ?>/clients/update?id=<?= $client['id'] ?>">
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($client['prenom']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($client['nom']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($client['telephone'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="etat_client">État</label>
                        <select id="etat_client" name="etat_client">
                            <?php foreach (['nouveau' => 'Nouveau', 'solvable' => 'Solvable', 'non solvable' => 'Non solvable'] as $value => $label): ?>
                                <option value="<?= $value ?>" <?= $client['etat_client'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button type="submit" class="btn-submit">Enregistrer les modifications</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
