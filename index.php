<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/fonctions-factures.php';
// On n'inclut pas auth/session.php directement car il redirige vers login.php 
// Et si on est pas connecté, login.php redirige ici, créant une boucle.
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header('Location: ' . BASE_URL . '/auth/login.php');
    exit;
}
$factures = lireFactures();
$aujourdhui = date('Y-m-d');
$total_aujourdhui = 0;
$nb_aujourdhui = 0;
foreach ($factures as $facture) {
    if ($facture['date'] === $aujourdhui) {
        $total_aujourdhui += $facture['total_ttc'];
        $nb_aujourdhui++;
    }
}
$nb_factures = count($factures);
?>
<?php include __DIR__ . '/includes/header.php'; ?>
<div class="space-y-6">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="card-title">Tableau de bord</h2>
                    <p class="opacity-70">Bienvenue, <?php echo htmlspecialchars($_SESSION['utilisateur']['nom_complet']); ?></p>
                </div>
                <div class="join">
                    <a href="<?php echo BASE_URL; ?>/modules/facturation/nouvelle-facture.php" class="btn btn-primary join-item">Nouvelle facture</a>
                    <a href="<?php echo BASE_URL; ?>/modules/facturation/liste.php" class="btn join-item">Historique</a>
                </div>
            </div>
            <div class="stats stats-vertical lg:stats-horizontal bg-base-200">
                <div class="stat">
                    <div class="stat-title">Rôle actuel</div>
                    <div class="stat-value text-primary"><?php echo htmlspecialchars($_SESSION['utilisateur']['role']); ?></div>
                    <div class="stat-desc">Accès selon votre profil</div>
                </div>
                <div class="stat">
                    <div class="stat-title">Factures total</div>
                    <div class="stat-value"><?php echo $nb_factures; ?></div>
                    <div class="stat-desc">Historique global</div>
                </div>
                <div class="stat">
                    <div class="stat-title">Aujourd'hui</div>
                    <div class="stat-value text-success"><?php echo $nb_aujourdhui; ?></div>
                    <div class="stat-desc">Ventes du jour</div>
                </div>
                <div class="stat">
                    <div class="stat-title">Total TTC</div>
                    <div class="stat-value text-secondary"><?php echo number_format($total_aujourdhui, 0, ',', ' '); ?></div>
                    <div class="stat-desc">CDF aujourd'hui</div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="card bg-base-100 shadow lg:col-span-2">
            <div class="card-body">
                <h3 class="card-title">Activite recente</h3>
                <?php if (empty($factures)): ?>
                    <div class="alert">
                        <span>Aucune facture enregistree pour le moment.</span>
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="table table-zebra">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Date</th>
                                    <th>Caissier</th>
                                    <th>Total TTC</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice(array_reverse($factures), 0, 5) as $facture): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($facture['id_facture']); ?></td>
                                        <td><?php echo htmlspecialchars($facture['date']); ?></td>
                                        <td><?php echo htmlspecialchars($facture['caissier']); ?></td>
                                        <td><?php echo number_format($facture['total_ttc'], 0, ',', ' '); ?> CDF</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="card bg-base-100 shadow">
            <div class="card-body">
                <h3 class="card-title">Raccourcis</h3>
                <div class="space-y-3">
                    <a href="<?php echo BASE_URL; ?>/modules/facturation/nouvelle-facture.php" class="btn btn-primary btn-block">Demarrer une facture</a>
                    <a href="<?php echo BASE_URL; ?>/modules/facturation/liste.php" class="btn btn-outline btn-block">Consulter les factures</a>
                    <?php if ($_SESSION['utilisateur']['role'] === 'Manager' || $_SESSION['utilisateur']['role'] === 'Super Administrateur'): ?>
                        <a href="<?php echo BASE_URL; ?>/modules/produits/liste.php" class="btn btn-outline btn-block">Gerer les produits</a>
                        <a href="<?php echo BASE_URL; ?>/rapports/rapport-journalier.php" class="btn btn-ghost btn-block">Voir les rapports</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/includes/footer.php'; ?>
