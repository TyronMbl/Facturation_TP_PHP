<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../includes/fonctions-factures.php';

$id = $_GET['id'] ?? '';
$factures = lireFactures();
$facture = null;

foreach ($factures as $f) {
    if ($f['id_facture'] === $id) {
        $facture = $f;
        break;
    }
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<div class="card bg-base-100 shadow">
    <div class="card-body">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h2 class="card-title">Facture <?php echo htmlspecialchars($id); ?></h2>
            <a href="liste.php" class="btn btn-ghost">Retour a la liste</a>
        </div>
        <?php if ($facture): ?>
            <div class="stats stats-vertical md:stats-horizontal bg-base-200">
                <div class="stat">
                    <div class="stat-title">Date</div>
                    <div class="stat-value text-primary"><?php echo $facture['date']; ?></div>
                    <div class="stat-desc"><?php echo $facture['heure']; ?></div>
                </div>
                <div class="stat">
                    <div class="stat-title">Caissier</div>
                    <div class="stat-value"><?php echo htmlspecialchars($facture['caissier']); ?></div>
                    <div class="stat-desc">Responsable</div>
                </div>
                <div class="stat">
                    <div class="stat-title">Total TTC</div>
                    <div class="stat-value text-secondary"><?php echo number_format($facture['total_ttc'], 0, ',', ' '); ?></div>
                    <div class="stat-desc">CDF</div>
                </div>
            </div>
            <div class="overflow-x-auto mt-4">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Article</th>
                            <th>Prix Unit</th>
                            <th>Qte</th>
                            <th>Sous-total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($facture['articles'] as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nom']); ?></td>
                                <td><?php echo number_format($item['prix_unitaire_ht'], 0, ',', ' '); ?> CDF</td>
                                <td><?php echo $item['quantite']; ?></td>
                                <td><?php echo number_format($item['sous_total_ht'], 0, ',', ' '); ?> CDF</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="stats stats-vertical md:stats-horizontal bg-base-200 mt-4">
                <div class="stat">
                    <div class="stat-title">Total HT</div>
                    <div class="stat-value"><?php echo number_format($facture['total_ht'], 0, ',', ' '); ?></div>
                    <div class="stat-desc">CDF</div>
                </div>
                <div class="stat">
                    <div class="stat-title">TVA (<?php echo TVA_RATE * 100; ?>%)</div>
                    <div class="stat-value"><?php echo number_format($facture['tva'], 0, ',', ' '); ?></div>
                    <div class="stat-desc">CDF</div>
                </div>
                <div class="stat">
                    <div class="stat-title">Total TTC</div>
                    <div class="stat-value text-primary"><?php echo number_format($facture['total_ttc'], 0, ',', ' '); ?></div>
                    <div class="stat-desc">CDF</div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <span>Facture introuvable.</span>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
