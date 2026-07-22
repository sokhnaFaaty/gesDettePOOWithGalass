<?php
$title = 'Liste des dettes';
// Query string qui conserve les filtres actifs dans les liens de pagination
$qs = fn($p) => '?' . http_build_query([
    'etat_client' => $etatClient,
    'etat_dette'  => $etatDette,
    'page'        => $p,
]);
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-file-invoice"></i> Dettes</h2>
    <div>
        <a href="<?= url('dettes/non-soldees') ?>" class="btn btn-warning">
            <i class="fas fa-exclamation-triangle"></i> Non soldées
        </a>
        <a href="<?= url('dettes/soldees') ?>" class="btn btn-success">
            <i class="fas fa-check-circle"></i> Soldées
        </a>
    </div>
</div>

<form method="GET" action="<?= url('dettes') ?>" class="row g-2 align-items-center mb-3">
    <div class="col-auto">
        <select name="etat_client" class="form-select">
            <option value="">-- État client --</option>
            <option value="nouveau"      <?= $etatClient === 'nouveau'      ? 'selected' : '' ?>>Nouveau</option>
            <option value="solvable"     <?= $etatClient === 'solvable'     ? 'selected' : '' ?>>Solvable</option>
            <option value="non solvable" <?= $etatClient === 'non solvable' ? 'selected' : '' ?>>Non solvable</option>
        </select>
    </div>
    <div class="col-auto">
        <select name="etat_dette" class="form-select">
            <option value="">-- État dette --</option>
            <option value="non soldee" <?= $etatDette === 'non soldee' ? 'selected' : '' ?>>Non soldée</option>
            <option value="soldee"     <?= $etatDette === 'soldee'     ? 'selected' : '' ?>>Soldée</option>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-primary"><i class="fas fa-filter"></i> Filtrer</button>
        <a href="<?= url('dettes') ?>" class="btn btn-outline-secondary">Réinitialiser</a>
    </div>
</form>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>N° Dette</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>État</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dettes)): ?>
                    <tr><td colspan="5" class="text-center text-muted">Aucune dette ne correspond.</td></tr>
                <?php else: ?>
                    <?php foreach ($dettes as $dette): ?>
                    <tr>
                        <td><?= htmlspecialchars($dette['numero']) ?></td>
                        <td><?= htmlspecialchars($dette['prenom'] . ' ' . $dette['nom']) ?></td>
                        <td><?= date('d/m/Y', strtotime($dette['date'])) ?></td>
                        <td><?= number_format((float) $dette['montant'], 0, ',', ' ') ?> FCFA</td>
                        <td>
                            <span class="badge <?= $dette['etat_dette'] === 'soldee' ? 'badge-success' : 'badge-warning' ?>">
                                <?= htmlspecialchars($dette['etat_dette']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($totalPages > 1): ?>
        <nav aria-label="Pagination des dettes">
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= url('dettes' . $qs($page - 1)) ?>">&laquo;</a>
                </li>
                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                    <a class="page-link" href="<?= url('dettes' . $qs($p)) ?>"><?= $p ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= url('dettes' . $qs($page + 1)) ?>">&raquo;</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>
