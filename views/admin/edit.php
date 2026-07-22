<!DOCTYPE html><!-- Document HTML5 -->
<html lang="fr"><!-- Racine du document, en français -->
<head>
    <meta charset="UTF-8"><!-- Encodage des caractères -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- Page adaptée aux mobiles -->
    <title>Modifier un client</title><!-- Titre de l'onglet du navigateur -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css"><!-- Feuille de style commune -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"><!-- Icônes Font Awesome -->
</head>
<body>
    <?php $active = 'clients'; include_once __DIR__ . '/../partials/sidebar.php'; ?><!-- Sidebar avec "Clients" actif -->

    <div class="main-wrapper">
        <?php include_once __DIR__ . '/../partials/topbar.php'; ?><!-- Barre du haut -->

        <main class="page-content">
            <div class="page-header"><!-- Bandeau titre + bouton retour -->
                <h2>Modifier <?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?></h2><!-- Titre dynamique avec le nom du client -->
                <div class="page-header-actions">
                    <a href="<?= BASE_URL ?>/clients" class="btn-restore"><i class="fas fa-arrow-left"></i> Retour à la liste</a><!-- Retour vers la liste -->
                </div>
            </div>

            <div class="card"><!-- Carte blanche contenant le formulaire -->
                <?php if (!empty($erreurs['general'])): // S'il existe une erreur générale ?>
                    <div class="erreur"><?= htmlspecialchars($erreurs['general']) ?></div><!-- Message d'erreur général -->
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/clients/update?id=<?= $client['id'] ?>" novalidate autocomplete="off"><!-- POST vers update() ; l'id du client à modifier est passé dans l'URL -->
                    <div class="form-group"><!-- Bloc "Prénom" -->
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" class="<?= !empty($erreurs['prenom']) ? 'input-error' : '' ?>" value="<?= htmlspecialchars($client['prenom']) ?>"><!-- Préremplit avec la valeur actuelle du client -->
                        <?php if (!empty($erreurs['prenom'])): ?><span class="form-error"><?= htmlspecialchars($erreurs['prenom']) ?></span><?php endif; ?>
                    </div>

                    <div class="form-group"><!-- Bloc "Nom" -->
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" class="<?= !empty($erreurs['nom']) ? 'input-error' : '' ?>" value="<?= htmlspecialchars($client['nom']) ?>">
                        <?php if (!empty($erreurs['nom'])): ?><span class="form-error"><?= htmlspecialchars($erreurs['nom']) ?></span><?php endif; ?>
                    </div>

                    <div class="form-group"><!-- Bloc "Email" -->
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="<?= !empty($erreurs['email']) ? 'input-error' : '' ?>" value="<?= htmlspecialchars($client['email']) ?>">
                        <?php if (!empty($erreurs['email'])): ?><span class="form-error"><?= htmlspecialchars($erreurs['email']) ?></span><?php endif; ?>
                    </div>

                    <div class="form-group"><!-- Bloc "Téléphone" -->
                        <label for="telephone">Téléphone</label>
                        <input type="text" id="telephone" name="telephone" class="<?= !empty($erreurs['telephone']) ? 'input-error' : '' ?>" value="<?= htmlspecialchars($client['telephone'] ?? '') ?>"><!-- '-' remplacé par chaîne vide si téléphone absent -->
                        <?php if (!empty($erreurs['telephone'])): ?><span class="form-error"><?= htmlspecialchars($erreurs['telephone']) ?></span><?php endif; ?>
                    </div>

                    <div class="form-group"><!-- Bloc "État" -->
                        <label for="etat_client">État</label>
                        <select id="etat_client" name="etat_client" class="<?= !empty($erreurs['etat_client']) ? 'input-error' : '' ?>">
                            <?php foreach (['nouveau' => 'Nouveau', 'solvable' => 'Solvable', 'non solvable' => 'Non solvable'] as $value => $label): // Parcourt les 3 états possibles ?>
                                <option value="<?= $value ?>" <?= $client['etat_client'] === $value ? 'selected' : '' ?>><?= $label ?></option><!-- Présélectionne l'état actuel du client -->
                            <?php endforeach; ?>
                        </select>
                        <?php if (!empty($erreurs['etat_client'])): ?><span class="form-error"><?= htmlspecialchars($erreurs['etat_client']) ?></span><?php endif; ?>
                    </div>

                    <button type="submit" class="btn-submit">Enregistrer les modifications</button><!-- Bouton de soumission -->
                </form>
            </div>
        </main>
    </div>
</body>
</html>
