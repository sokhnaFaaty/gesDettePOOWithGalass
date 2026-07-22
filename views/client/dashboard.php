<!DOCTYPE html><!-- Document HTML5 -->
<html lang="fr"><!-- Racine du document, en français -->
<head>
    <meta charset="UTF-8"><!-- Encodage des caractères -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"><!-- Page adaptée aux mobiles -->
    <title>Ma fiche</title><!-- Titre de l'onglet du navigateur -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/app.css"><!-- Feuille de style commune -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"><!-- Icônes Font Awesome -->
</head>
<body>
    <?php $active = 'profil'; include_once __DIR__ . '/../partials/sidebar.php'; ?><!-- Sidebar avec "Ma fiche" actif (espace client) -->

    <div class="main-wrapper">
        <?php include_once __DIR__ . '/../partials/topbar.php'; ?><!-- Barre du haut -->

        <main class="page-content">
            <div class="page-header"><!-- Bandeau titre (pas d'action ici, juste consultation) -->
                <h2>Ma fiche</h2><!-- Titre de la page -->
            </div>

            <div class="fiche"><!-- Carte affichant les informations du client connecté -->
                <div class="avatar-lg"><!-- Grand cercle contenant la photo ou l'icône par défaut -->
                    <?php if (!empty($client['photo'])): // Si ce client a une photo enregistrée ?>
                        <img src="<?= BASE_URL ?>/uploads/clients/<?= htmlspecialchars($client['photo']) ?>" alt="<?= htmlspecialchars($client['prenom']) ?>"><!-- Affiche la vraie photo -->
                    <?php else: // Sinon, pas de photo ?>
                        <i class="fas fa-user"></i><!-- Icône générique -->
                    <?php endif; ?>
                </div>
                <div class="infos"><!-- Bloc texte des informations -->
                    <p><span class="label">Nom</span> <?= htmlspecialchars($client['prenom'] . ' ' . $client['nom']) ?></p><!-- Prénom + nom -->
                    <p><span class="label">Email</span> <?= htmlspecialchars($client['email']) ?></p><!-- Email -->
                    <p><span class="label">Téléphone</span> <?= htmlspecialchars($client['telephone'] ?? '-') ?></p><!-- Téléphone, "-" si absent -->
                    <p><span class="label">État</span>
                        <?php $etatClass = 'etat-' . str_replace(' ', '-', $client['etat_client']); // Classe CSS du badge d'état ?>
                        <span class="etat <?= $etatClass ?>"><?= htmlspecialchars($client['etat_client']) ?></span><!-- Badge coloré selon l'état -->
                    </p>
                </div>
            </div>

            <span class="section-title">Liste de mes dettes</span><!-- Sous-titre de la section suivante -->

            <div class="table-wrapper"><!-- Carte blanche contenant le tableau des dettes -->
                <table class="data-table">
                    <thead><!-- En-têtes des colonnes -->
                        <tr>
                            <th>Numéro</th><!-- Numéro de la dette -->
                            <th>Montant</th><!-- Montant dû -->
                            <th>Date</th><!-- Date de la dette -->
                            <th>État</th><!-- Soldée ou non -->
                        </tr>
                    </thead>
                    <tbody><!-- Corps du tableau : une ligne par dette du client connecté -->
                        <?php if (!empty($dettes)): // S'il existe au moins une dette ?>
                            <?php foreach ($dettes as $d): // Boucle sur chaque dette ?>
                                <?php $etatDetteClass = 'etat-dette-' . str_replace(' ', '-', $d['etat_dette']); // Classe CSS du badge ?>
                                <tr>
                                    <td><?= htmlspecialchars($d['numero']) ?></td><!-- Numéro de la dette -->
                                    <td><?= number_format((float) $d['montant'], 0, ',', ' ') ?></td><!-- Montant formaté -->
                                    <td><?= htmlspecialchars((new DateTime($d['date']))->format('d/m/y')) ?></td><!-- Date reformatée en jj/mm/aa -->
                                    <td><span class="etat-dette <?= $etatDetteClass ?>"><?= htmlspecialchars($d['etat_dette']) ?></span></td><!-- Badge coloré -->
                                </tr>
                            <?php endforeach; ?>
                        <?php else: // Aucune dette pour ce client ?>
                            <tr>
                                <td colspan="4" style="text-align: center; color: #7f8c8d; padding: 24px;">Aucune dette enregistrée.</td><!-- Message affiché à la place du tableau vide -->
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
