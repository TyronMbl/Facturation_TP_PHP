<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../includes/fonctions-factures.php';
require_once __DIR__ . '/../../includes/fonctions-produits.php';

// Initialisation panier si inexistant
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [];
}

// Logique AJAX pour chercher
if (isset($_GET['action']) && $_GET['action'] === 'chercher_produit') {
    $code = $_GET['code'] ?? '';
    echo json_encode(chercherProduit($code));
    exit;
}

// Logique ajout article
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter'])) {
    $produit = chercherProduit($_POST['code_barre']);
    if ($produit && $_POST['quantite'] > 0) {
        $_SESSION['panier'][] = [
            'code_barre' => $produit['code_barre'],
            'nom' => $produit['nom'],
            'prix_unitaire_ht' => $produit['prix_unitaire_ht'],
            'quantite' => (int)$_POST['quantite'],
            'sous_total_ht' => $produit['prix_unitaire_ht'] * (int)$_POST['quantite']
        ];
    }
}

// Logique validation facture
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['valider'])) {
    $total_ht = 0;
    foreach ($_SESSION['panier'] as $item) $total_ht += $item['sous_total_ht'];
    $tva = $total_ht * TVA_RATE;
    
    $facture = [
        'id_facture' => genererIdFacture(),
        'date' => date('Y-m-d'),
        'heure' => date('H:i:s'),
        'caissier' => $_SESSION['utilisateur']['identifiant'],
        'articles' => $_SESSION['panier'],
        'total_ht' => $total_ht,
        'tva' => $tva,
        'total_ttc' => $total_ht + $tva
    ];
    sauvegarderFacture($facture);
    $_SESSION['panier'] = []; // Vider le panier
    $_SESSION['message_succes'] = "Facture validée avec succès !";
    header('Location: nouvelle-facture.php');
    exit;
}
?>
<?php include __DIR__ . '/../../includes/header.php'; ?>
<div class="space-y-6">
    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <h2 class="card-title">Nouvelle facture</h2>
                <div class="badge badge-outline">Scan actif</div>
            </div>
            <?php if (isset($_SESSION['message_succes'])): ?>
                <div class="alert alert-success">
                    <span><?php echo $_SESSION['message_succes']; unset($_SESSION['message_succes']); ?></span>
                </div>
            <?php endif; ?>
            <div class="relative">
                <div id="scanner" class="rounded-box overflow-hidden border border-base-300"></div>
                <div class="badge badge-neutral absolute bottom-3 left-1/2" style="transform: translateX(-50%);">Alignez le code-barres sur la ligne rouge</div>
            </div>
            <div id="resultat"></div>
            <div class="alert alert-warning">
                <span>Conseil : pour un meilleur scan, approchez doucement le produit et assurez un bon eclairage.</span>
            </div>
        </div>
    </div>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h3 class="card-title">Ajouter un article</h3>
            <form method="POST" id="form-ajout" class="grid gap-4 lg:grid-cols-[1fr_1fr_auto] items-end">
                <label class="form-control">
                    <span class="label-text">Produit</span>
                    <input type="text" name="code_barre" id="code_barre" placeholder="Scanner ou saisir le code-barres" required class="input input-bordered" />
                </label>
                <label class="form-control">
                    <span class="label-text">Quantite</span>
                    <input type="number" name="quantite" id="quantite" placeholder="Quantite" value="1" min="1" required class="input input-bordered" />
                </label>
                <button type="submit" name="ajouter" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
    </div>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <div class="flex items-center justify-between">
                <h3 class="card-title">Panier actuel</h3>
                <span class="badge badge-outline"><?php echo count($_SESSION['panier']); ?> article(s)</span>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-zebra">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>PU</th>
                            <th>Qte</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($_SESSION['panier'])): ?>
                            <tr>
                                <td colspan="4" class="text-center opacity-60">Le panier est vide</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($_SESSION['panier'] as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['nom']); ?></td>
                                    <td><?php echo number_format($item['prix_unitaire_ht'], 0, ',', ' '); ?> CDF</td>
                                    <td><?php echo $item['quantite']; ?></td>
                                    <td class="font-semibold"><?php echo number_format($item['sous_total_ht'], 0, ',', ' '); ?> CDF</td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!empty($_SESSION['panier'])): ?>
                <form method="POST" class="flex justify-end mt-4">
                    <button type="submit" name="valider" class="btn btn-primary">Valider la facture</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
