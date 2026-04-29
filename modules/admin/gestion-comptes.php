<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../includes/fonctions-auth.php';

if ($_SESSION['utilisateur']['role'] !== 'Super Administrateur') {
    die("Accès refusé.");
}

$utilisateurs = lireUtilisateurs();
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<div class="card bg-base-100 shadow">
    <div class="card-body">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h2 class="card-title">Gestion des comptes</h2>
            <a href="ajouter-compte.php" class="btn btn-primary">Ajouter un compte</a>
        </div>
        <?php if (empty($utilisateurs)): ?>
            <div class="alert">
                <span>Aucun utilisateur enregistre.</span>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Identifiant</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($utilisateurs as $u): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($u['identifiant']); ?></td>
                                <td><span class="badge badge-outline"><?php echo htmlspecialchars($u['role']); ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
