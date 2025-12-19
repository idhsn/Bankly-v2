<?php
require_once '../includes/session.php';
require_once '../config/database.php';

$success = '';
$error = '';

// Get all active accounts
$stmt = $pdo->query("
    SELECT c.id_compte, c.numero_compte, c.solde, cl.nom, cl.prenom 
    FROM Compte c 
    JOIN Client cl ON c.id_client = cl.id_client 
    WHERE c.statut = 'actif'
    ORDER BY c.numero_compte
");
$accounts = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_compte = trim($_POST['id_compte']);
    $type_transaction = trim($_POST['type_transaction']);
    $montant = floatval(trim($_POST['montant']));
    
    try {
        // Start transaction
        $pdo->beginTransaction();
        
        // Get current balance
        $stmt = $pdo->prepare("SELECT solde FROM Compte WHERE id_compte = ?");
        $stmt->execute([$id_compte]);
        $solde_avant = $stmt->fetch()['solde'];
        
        // Calculate new balance
        if ($type_transaction == 'depot') {
            $solde_apres = $solde_avant + $montant;
        } else { // retrait
            if ($solde_avant < $montant) {
                throw new Exception("Solde insuffisant!");
            }
            $solde_apres = $solde_avant - $montant;
        }
        
        // Update account balance
        $stmt = $pdo->prepare("UPDATE Compte SET solde = ? WHERE id_compte = ?");
        $stmt->execute([$solde_apres, $id_compte]);
        
        // Record transaction
        $stmt = $pdo->prepare("INSERT INTO Transaction (id_compte, id_utilisateur, type_transaction, montant, solde_avant, solde_apres) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$id_compte, $_SESSION['user_id'], $type_transaction, $montant, $solde_avant, $solde_apres]);
        
        // Commit
        $pdo->commit();
        
        $success = "Transaction effectuée avec succès! Nouveau solde: " . number_format($solde_apres, 2) . " MAD";
        $_POST = array();
        
    } catch(Exception $e) {
        $pdo->rollBack();
        $error = "Erreur: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouvelle transaction - Bankly V2</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/nav.php'; ?>
    
    <div class="container">
        <h1>Effectuer une transaction</h1>
        <p>Dépôt ou retrait sur un compte</p>
        
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
                        <label>Compte *</label>
                        <select name="id_compte" required>
                            <option value="">Sélectionner un compte</option>
                            <?php foreach ($accounts as $account): ?>
                                <option value="<?php echo $account['id_compte']; ?>">
                                    <?php echo $account['numero_compte'] . ' - ' . htmlspecialchars($account['nom'] . ' ' . $account['prenom']) . ' (Solde: ' . number_format($account['solde'], 2) . ' MAD)'; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Type de transaction *</label>
                        <select name="type_transaction" required>
                            <option value="depot">Dépôt</option>
                            <option value="retrait">Retrait</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Montant (MAD) *</label>
                        <input type="number" name="montant" step="0.01" min="0.01" required>
                    </div>
                    
                    <div class="form-group form-grid-full">
                        <label>Description</label>
                        <textarea name="description" rows="3" placeholder="Optionnel..."></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit">Effectuer la transaction</button>
                    <a href="list_transactions.php" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
