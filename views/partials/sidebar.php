<?php
$role = $_SESSION['user']['role'] ?? null;
$active = $active ?? '';
?>
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-icon"><i class="fas fa-hand-holding-dollar"></i></div>
        <div class="logo-text">
            <span class="logo-title">GesDette</span>
            <span class="logo-sub">Gestion des dettes clients</span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <?php if ($role === 'admin'): ?>
            <a href="<?= BASE_URL ?>/clients" class="nav-item <?= $active === 'clients' ? 'active' : '' ?>">
                <i class="fas fa-users"></i>
                <span>Clients</span>
            </a>
        <?php elseif ($role === 'client'): ?>
            <a href="<?= BASE_URL ?>/profil" class="nav-item <?= $active === 'profil' ? 'active' : '' ?>">
                <i class="fas fa-id-card"></i>
                <span>Ma fiche</span>
            </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer">
        <a href="<?= BASE_URL ?>/logout" class="nav-item logout">
            <i class="fas fa-right-from-bracket"></i>
            <span>Déconnexion</span>
        </a>
    </div>
</aside>
