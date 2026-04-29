<?php
session_start();
require_once __DIR__ . "/../config/config.php";
require_once __DIR__ . "/../includes/fonctions-auth.php";

if (isset($_SESSION["utilisateur"])) {
    header("Location: " . BASE_URL . "/index.php");
    exit();
}

$erreur = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $identifiant = $_POST["identifiant"] ?? "";
    $mot_de_passe = $_POST["mot_de_passe"] ?? "";

    $utilisateur = verifierUtilisateur($identifiant, $mot_de_passe);
    if ($utilisateur) {
        $_SESSION["utilisateur"] = $utilisateur;

        $redirect = BASE_URL . "/index.php";
        if ($utilisateur["role"] === "Caissier") {
            $redirect = BASE_URL . "/modules/facturation/nouvelle-facture.php";
        } elseif ($utilisateur["role"] === "Manager") {
            $redirect = BASE_URL . "/modules/produits/liste.php";
        }

        header("Location: " . $redirect);
        exit();
    } else {
        $erreur = "Identifiants invalides.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr" data-theme="business">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=<?php echo time(); ?>">
    <title>Connexion - Nook Factures</title>
</head>
<body class="bg-gradient-to-br from-base-100 to-base-200 min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-4">
                <div class="avatar placeholder">
                    <div class="bg-primary text-primary-content rounded-full w-20">
                        <span class="text-2xl font-bold">NF</span>
                    </div>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-base-content mb-2">Nook Factures</h1>
            <p class="text-base-content/70">Connectez-vous à votre espace</p>
        </div>

        <!-- Card -->
        <div class="card bg-base-100 shadow-2xl">
            <div class="card-body">
                <!-- Alert d'erreur -->
                <?php if ($erreur): ?>
                    <div class="alert alert-error shadow-lg mb-6">
                        <div class="flex gap-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l-2-2m0 0l-2-2m2 2l2-2m-2 2l-2 2m2-2l2 2m-2-2l-2-2m2 2l2 2m0 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span><?php echo htmlspecialchars(
                                $erreur,
                            ); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Form -->
                <form method="POST" class="space-y-5">
                    <!-- Identifiant -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Identifiant</span>
                        </label>
                        <div class="relative">
                            <input
                                type="text"
                                name="identifiant"
                                placeholder="Ex: admin"
                                required
                                autofocus
                                class="input input-bordered w-full pl-10 focus:outline-none focus:ring-2 focus:ring-primary"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Mot de passe -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Mot de passe</span>
                        </label>
                        <div class="relative">
                            <input
                                type="password"
                                name="mot_de_passe"
                                placeholder="••••••••"
                                required
                                class="input input-bordered w-full pl-10 focus:outline-none focus:ring-2 focus:ring-primary"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-base-content/40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-full mt-6 text-lg font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Se connecter
                    </button>
                </form>

                <!-- Info Box -->
                <div class="divider">Identifiants de test</div>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between items-center bg-base-200 p-2 rounded-lg">
                        <span class="font-semibold">Admin:</span>
                        <code class="text-xs bg-base-300 px-2 py-1 rounded">admin / admin123</code>
                    </div>
                    <div class="flex justify-between items-center bg-base-200 p-2 rounded-lg">
                        <span class="font-semibold">Caissier:</span>
                        <code class="text-xs bg-base-300 px-2 py-1 rounded">caissier / caissier123</code>
                    </div>
                    <div class="flex justify-between items-center bg-base-200 p-2 rounded-lg">
                        <span class="font-semibold">Manager:</span>
                        <code class="text-xs bg-base-300 px-2 py-1 rounded">manager / manager123</code>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-base-content/60 text-sm">
            <p>© 2026 Nook Factures. Tous droits réservés.</p>
        </div>
    </div>
</body>
</html>
