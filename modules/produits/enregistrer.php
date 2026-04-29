<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../auth/session.php';
require_once __DIR__ . '/../../includes/fonctions-produits.php';

if ($_SESSION['utilisateur']['role'] !== 'Manager' && $_SESSION['utilisateur']['role'] !== 'Super Administrateur') {
    die("Accès refusé.");
}

// Logique AJAX pour vérifier le produit
if (isset($_GET['action']) && $_GET['action'] === 'verifier_produit') {
    $code = $_GET['code'] ?? '';
    $produit = chercherProduit($code);
    echo json_encode(['existe' => $produit !== null, 'donnees' => $produit]);
    exit;
}

$prefilled_code = $_GET['code'] ?? '';

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['code_barre']) && !empty($_POST['nom']) && is_numeric($_POST['prix_unitaire_ht'])) {
        $produit = [
            'code_barre' => $_POST['code_barre'],
            'nom' => $_POST['nom'],
            'prix_unitaire_ht' => (float)$_POST['prix_unitaire_ht'],
            'date_expiration' => $_POST['date_expiration'], // Format MM-JJ-AAAA demandé
            'stock' => (int)$_POST['stock'],
            'date_enregistrement' => date('Y-m-d')
        ];
        ajouterProduit($produit);
        $message = "Produit enregistré avec succès !";
    }
}
?>

<?php include __DIR__ . '/../../includes/header.php'; ?>

<div class="card bg-base-100 shadow">
    <div class="card-body">
        <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <h2 class="card-title">Scanner un produit</h2>
            <a href="liste.php" class="btn btn-ghost">Voir le catalogue</a>
        </div>

        <div class="relative mb-6">
            <div id="scanner" class="rounded-box overflow-hidden border border-base-300"></div>
        </div>

        <div id="resultat-scan"></div>

        <?php if ($message): ?>
            <div class="alert alert-success mb-4">
                <span><?php echo $message; ?></span>
            </div>
        <?php endif; ?>

        <div id="form-enregistrement" class="pt-6 border-t border-base-300" style="<?php echo $prefilled_code ? 'display: block;' : 'display: none;'; ?>">
            <h3 class="text-lg font-semibold mb-4">Details du produit</h3>
            <form method="POST" class="grid gap-4">
                <label class="form-control">
                    <span class="label-text">Code-barres</span>
                    <input type="text" name="code_barre" id="input-code" value="<?php echo htmlspecialchars($prefilled_code); ?>" readonly class="input input-bordered" />
                </label>
                <label class="form-control">
                    <span class="label-text">Nom du produit</span>
                    <input type="text" name="nom" required placeholder="Ex: Savon Le Coq" class="input input-bordered" />
                </label>
                <label class="form-control">
                    <span class="label-text">Prix unitaire HT (CDF)</span>
                    <input type="number" name="prix_unitaire_ht" step="0.01" required placeholder="0.00" class="input input-bordered" />
                </label>
                <label class="form-control">
                    <span class="label-text">Date d'expiration (MM-JJ-AAAA)</span>
                    <input type="text" name="date_expiration" placeholder="MM-JJ-AAAA" pattern="\d{2}-\d{2}-\d{4}" class="input input-bordered" />
                </label>
                <label class="form-control">
                    <span class="label-text">Quantite initiale en stock</span>
                    <input type="number" name="stock" required value="1" class="input input-bordered" />
                </label>
                <button type="submit" class="btn btn-primary">Enregistrer le produit</button>
            </form>
        </div>

        <div id="info-produit" class="hidden rounded-box border border-base-300 bg-base-200 p-6">
            <h3 class="text-lg font-semibold text-primary mb-3">Produit deja reference</h3>
            <div id="details-produit" class="space-y-1"></div>
            <button onclick="location.reload()" class="btn btn-secondary mt-4">Scanner un autre produit</button>
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
                facingMode: "user"
            },
            area: {
                top: "10%", right: "10%", left: "10%", bottom: "10%"
            }
        },
        locator: {
            patchSize: "medium",
            halfSample: false
        },
        decoder: {
            readers: ["ean_reader", "ean_8_reader", "code_128_reader", "upc_reader"]
        },
        locate: true,
        frequency: 20
    }, function(err) {
        if (!err) Quagga.start();
    });
}

// Ne pas démarrer le scanner si on a déjà un code pré-rempli pour éviter la confusion
if (!document.getElementById('input-code').value) {
    startScanner();
}

Quagga.onDetected(function(data) {
    let code = data.codeResult.code;
    Quagga.stop();
    
    // Bip sonore simulé ou feedback visuel
    document.getElementById('scanner').style.borderColor = "var(--primary)";
    
    fetch('?action=verifier_produit&code=' + code)
        .then(r => r.json())
        .then(res => {
            if (res.existe) {
                // Produit connu
                document.getElementById('form-enregistrement').style.display = 'none';
                document.getElementById('info-produit').classList.remove('hidden');
                document.getElementById('details-produit').innerHTML = `
                    <div class="flex items-center justify-between">
                        <span class="font-semibold">Nom</span>
                        <span>${res.donnees.nom}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="font-semibold">Code</span>
                        <span>${res.donnees.code_barre}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="font-semibold">Prix</span>
                        <span>${res.donnees.prix_unitaire_ht} CDF</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="font-semibold">Stock</span>
                        <span>${res.donnees.stock || 'N/A'}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="font-semibold">Expiration</span>
                        <span>${res.donnees.date_expiration || 'Non definie'}</span>
                    </div>
                `;
            } else {
                // Produit inconnu
                document.getElementById('info-produit').classList.add('hidden');
                document.getElementById('form-enregistrement').style.display = 'block';
                document.getElementById('input-code').value = code;
                // Scroll vers le formulaire
                document.getElementById('form-enregistrement').scrollIntoView({ behavior: 'smooth' });
            }
        });
});
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
