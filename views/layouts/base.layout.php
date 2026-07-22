<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Gestion des Dettes' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background: #f8f9fa; }
        .container { margin-top: 30px; }
        .card { border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .table th { background: #e9ecef; }
        .badge-success { background: #28a745; }
        .badge-warning { background: #ffc107; color: #212529; }
        .badge-danger { background: #dc3545; }
        .badge-info { background: #17a2b8; }
        .btn-sm { margin-right: 5px; }
        .navbar-brand { font-weight: bold; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= url('clients') ?>">
                <i class="fas fa-money-bill-wave"></i> Gestion des Dettes
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= url('clients') ?>">Clients</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('dettes') ?>">Dettes</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= url('dettes/non-soldees') ?>">Non soldées</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        <?php if ($flash = flash('success')): ?>
            <div class="alert alert-success alert-dismissible fade show mt-3">
                <i class="fas fa-check-circle"></i> <?= $flash ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($flash = flash('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show mt-3">
                <i class="fas fa-exclamation-circle"></i> <?= $flash ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        
        <?php if ($flash = flash('warning')): ?>
            <div class="alert alert-warning alert-dismissible fade show mt-3">
                <i class="fas fa-exclamation-triangle"></i> <?= $flash ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-fermer les alertes après 5 secondes
        setTimeout(() => {
            document.querySelectorAll('.alert').forEach(el => {
                const closeBtn = el.querySelector('.btn-close');
                if (closeBtn) closeBtn.click();
            });
        }, 5000);
    </script>
</body>
</html>