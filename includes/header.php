<!DOCTYPE html>
<html lang="fr" data-theme="business">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/assets/css/style.css?v=<?php echo time(); ?>">
    <title>Nook Factures</title>
</head>
<body>
    <?php
    $role = $_SESSION["utilisateur"]["role"];
    $current_path = $_SERVER["REQUEST_URI"] ?? "";
    $navActive = function ($needle) use ($current_path) {
        return strpos($current_path, $needle) !== false ? "menu-active" : "";
    };
    ?>
    <div class="drawer lg:drawer-open">
        <input id="app-drawer" type="checkbox" class="drawer-toggle">
        <div class="drawer-content bg-base-200 min-h-screen">
            <div class="navbar bg-base-100 border-b">
                <div class="flex-none lg:hidden">
                    <label for="app-drawer" class="btn btn-ghost btn-square" aria-label="Ouvrir le menu">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </label>
                </div>
                <div class="flex-1 gap-4">
                    <div class="avatar placeholder">
                        <div class="bg-primary text-primary-content rounded-full w-10">
                            <span>NF</span>
                        </div>
                    </div>
                    <div class="hidden sm:block">
                        <div class="text-xs opacity-70">Bienvenue</div>
                        <div class="font-semibold">
                            <?php echo htmlspecialchars(
                                $_SESSION["utilisateur"]["nom_complet"],
                            ); ?>
                        </div>
                    </div>
                    <div class="badge badge-outline">
                        <?php echo htmlspecialchars($role); ?>
                    </div>
                </div>
                <div class="flex-none gap-2">
                    <div class="form-control hidden md:block">
                        <input type="text" placeholder="Rechercher" class="input input-bordered w-56" />
                    </div>
                    <button class="btn btn-ghost btn-circle" type="button" aria-label="Notifications">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.4-1.4A2 2 0 0118 14V9a6 6 0 10-12 0v5a2 2 0 01-.6 1.4L4 17h5m6 0a3 3 0 11-6 0" />
                        </svg>
                    </button>
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar placeholder">
                            <div class="bg-neutral text-neutral-content rounded-full w-9">
                                <span class="text-xs">NF</span>
                            </div>
                        </div>
                        <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box w-52 mt-3 shadow">
                            <li><span class="text-xs opacity-70">Connecté</span></li>
                            <li><a href="<?php echo BASE_URL; ?>/auth/logout.php">Déconnexion</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <main class="p-6">
