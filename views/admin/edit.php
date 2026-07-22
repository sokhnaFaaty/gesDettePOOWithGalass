<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un client</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f6f9; color: #333; }
        h1 { color: #2c3e50; }
        .nav { margin-bottom: 20px; }
        .nav a { margin-right: 15px; text-decoration: none; color: #34495e; font-weight: bold; }
        .nav a:hover { color: #2ecc71; }
        .card { background: #fff; padding: 25px 30px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); max-width: 480px; }
        label { display: block; margin-top: 14px; margin-bottom: 4px; font-size: 14px; color: #555; }
        input, select { width: 100%; padding: 8px 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { margin-top: 22px; padding: 10px 20px; background-color: #2ecc71; color: #fff; border: none; border-radius: 4px; font-size: 15px; cursor: pointer; }
        button:hover { background-color: #27ae60; }
    </style>
</head>
<body>
    <div class="nav">
        <a href="<?= BASE_URL ?>/clients">&larr; Retour à la liste</a>
    </div>

    <h1>Modifier <?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?></h1>

    <div class="card">
        <form method="POST" action="<?= BASE_URL ?>/clients/update?id=<?= $client['id'] ?>">
            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" value="<?= htmlspecialchars($client['prenom']) ?>" required>

            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="<?= htmlspecialchars($client['nom']) ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($client['email']) ?>" required>

            <label for="telephone">Téléphone</label>
            <input type="text" id="telephone" name="telephone" value="<?= htmlspecialchars($client['telephone'] ?? '') ?>">

            <label for="etat_client">État</label>
            <select id="etat_client" name="etat_client">
                <?php foreach (['nouveau' => 'Nouveau', 'solvable' => 'Solvable', 'non solvable' => 'Non solvable'] as $value => $label): ?>
                    <option value="<?= $value ?>" <?= $client['etat_client'] === $value ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>

            <button type="submit">Enregistrer les modifications</button>
        </form>
    </div>
</body>
</html>
