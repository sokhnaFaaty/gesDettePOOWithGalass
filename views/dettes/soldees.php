<?php $title = 'Dettes soldées'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-check-circle"></i> Dettes soldées</h2>
    <a href="<?= url('dettes') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Toutes les dettes</a>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>N° Dette</th>
                    <th>Client</th>
                    <th>Date</th>
                    <th>Montant</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($dettes)): ?>
                    <tr><td colspan="4" class="text-center text-muted">Aucune dette soldée.</td></tr>
                <?php else: ?>
                    <?php foreach ($dettes as $dette): ?>
                    <tr>
                        <td><?= htmlspecialchars($dette['numero']) ?></td>
                        <td><?= htmlspecialchars($dette['prenom'] . ' ' . $dette['nom']) ?></td>
                        <td><?= date('d/m/Y', strtotime($dette['date'])) ?></td>
                        <td><?= number_format((float) $dette['montant'], 0, ',', ' ') ?> FCFA</td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($totalPages > 1): ?>
        <nav aria-label="Pagination des dettes soldées">
            <ul class="pagination justify-content-center mb-0">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= url('dettes/soldees?page=' . ($page - 1)) ?>">&laquo;</a>
                </li>
                <?php for ($p = 1; $p <= $totalPages; $p++): ?>
                <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                    <a class="page-link" href="<?= url('dettes/soldees?page=' . $p) ?>"><?= $p ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link" href="<?= url('dettes/soldees?page=' . ($page + 1)) ?>">&raquo;</a>
                </li>
            </ul>
        </nav>
        <?php endif; ?>
    </div>
</div>
