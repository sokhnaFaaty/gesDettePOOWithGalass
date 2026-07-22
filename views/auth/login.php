<!DOCTYPE html><!-- Document HTML5 -->
<html lang="fr"><!-- Racine du document, en français -->
<head>
    <meta charset="UTF-8"><!-- Encodage des caractères -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- Page adaptée aux mobiles -->
    <title>Connexion</title><!-- Titre de l'onglet du navigateur -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css"><!-- Feuille de style commune -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"><!-- Icônes Font Awesome -->
</head>
<body style="display:block;"><!-- Pas de sidebar sur cette page : on annule le "display:flex" par défaut du body -->
    <div class="login-wrapper"><!-- Fond plein écran qui centre la carte de connexion -->
        <div class="login-card"><!-- Carte blanche contenant le formulaire -->
            <div class="logo-icon" style="width:56px;height:56px;font-size:24px;"><i class="fas fa-hand-holding-dollar"></i></div><!-- Icône/logo de l'application -->
            <h1>GesDette</h1><!-- Nom de l'application -->
            <div class="logo-sub-center">Connectez-vous à votre espace</div><!-- Sous-titre -->

            <?php if (!empty($erreurs['general'])): // Erreur générale : identifiants incorrects ?>
                <div class="erreur"><?= htmlspecialchars($erreurs['general']) ?></div><!-- Affiche le message d'erreur -->
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>/authenticate" novalidate><!-- Soumission vers authenticate() ; novalidate désactive la validation navigateur -->
                <div class="form-group"><!-- Bloc "Email" -->
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="<?= !empty($erreurs['email']) ? 'input-error' : '' ?>" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" autofocus><!-- Reprend l'email saisi en cas d'erreur ; le curseur se place ici au chargement -->
                    <?php if (!empty($erreurs['email'])): ?><span class="form-error"><?= htmlspecialchars($erreurs['email']) ?></span><?php endif; ?><!-- Message d'erreur sous le champ -->
                </div>

                <div class="form-group"><!-- Bloc "Mot de passe" -->
                    <label for="mot_de_passe">Mot de passe</label>
                    <input type="password" id="mot_de_passe" name="mot_de_passe" class="<?= !empty($erreurs['mot_de_passe']) ? 'input-error' : '' ?>"><!-- Jamais préremplit, pour des raisons de sécurité -->
                    <?php if (!empty($erreurs['mot_de_passe'])): ?><span class="form-error"><?= htmlspecialchars($erreurs['mot_de_passe']) ?></span><?php endif; ?>
                </div>

                <button type="submit" class="btn-submit">Se connecter</button><!-- Bouton de soumission du formulaire -->
            </form>
        </div>
    </div>
</body>
</html>
