<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../auth/session.php';
require_once __DIR__ . '/../includes/fonctions-factures.php';

// Restriction aux managers et admins
if ($_SESSION['utilisateur']['role'] !== 'Manager' && $_SESSION['utilisateur']['role'] !== 'Super Administrateur') {
    header('Location: ' . BASE_URL . '/index.php');
    exit;
}

$factures = lireFactures();
$aujourdhui = date('Y-m-d');

$stats = [
    'total_ht' => 0,
    'total_tva' => 0,
    'total_ttc' => 0,
    'nb_factures' => 0,
    'produits' => []
];

foreach ($factures as $f) {
    if ($f['date'] === $aujourdhui) {
        $stats['total_ht'] += $f['total_ht'];
        $stats['total_tva'] += $f['tva'];
        $stats['total_ttc'] += $f['total_ttc'];
        $stats['nb_factures']++;
        
        foreach ($f['articles'] as $art) {
            $cb = $art['code_barre'];
            if (!isset($stats['produits'][$cb])) {
                $stats['produits'][$cb] = [
                    'nom' => $art['nom'],
                    'quantite' => 0,
                    'total_ht' => 0
                ];
            }
            $stats['produits'][$cb]['quantite'] += $art['quantite'];
            $stats['produits'][$cb]['total_ht'] += $art['sous_total_ht'];
        }
    }
}

// Trier les produits par quantité vendue
uasort($stats['produits'], function($a, $b) {
    return $b['quantite'] <=> $a['quantite'];
});

include __DIR__ . '/../includes/header.php';
?>

<div class="space-y-6">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="card-title">Rapport journalier</h2>
                    <p class="opacity-70">Resume de l'activite pour le <?php echo date('d/m/Y'); ?></p>
                </div>
                <div class="badge badge-outline">Jour en cours</div>
            </div>
            <div class="stats stats-vertical lg:stats-horizontal bg-base-200">
                <div class="stat">
                    <div class="stat-title">Total HT</div>
                    <div class="stat-value text-primary"><?php echo number_format($stats['total_ht'], 0, ',', ' '); ?></div>
                    <div class="stat-desc">CDF</div>
                </div>
                <div class="stat">
                    <div class="stat-title">Total TTC</div>
                    <div class="stat-value text-secondary"><?php echo number_format($stats['total_ttc'], 0, ',', ' '); ?></div>
                    <div class="stat-desc">CDF</div>
                </div>
                <div class="stat">
                    <div class="stat-title">TVA (<?php echo TVA_RATE * 100; ?>%)</div>
                    <div class="stat-value text-accent"><?php echo number_format($stats['total_tva'], 0, ',', ' '); ?></div>
                    <div class="stat-desc">CDF</div>
                </div>
                <div class="stat">
                    <div class="stat-title">Ventes</div>
                    <div class="stat-value"><?php echo $stats['nb_factures']; ?></div>
                    <div class="stat-desc">Factures</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h3 class="card-title">Details des produits vendus</h3>
            <?php if (empty($stats['produits'])): ?>
                <div class="alert">
                    <span>Aucune transaction enregistree pour cette journee.</span>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Designation</th>
                                <th>Quantite</th>
                                <th>Total HT</th>
                                <th>Part du CA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['produits'] as $p): ?>
                                <?php $part = $stats['total_ht'] > 0 ? ($p['total_ht'] / $stats['total_ht'] * 100) : 0; ?>
                                <tr>
                                    <td class="font-semibold"><?php echo htmlspecialchars($p['nom']); ?></td>
                                    <td><span class="badge badge-outline"><?php echo $p['quantite']; ?></span></td>
                                    <td class="font-semibold"><?php echo number_format($p['total_ht'], 0, ',', ' '); ?> CDF</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <progress class="progress progress-primary w-40" value="<?php echo round($part, 1); ?>" max="100"></progress>
                                            <span class="text-sm font-semibold"><?php echo round($part, 1); ?>%</span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
