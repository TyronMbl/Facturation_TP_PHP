            </main>
        </div>
        <div class="drawer-side">
            <label for="app-drawer" class="drawer-overlay"></label>
            <aside class="bg-base-100 w-72 min-h-screen border-r">
                <div class="px-6 py-6">
                    <div class="flex items-center gap-3">
                        <div class="avatar">
                            <div class="w-10 rounded-box">
                                <img src="<?php echo BASE_URL; ?>/NKF.png" alt="Nook Factures">
                            </div>
                        </div>
                        <div>
                            <div class="text-lg font-bold">Nook Factures</div>
                            <div class="text-xs opacity-70">Facturation & Gestion</div>
                        </div>
                    </div>
                </div>
                <ul class="menu p-4 gap-1">
                    <li class="menu-title">Principal</li>
                    <li><a href="<?php echo BASE_URL; ?>/index.php" class="<?php echo $navActive(
    "/index.php",
); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10.5l9-7 9 7V20a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1v-9.5z" />
                        </svg>
                        Tableau de bord
                    </a></li>
                    <li><a href="<?php echo BASE_URL; ?>/modules/facturation/nouvelle-facture.php" class="<?php echo $navActive(
    "/modules/facturation/nouvelle-facture.php",
); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Nouvelle facture
                    </a></li>
                    <li><a href="<?php echo BASE_URL; ?>/modules/facturation/liste.php" class="<?php echo $navActive(
    "/modules/facturation/liste.php",
); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6M9 9h6M9 13h6M9 17h6M5 5h.01M5 9h.01M5 13h.01M5 17h.01" />
                        </svg>
                        Historique factures
                    </a></li>
                    <?php if (
                        $role === "Manager" ||
                        $role === "Super Administrateur"
                    ): ?>
                        <li class="menu-title">Catalogue</li>
                        <li><a href="<?php echo BASE_URL; ?>/modules/produits/liste.php" class="<?php echo $navActive(
    "/modules/produits/liste.php",
); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18" />
                            </svg>
                            Produits
                        </a></li>
                        <li><a href="<?php echo BASE_URL; ?>/modules/produits/enregistrer.php" class="<?php echo $navActive(
    "/modules/produits/enregistrer.php",
); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v12m6-6H6" />
                            </svg>
                            Enregistrer produit
                        </a></li>
                        <li class="menu-title">Rapports</li>
                        <li><a href="<?php echo BASE_URL; ?>/rapports/rapport-journalier.php" class="<?php echo $navActive(
    "/rapports/rapport-journalier.php",
); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3v18h18" />
                            </svg>
                            Rapport journalier
                        </a></li>
                        <li><a href="<?php echo BASE_URL; ?>/rapports/rapport-mensuel.php" class="<?php echo $navActive(
    "/rapports/rapport-mensuel.php",
); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                            </svg>
                            Rapport mensuel
                        </a></li>
                    <?php endif; ?>
                    <?php if ($role === "Super Administrateur"): ?>
                        <li class="menu-title">Administration</li>
                        <li><a href="<?php echo BASE_URL; ?>/modules/admin/gestion-comptes.php" class="<?php echo $navActive(
    "/modules/admin/gestion-comptes.php",
); ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6a3 3 0 100 6 3 3 0 000-6zM4 20a8 8 0 0116 0" />
                            </svg>
                            Gestion des comptes
                        </a></li>
                    <?php endif; ?>
                </ul>
                <div class="p-4">
                    <a href="<?php echo BASE_URL; ?>/auth/logout.php" class="btn btn-outline btn-block">Déconnexion</a>
                </div>
            </aside>
        </div>
    </div>
</body>
</html>
