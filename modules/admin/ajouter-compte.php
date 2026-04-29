<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../includes/fonctions-auth.php';

if ($_SESSION['utilisateur']['role'] !== 'Super Administrateur') {
    die("Accès refusé. Seuls les Super Administrateurs peuvent ajouter des comptes.");
}

$message_succes = "";
$message_erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = $_POST['identifiant'] ?? '';
    $nom_complet = $_POST['nom_complet'] ?? '';
    $mot_de_passe = $_POST['mot_de_passe'] ?? '';
    $role = $_POST['role'] ?? '';

    // Validation des champs
    if (empty($identifiant) || empty($nom_complet) || empty($mot_de_passe) || empty($role)) {
        $message_erreur = "Tous les champs sont obligatoires.";
    } elseif (strlen($mot_de_passe) < 6) {
        $message_erreur = "Le mot de passe doit contenir au moins 6 caractères.";
    } else {
        if (ajouterUtilisateur($identifiant, $nom_complet, $mot_de_passe, $role)) {
            $message_succes = "Compte utilisateur ajouté avec succès !";
            // Réinitialiser les champs du formulaire après succès
            $_POST = [];
        } else {
            $message_erreur = "L'identifiant existe déjà. Veuillez en choisir un autre.";
        }
    }
}
?>

<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="card bg-base-100 shadow">
    <div class="card-body">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <h2 class="card-title">Ajouter un compte utilisateur</h2>
            <a href="gestion-comptes.php" class="btn btn-ghost">Retour à la gestion des comptes</a>
        </div>

        <?php if ($message_succes): ?>
            <div class="alert alert-success">
                <span><?php echo htmlspecialchars($message_succes); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($message_erreur): ?>
            <div class="alert alert-error">
                <span><?php echo htmlspecialchars($message_erreur); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" class="grid gap-4 md:grid-cols-2">
            <label class="form-control">
                <span class="label-text">Identifiant</span>
                <input type="text" name="identifiant" placeholder="Ex: nouvel_utilisateur" required class="input input-bordered" value="<?php echo htmlspecialchars($_POST['identifiant'] ?? ''); ?>" />
            </label>
            <label class="form-control">
                <span class="label-text">Nom complet</span>
                <input type="text" name="nom_complet" placeholder="Ex: Jean Dupont" required class="input input-bordered" value="<?php echo htmlspecialchars($_POST['nom_complet'] ?? ''); ?>" />
            </label>
            <label class="form-control">
                <span class="label-text">Mot de passe</span>
                <input type="password" name="mot_de_passe" required class="input input-bordered" />
            </label>
            <label class="form-control">
                <span class="label-text">Rôle</span>
                <select name="role" required class="select select-bordered">
                    <option value="">Selectionner un rôle</option>
                    <option value="Caissier" <?php echo (($_POST['role'] ?? '') === 'Caissier') ? 'selected' : ''; ?>>Caissier</option>
                    <option value="Manager" <?php echo (($_POST['role'] ?? '') === 'Manager') ? 'selected' : ''; ?>>Manager</option>
                    <option value="Super Administrateur" <?php echo (($_POST['role'] ?? '') === 'Super Administrateur') ? 'selected' : ''; ?>>Super Administrateur</option>
                </select>
            </label>
            <div class="md:col-span-2 mt-4">
                <button type="submit" class="btn btn-primary">Ajouter l'utilisateur</button>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>