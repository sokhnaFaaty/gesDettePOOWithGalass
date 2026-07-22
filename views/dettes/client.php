<?php $title = 'Dettes de ' . $client['nom'] . ' ' . $client['prenom']; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-user"></i> Dettes de <?= htmlspecialchars($client['nom'] . ' ' . $client['prenom']) ?></h2>
    <a href="<?= url('clients/fiche/' . $client['id']) ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Retour</a>
</div>

<div class="card">
    <div class="card-body">
        <?php if (empty($dettes)): ?>
            <p class="text-muted">Ce client n'a pas encore de dettes.</p>
        <?php else: ?>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>N° Dette</th>
                        <th>Date</th>
                        <th>Montant</th>
                        <th>État</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dettes as $dette): ?>
                    <tr>
                        <td><?= htmlspecialchars($dette['numero']) ?></td>
                        <td><?= date('d/m/Y', strtotime($dette['date'])) ?></td>
                        <td><?= number_format($dette['montant'], 0, ',', ' ') ?> FCFA</td>
                        <td>
                            <span class="badge <?= $dette['etat'] == 'soldee' ? 'badge-success' : 'badge-warning' ?>">
                                <?= $dette['etat'] ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($dette['etat'] == 'non_soldee'): ?>
                                <a href="<?= url('dettes/soldeer/' . $dette['id']) ?>" class="btn btn-success btn-sm">
                                    <i class="fas fa-check"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>