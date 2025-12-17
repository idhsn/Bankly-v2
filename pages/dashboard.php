<?php
require_once '../includes/session.php';
require_once '../config/database.php';

// Get statistics
$stmt = $pdo->query("SELECT COUNT(*) as total FROM Client");
$total_clients = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM Compte");
$total_comptes = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT COUNT(*) as total FROM Transaction WHERE DATE(date_transaction) = CURDATE()");
$total_transactions_today = $stmt->fetch()['total'];

$stmt = $pdo->query("SELECT SUM(solde) as total FROM Compte WHERE statut = 'actif'");
$total_solde = $stmt->fetch()['total'] ?? 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Bankly V2</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/nav.php'; ?>
    
    <div class="container">
        <h1>Tableau de bord</h1>
        <p>Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
        
        <div class="stats-grid">
            <div class="stat-card">
                <h3><?php echo $total_clients; ?></h3>
                <p>Clients</p>
            </div>
            
            <div class="stat-card">
                <h3><?php echo $total_comptes; ?></h3>
                <p>Comptes bancaires</p>
            </div>
            
            <div class="stat-card">
                <h3><?php echo $total_transactions_today; ?></h3>
                <p>Transactions aujourd'hui</p>
            </div>
            
            <div class="stat-card">
                <h3><?php echo number_format($total_solde, 2); ?> MAD</h3>
                <p>Solde total</p>
            </div>
        </div>
    </div>
</body>
</html>