<script>
function startScanner() {
    // Correctif pour les anciens navigateurs
    if (navigator.mediaDevices === undefined) {
        navigator.mediaDevices = {};
    }
    if (navigator.mediaDevices.getUserMedia === undefined) {
        navigator.mediaDevices.getUserMedia = function(constraints) {
            var getUserMedia = navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
            if (!getUserMedia) {
                return Promise.reject(new Error('getUserMedia is not implemented in this browser'));
            }
            return new Promise(function(resolve, reject) {
                getUserMedia.call(navigator, constraints, resolve, reject);
            });
        }
    }

    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#scanner'),
            constraints: {
                width: { min: 640, ideal: 1280 },
                height: { min: 480, ideal: 720 },
                facingMode: "user" // Utilise la webcam du PC
            },
            area: {
                top: "10%",    
                right: "10%",  
                left: "10%",   
                bottom: "10%"  
            }
        },
        locator: {
            patchSize: "medium",
            halfSample: false, // Plus précis (désactivé pour mieux lire les petits détails)
            workers: 4
        },
        decoder: {
            readers: [
                "ean_reader",
                "ean_8_reader",
                "code_128_reader",
                "upc_reader"
            ],
            multiple: false
        },
        locate: true,
        frequency: 20 // Scan plus souvent (20 fois par seconde au lieu de 10)
    }, function(err) {
        if (err) {
            console.error(err);
            document.getElementById('scanner').innerHTML = `<div class="p-6 text-error text-center">Erreur camera : ${err.message}</div>`;
            return;
        }
        Quagga.start();
    });
}

startScanner();

// Filtre pour éviter les faux positifs (vérifie la validité du code scanné)
Quagga.onDetected(function(data) {
    if (!data.codeResult || !data.codeResult.code) return;
    
    var code = data.codeResult.code;
    
    // Validation simple pour EAN-13 (souvent 13 chiffres)
    if (code.length < 8) return; 

    Quagga.stop();
    
    // Feedback visuel immédiat (vibration si supporté)
    if (navigator.vibrate) navigator.vibrate(100);

    fetch('?action=chercher_produit&code=' + code)
        .then(r => r.json())
        .then(p => {
            if (p) {
                document.getElementById('resultat').innerHTML = `
                    <div class="alert alert-success mb-4">
                        <div>
                            <div class="text-xs opacity-70">Produit identifie</div>
                            <div class="text-lg font-semibold">${p.nom}</div>
                            <div>${p.prix_unitaire_ht} CDF</div>
                        </div>
                    </div>
                `;
                document.getElementById('code_barre').value = p.code_barre;
                // Auto-focus sur la quantité pour aller vite
                document.getElementById('quantite').focus();
            } else {
                document.getElementById('resultat').innerHTML = `
                    <div class="alert alert-warning mb-4">
                        <div>
                            <div class="text-xs opacity-70">Code scanne : <strong>${code}</strong></div>
                            <div class="text-lg font-semibold">Produit inconnu</div>
                            <div class="mt-2">
                                <a href="../produits/enregistrer.php?code=${code}" class="btn btn-sm btn-outline">Enregistrer ce produit</a>
                            </div>
                        </div>
                    </div>
                `;
                document.getElementById('code_barre').value = code;
            }
            setTimeout(startScanner, 3000); 
        });
});
</script>


<?php include __DIR__ . '/../../includes/footer.php'; ?>

