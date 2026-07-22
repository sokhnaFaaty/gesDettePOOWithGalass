<?php $title = 'Dettes non soldées'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-exclamation-triangle"></i> Dettes non soldées</h2>
    <a href="<?= url('dettes') ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Toutes les dettes</a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($dettes)): ?>
            <p class="text-muted">Aucune dette non soldée.</p>
        <?php else: ?>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>N° Dette</th>
                        <th>Client</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dettes as $dette): ?>
                    <tr>
                        <td><?= htmlspecialchars($dette['numero']) ?></td>
                        <td><?= htmlspecialchars($dette['nom'] . ' ' . $dette['prenom']) ?></td>
                        <td><?= date('d/m/Y', strtotime($dette['date'])) ?></td>
                        <td><?= number_format($dette['montant'], 0, ',', ' ') ?> FCFA</td>
                        <td>
                            <a href="<?= url('dettes/soldeer/' . $dette['id']) ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i> Soldeer
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>