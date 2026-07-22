<?php $title = 'Liste des dettes'; ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h2><i class="fas fa-file-invoice"></i> Dettes</h2>
    <div>
        <a href="<?= url('dettes/ajouter') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle dette
        </a>
        <a href="<?= url('dettes/non-soldees') ?>" class="btn btn-warning">
            <i class="fas fa-exclamation-triangle"></i> Non soldées
        </a>
    </div>
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
                    <th>État</th>
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
                        <span class="badge <?= $dette['etat'] == 'soldee' ? 'badge-success' : 'badge-warning' ?>">
                            <?= $dette['etat'] ?>
                        </span>
                    </td>
                    <td>
                        <a href="<?= url('dettes/modifier/' . $dette['id']) ?>" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <?php if ($dette['etat'] == 'non_soldee'): ?>
                            <a href="<?= url('dettes/soldeer/' . $dette['id']) ?>" class="btn btn-success btn-sm">
                                <i class="fas fa-check"></i>
                            </a>
                            <a href="<?= url('dettes/supprimer/' . $dette['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Supprimer cette dette ?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div