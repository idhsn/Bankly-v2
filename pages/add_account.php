<?php
require_once '../includes/session.php';
require_once '../config/database.php';
require_once '../includes/functions.php';

$success = '';
$error = '';

// Get all clients for dropdown
$stmt = $pdo->query("SELECT id_client, nom, prenom FROM Client ORDER BY nom");
$clients = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numero_compte = generate_account_number();
    $id_client = trim($_POST['id_client']);
    $type_compte = trim($_POST['type_compte']);
    $solde = trim($_POST['solde']);
    $statut = 'actif';
    
    try {
        $stmt = $pdo->prepare("INSERT INTO Compte (numero_compte, id_client, type_compte, solde, statut) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$numero_compte, $id_client, $type_compte, $solde, $statut]);
        $success = "Compte créé avec succès! N°: " . $numero_compte;
        $_POST = array();
    } catch(PDOException $e) {
        $error = "Erreur: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un compte - Bankly V2</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/nav.php'; ?>
    
    <div class="container">
        <h1>Créer un compte bancaire</h1>
        <p>Ouvrir un nouveau compte pour un client</p>
        
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST">
                <div class="form-grid">
                    <div class="form-group form-grid-full">
                        <label>Client *</label>
                        <select name="id_client" required>
                            <option value="">Sélectionner un client</option>
                            <?php foreach ($clients as $client): ?>
                                <option value="<?php echo $client['id_client']; ?>">
                                    <?php echo htmlspecialchars($client['nom'] . ' ' . $client['prenom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Type de compte *</label>
                        <select name="type_compte" required>
                            <option value="courant">Courant</option>
                            <option value="epargne">Épargne</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Solde initial *</label>
                        <input type="number" name="solde" step="0.01" min="0" value="0" required>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit">Créer le compte</button>
                    <a href="list_accounts.php" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
