<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body style="display:block;">
    <div class="login-wrapper">
        <div class="login-card">
            <div class="logo-icon" style="width:56px;height:56px;font-size:24px;"><i class="fas fa-hand-holding-dollar"></i></div>
            <h1>GesDette</h1>
            <div class="logo-sub-center">Connectez-vous à votre espace</div>

            <?php if (!empty($erreur)): ?>
                <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/authenticate">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required autofocus>
                </div>

                <div class="form-group">
                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" required>
                </div>

                <button type="submit" class="btn-submit">Se connecter</button>
            </form>
        </div>
    </div>
</body>
</html>
