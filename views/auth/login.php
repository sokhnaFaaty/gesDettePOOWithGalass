<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f6f9; color: #333; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .card { background: #fff; padding: 30px 35px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.15); width: 320px; }
        h1 { color: #2c3e50; font-size: 22px; margin-top: 0; text-align: center; }
        label { display: block; margin-top: 14px; margin-bottom: 4px; font-size: 14px; color: #555; }
        input { width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; margin-top: 22px; padding: 10px; background-color: #34495e; color: #fff; border: none; border-radius: 4px; font-size: 15px; cursor: pointer; }
        button:hover { background-color: #2c3e50; }
        .erreur { background: #fdecea; color: #e74c3c; padding: 8px 10px; border-radius: 4px; font-size: 14px; margin-top: 14px; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Connexion</h1>

        <?php if (!empty($erreur)): ?>
            <div class="erreur"><?= htmlspecialchars($erreur) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= BASE_URL ?>/authenticate">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required autofocus>

            <label for="mot_de_passe">Mot de passe</label>
            <input type="password" id="mot_de_passe" name="mot_de_passe" required>

            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
