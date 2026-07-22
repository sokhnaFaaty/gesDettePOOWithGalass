<?php
$prenom = $_SESSION['user']['prenom'] ?? $_SESSION['user']['nom'] ?? '';
?>
<header class="topbar">
    <div class="topbar-user">
        <span>Bonjour <?= htmlspecialchars($prenom) ?></span>
        <div class="avatar"><i class="fas fa-user"></i></div>
    </div>
</header>
