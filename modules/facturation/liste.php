<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../includes/fonctions-factures.php';

$factures = lireFactures();
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<div class="card bg-base-100 shadow">
    <div class="card-body">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h2 class="card-title">Historique des factures</h2>
            <a href="nouvelle-facture.php" class="btn btn-primary">Nouvelle facture</a>
        </div>
        <?php if (empty($factures)): ?>
            <div class="alert">
                <span>Aucune facture enregistree pour le moment.</span>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>ID Facture</th>
                            <th>Date</th>
                            <th>Caissier</th>
                            <th>Total TTC</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($factures as $f): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($f['id_facture']); ?></td>
                                <td><?php echo htmlspecialchars($f['date']); ?></td>
                                <td><?php echo htmlspecialchars($f['caissier']); ?></td>
                                <td><?php echo number_format($f['total_ttc'], 0, ',', ' '); ?> CDF</td>
                                <td>
                                    <a href="afficher-facture.php?id=<?php echo urlencode($f['id_facture']); ?>" class="btn btn-sm btn-outline">Voir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
