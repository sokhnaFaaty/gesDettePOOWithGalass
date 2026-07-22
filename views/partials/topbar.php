<?php
$prenom = $_SESSION['user']['prenom'] ?? $_SESSION['user']['nom'] ?? ''; // Prénom de l'utilisateur connecté (ou son nom, ou vide en secours)
?>
<header class="topbar"><!-- Barre verte fixée en haut de la zone principale -->
    <div class="topbar-user"><!-- Zone d'affichage de l'utilisateur connecté -->
        <span>Bonjour <?= htmlspecialchars($prenom) ?></span><!-- Message de bienvenue personnalisé -->
        <div class="avatar"><i class="fas fa-user"></i></div><!-- Avatar générique (icône) -->
    </div>
</header>
