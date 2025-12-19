<?php
require_once '../includes/session.php';
require_once '../config/database.php';

$success = '';
$error = '';
$account_id = $_GET['id'] ?? 0;

// Get account data
$stmt = $pdo->prepare("SELECT * FROM Compte WHERE id_compte = ?");
$stmt->execute([$account_id]);
$account = $stmt->fetch();

if (!$account) {
    header("Location: list_accounts.php");
    exit();
}

// Get all clients
$stmt = $pdo->query("SELECT id_client, nom, prenom FROM Client ORDER BY nom");
$clients = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_client = trim($_POST['id_client']);
    $type_compte = trim($_POST['type_compte']);
    $statut = trim($_POST['statut']);
    
    try {
        $stmt = $pdo->prepare("UPDATE Compte SET id_client=?, type_compte=?, statut=? WHERE id_compte=?");
        $stmt->execute([$id_client, $type_compte, $statut, $account_id]);
        $success = "Compte modifié avec succès!";
        
        // Refresh
        $stmt = $pdo->prepare("SELECT * FROM Compte WHERE id_compte = ?");
        $stmt->execute([$account_id]);
        $account = $stmt->fetch();
    } catch(PDOException $e) {
        $error = "Erreur: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un compte - Bankly V2</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/nav.php'; ?>
    
    <div class="container">
        <h1>Modifier un compte</h1>
        <p>Compte N°: <?php echo htmlspecialchars($account['numero_compte']); ?></p>
        
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
                            <?php foreach ($clients as $client): ?>
                                <option value="<?php echo $client['id_client']; ?>" 
                                    <?php echo $client['id_client'] == $account['id_client'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($client['nom'] . ' ' . $client['prenom']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Type de compte *</label>
                        <select name="type_compte" required>
                            <option value="courant" <?php echo $account['type_compte'] == 'courant' ? 'selected' : ''; ?>>Courant</option>
                            <option value="epargne" <?php echo $account['type_compte'] == 'epargne' ? 'selected' : ''; ?>>Épargne</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Statut *</label>
                        <select name="statut" required>
                            <option value="actif" <?php echo $account['statut'] == 'actif' ? 'selected' : ''; ?>>Actif</option>
                            <option value="suspendu" <?php echo $account['statut'] == 'suspendu' ? 'selected' : ''; ?>>Suspendu</option>
                            <option value="ferme" <?php echo $account['statut'] == 'ferme' ? 'selected' : ''; ?>>Fermé</option>
                        </select>
                    </div>
                    
                    <div class="form-group form-grid-full">
                        <label>Solde actuel</label>
                        <input type="text" value="<?php echo number_format($account['solde'], 2); ?> MAD" disabled>
                        <small style="color: var(--text-muted); font-size: 0.75rem;">Le solde se modifie via les transactions</small>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit">Modifier le compte</button>
                    <a href="list_accounts.php" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
