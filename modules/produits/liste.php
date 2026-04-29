<?php
require_once __DIR__ . "/../../config/config.php";
require_once __DIR__ . "/../../auth/session.php";
require_once __DIR__ . "/../../includes/fonctions-produits.php";

$produits = lireProduits();
?>
<?php include __DIR__ . "/../../includes/header.php"; ?>
<div class="card bg-base-100 shadow">
    <div class="card-body">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h2 class="card-title">Liste des produits</h2>
            <a href="enregistrer.php" class="btn btn-primary">Nouveau produit</a>
        </div>
        <?php if (empty($produits)): ?>
            <div class="alert">
                <span>Aucun produit enregistre pour le moment.</span>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Code-barres</th>
                            <th>Nom</th>
                            <th>Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produits as $p): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($p["code_barre"]); ?></td>
                                <td><?php echo htmlspecialchars($p["nom"]); ?></td>
                                <td><?php echo number_format($p["prix_unitaire_ht"], 0, ',', ' '); ?> CDF</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . "/../../includes/footer.php"; ?>
