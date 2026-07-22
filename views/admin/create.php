<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un client</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <?php $active = 'clients'; include_once __DIR__ . '/../partials/sidebar.php'; ?>

    <div class="main-wrapper">
        <?php include_once __DIR__ . '/../partials/topbar.php'; ?>

        <main class="page-content">
            <div class="page-header">
                <h2>Ajouter un client</h2>
                <div class="page-header-actions">
                    <a href="<?= BASE_URL ?>/clients" class="btn-restore"><i class="fas fa-arrow-left"></i> Retour à la liste</a>
                </div>
            </div>

            <div class="card">
                <?php if (!empty($erreur)): ?>
                    <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/clients/store">
                    <div class="form-group">
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" required>
                    </div>

                    <div class="form-group">
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="mot_de_passe">Mot de passe</label>
                        <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                    </div>

                    <div class="form-group">
                        <label for="telephone">Téléphone</label>
                        <input type="text" id="telephone" name="telephone">
                    </div>

                    <div class="form-group">
                        <label for="etat_client">État</label>
                        <select id="etat_client" name="etat_client">
                            <option value="nouveau">Nouveau</option>
                            <option value="solvable">Solvable</option>
                            <option value="non solvable">Non solvable</option>
                        </select>
                    </div>

                    <button type="submit" class="btn-submit">Enregistrer</button>
                </form>
            </div>
        </main>
    </div>
</body>
</html>
