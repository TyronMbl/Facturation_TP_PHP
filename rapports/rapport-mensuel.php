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
$ce_mois = date('Y-m');

$stats = [
    'total_ht' => 0,
    'total_ttc' => 0,
    'nb_factures' => 0,
    'jours' => []
];

foreach ($factures as $f) {
    if (strpos($f['date'], $ce_mois) === 0) {
        $stats['total_ht'] += $f['total_ht'];
        $stats['total_ttc'] += $f['total_ttc'];
        $stats['nb_factures']++;
        
        $jour = $f['date'];
        if (!isset($stats['jours'][$jour])) {
            $stats['jours'][$jour] = [
                'nb_factures' => 0,
                'total_ht' => 0,
                'total_ttc' => 0
            ];
        }
        $stats['jours'][$jour]['nb_factures']++;
        $stats['jours'][$jour]['total_ht'] += $f['total_ht'];
        $stats['jours'][$jour]['total_ttc'] += $f['total_ttc'];
    }
}

// Trier par date
ksort($stats['jours']);
$nb_jours_actifs = count($stats['jours']);
$moyenne_quotidienne = $nb_jours_actifs > 0 ? ($stats['total_ttc'] / $nb_jours_actifs) : 0;

include __DIR__ . '/../includes/header.php';
?>

<div class="space-y-6">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="card-title">Rapport mensuel</h2>
                    <p class="opacity-70">Analyse de performance pour <?php echo date('m/Y'); ?></p>
                </div>
                <div class="badge badge-outline">Mois en cours</div>
            </div>
            <div class="stats stats-vertical lg:stats-horizontal bg-base-200">
                <div class="stat">
                    <div class="stat-title">Chiffre d'affaires (TTC)</div>
                    <div class="stat-value text-primary"><?php echo number_format($stats['total_ttc'], 0, ',', ' '); ?></div>
                    <div class="stat-desc">CDF</div>
                </div>
                <div class="stat">
                    <div class="stat-title">Moyenne quotidienne</div>
                    <div class="stat-value text-accent"><?php echo number_format($moyenne_quotidienne, 0, ',', ' '); ?></div>
                    <div class="stat-desc">CDF / jour</div>
                </div>
                <div class="stat">
                    <div class="stat-title">Volume de transactions</div>
                    <div class="stat-value"><?php echo $stats['nb_factures']; ?></div>
                    <div class="stat-desc">Ventes</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h3 class="card-title">Evolution quotidienne des ventes</h3>
            <?php if (empty($stats['jours'])): ?>
                <div class="alert">
                    <span>Aucune donnee disponible pour ce mois.</span>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="table table-zebra">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Ventes</th>
                                <th>Total HT</th>
                                <th>Total TTC</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($stats['jours'] as $date => $d): ?>
                                <tr>
                                    <td class="font-semibold"><?php echo date('d/m/Y', strtotime($date)); ?></td>
                                    <td><span class="badge badge-outline"><?php echo $d['nb_factures']; ?></span></td>
                                    <td><?php echo number_format($d['total_ht'], 0, ',', ' '); ?> CDF</td>
                                    <td class="font-semibold text-primary"><?php echo number_format($d['total_ttc'], 0, ',', ' '); ?> CDF</td>
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
