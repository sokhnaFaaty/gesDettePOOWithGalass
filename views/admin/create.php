<!DOCTYPE html><!-- Document HTML5 -->
<html lang="fr"><!-- Racine du document, en français -->
<head>
    <meta charset="UTF-8"><!-- Encodage des caractères -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- Page adaptée aux mobiles -->
    <title>Ajouter un client</title><!-- Titre de l'onglet du navigateur -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css"><!-- Feuille de style commune -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"><!-- Icônes Font Awesome -->
</head>
<body>
    <?php $active = 'clients'; include_once __DIR__ . '/../partials/sidebar.php'; ?><!-- Sidebar avec "Clients" marqué comme actif -->

    <div class="main-wrapper">
        <?php include_once __DIR__ . '/../partials/topbar.php'; ?><!-- Barre du haut -->

        <main class="page-content">
            <div class="page-header"><!-- Bandeau titre + bouton retour -->
                <h2>Ajouter un client</h2><!-- Titre de la page -->
                <div class="page-header-actions">
                    <a href="<?= BASE_URL ?>/clients" class="btn-restore"><i class="fas fa-arrow-left"></i> Retour à la liste</a><!-- Retour vers la liste des clients -->
                </div>
            </div>

            <div class="card"><!-- Carte blanche contenant le formulaire -->
                <?php if (!empty($erreurs['general'])): // S'il existe une erreur générale (non liée à un champ précis) ?>
                    <div class="erreur"><?= htmlspecialchars($erreurs['general']) ?></div><!-- Affiche le message d'erreur général -->
                <?php endif; ?>

                <form method="POST" action="<?= BASE_URL ?>/clients/store" novalidate autocomplete="off" enctype="multipart/form-data"><!-- POST vers store() ; novalidate désactive la validation navigateur ; enctype nécessaire pour envoyer un fichier -->
                    <div class="form-group"><!-- Bloc "Photo" -->
                        <label for="photo">Photo (optionnelle)</label><!-- Libellé du champ -->
                        <div class="photo-upload"><!-- Conteneur aperçu + champ fichier -->
                            <div class="photo-preview" id="photoPreview"><i class="fas fa-user"></i></div><!-- Cercle d'aperçu, icône par défaut tant qu'aucune image n'est choisie -->
                            <input type="file" id="photo" name="photo" accept="image/png, image/jpeg, image/webp, image/gif" class="<?= !empty($erreurs['photo']) ? 'input-error' : '' ?>"><!-- Sélecteur de fichier ; bordure rouge si erreur de validation -->
                        </div>
                        <?php if (!empty($erreurs['photo'])): ?><span class="form-error"><?= htmlspecialchars($erreurs['photo']) ?></span><?php endif; ?><!-- Message d'erreur sous le champ, si présent -->
                    </div>

                    <div class="form-group"><!-- Bloc "Prénom" -->
                        <label for="prenom">Prénom</label>
                        <input type="text" id="prenom" name="prenom" class="<?= !empty($erreurs['prenom']) ? 'input-error' : '' ?>" value="<?= htmlspecialchars($old['prenom'] ?? '') ?>"><!-- Reprend la valeur saisie précédemment en cas d'erreur -->
                        <?php if (!empty($erreurs['prenom'])): ?><span class="form-error"><?= htmlspecialchars($erreurs['prenom']) ?></span><?php endif; ?>
                    </div>

                    <div class="form-group"><!-- Bloc "Nom" -->
                        <label for="nom">Nom</label>
                        <input type="text" id="nom" name="nom" class="<?= !empty($erreurs['nom']) ? 'input-error' : '' ?>" value="<?= htmlspecialchars($old['nom'] ?? '') ?>">
                        <?php if (!empty($erreurs['nom'])): ?><span class="form-error"><?= htmlspecialchars($erreurs['nom']) ?></span><?php endif; ?>
                    </div>

                    <div class="form-group"><!-- Bloc "Email" -->
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" class="<?= !empty($erreurs['email']) ? 'input-error' : '' ?>" value="<?= htmlspecialchars($old['email'] ?? '') ?>" autocomplete="off"><!-- autocomplete off pour éviter que le navigateur propose un email déjà enregistré -->
                        <?php if (!empty($erreurs['email'])): ?><span class="form-error"><?= htmlspecialchars($erreurs['email']) ?></span><?php endif; ?>
                    </div>

                    <div class="form-group"><!-- Bloc "Mot de passe" -->
                        <label for="mot_de_passe">Mot de passe</label>
                        <input type="password" id="mot_de_passe" name="mot_de_passe" class="<?= !empty($erreurs['mot_de_passe']) ? 'input-error' : '' ?>" autocomplete="new-password"><!-- new-password empêche le navigateur de proposer un mot de passe déjà enregistré -->
                        <?php if (!empty($erreurs['mot_de_passe'])): ?><span class="form-error"><?= htmlspecialchars($erreurs['mot_de_passe']) ?></span><?php endif; ?>
                    </div>

                    <div class="form-group"><!-- Bloc "Téléphone" -->
                        <label for="telephone">Téléphone</label>
                        <input type="text" id="telephone" name="telephone" class="<?= !empty($erreurs['telephone']) ? 'input-error' : '' ?>" value="<?= htmlspecialchars($old['telephone'] ?? '') ?>">
                        <?php if (!empty($erreurs['telephone'])): ?><span class="form-error"><?= htmlspecialchars($erreurs['telephone']) ?></span><?php endif; ?>
                    </div>

                    <div class="form-group"><!-- Bloc "État" -->
                        <label for="etat_client">État</label>
                        <?php $etatOld = $old['etat_client'] ?? 'nouveau'; // État à présélectionner (valeur précédente ou "nouveau" par défaut) ?>
                        <select id="etat_client" name="etat_client" class="<?= !empty($erreurs['etat_client']) ? 'input-error' : '' ?>">
                            <option value="nouveau" <?= $etatOld === 'nouveau' ? 'selected' : '' ?>>Nouveau</option>
                            <option value="solvable" <?= $etatOld === 'solvable' ? 'selected' : '' ?>>Solvable</option>
                            <option value="non solvable" <?= $etatOld === 'non solvable' ? 'selected' : '' ?>>Non solvable</option>
                        </select>
                        <?php if (!empty($erreurs['etat_client'])): ?><span class="form-error"><?= htmlspecialchars($erreurs['etat_client']) ?></span><?php endif; ?>
                    </div>

                    <button type="submit" class="btn-submit">Enregistrer</button><!-- Bouton de soumission du formulaire -->
                </form>
            </div>
        </main>
    </div>

    <script><!-- Petit script d'aperçu instantané de la photo sélectionnée -->
        document.getElementById('photo').addEventListener('change', function (e) { // Se déclenche quand l'utilisateur choisit un fichier
            const preview = document.getElementById('photoPreview'); // Récupère le cercle d'aperçu
            const file = e.target.files[0]; // Récupère le premier (et seul) fichier sélectionné
            if (!file) { // Si l'utilisateur a annulé la sélection
                preview.innerHTML = '<i class="fas fa-user"></i>'; // On revient à l'icône par défaut
                return; // On arrête ici
            }
            const reader = new FileReader(); // Outil du navigateur pour lire le contenu d'un fichier local
            reader.onload = function (ev) { // Appelé une fois la lecture terminée
                preview.innerHTML = '<img src="' + ev.target.result + '" alt="Aperçu">'; // Affiche l'image lue directement dans le cercle
            };
            reader.readAsDataURL(file); // Lance la lecture du fichier (résultat en base64, utilisable comme src d'image)
        });
    </script>
</body>
</html>
