<?php $title = 'Dettes de ' . (($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? '')); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-user"></i> Dettes de <?= htmlspecialchars(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? '')) ?></h2>
    <a href="<?= url('dettes') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($dettes)): ?>
            <p class="text-muted mb-0">Ce client n'a pas encore de dettes.</p>
        <?php else: ?>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>N° Dette</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>État</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dettes as $dette): ?>
                    <tr>
                        <td><?= htmlspecialchars($dette['numero']) ?></td>
                        <td><?= date('d/m/Y', strtotime($dette['date'])) ?></td>
                        <td><?= number_format((float) $dette['montant'], 0, ',', ' ') ?> FCFA</td>
                        <td>
                            <span class="badge <?= $dette['etat_dette'] === 'soldee' ? 'badge-success' : 'badge-warning' ?>">
                                <?= htmlspecialchars($dette['etat_dette']) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
