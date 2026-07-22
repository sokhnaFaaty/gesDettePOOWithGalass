<?php
$role = $_SESSION['user']['role'] ?? null; // Rôle de l'utilisateur connecté (admin, client, ou null si absent)
$active = $active ?? ''; // Nom de l'onglet actif transmis par la vue qui inclut ce partial (ex: 'clients', 'profil')
?>
<aside class="sidebar"><!-- Panneau de navigation fixe sur la gauche -->
    <div class="sidebar-logo"><!-- Zone du logo en haut de la sidebar -->
        <div class="logo-icon"><i class="fas fa-hand-holding-dollar"></i></div><!-- Icône de l'application -->
        <div class="logo-text"><!-- Nom + sous-titre à côté de l'icône -->
            <span class="logo-title">GesDette</span><!-- Nom de l'application -->
            <span class="logo-sub">Gestion des dettes clients</span><!-- Sous-titre -->
        </div>
    </div>

    <nav class="sidebar-nav"><!-- Liste des liens de navigation, différente selon le rôle -->
        <?php if ($role === 'admin'): // Utilisateur connecté en tant qu'admin ?>
            <a href="<?= BASE_URL ?>/clients" class="nav-item <?= $active === 'clients' ? 'active' : '' ?>"><!-- Lien "Clients", surligné si c'est la page actuelle -->
                <i class="fas fa-users"></i><!-- Icône du lien -->
                <span>Clients</span><!-- Texte du lien -->
            </a>
        <?php elseif ($role === 'client'): // Utilisateur connecté en tant que client ?>
            <a href="<?= BASE_URL ?>/profil" class="nav-item <?= $active === 'profil' ? 'active' : '' ?>"><!-- Lien "Ma fiche", surligné si c'est la page actuelle -->
                <i class="fas fa-id-card"></i><!-- Icône du lien -->
                <span>Ma fiche</span><!-- Texte du lien -->
            </a>
        <?php endif; ?>
    </nav>

    <div class="sidebar-footer"><!-- Pied de la sidebar : bouton de déconnexion -->
        <a href="<?= BASE_URL ?>/logout" class="nav-item logout"><!-- Lien de déconnexion, commun à tous les rôles -->
            <i class="fas fa-right-from-bracket"></i><!-- Icône de déconnexion -->
            <span>Déconnexion</span><!-- Texte du lien -->
        </a>
    </div>
</aside>
