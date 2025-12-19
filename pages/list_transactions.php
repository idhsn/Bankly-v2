<?php
require_once '../includes/session.php';
require_once '../config/database.php';

// Get all transactions
$stmt = $pdo->query("
    SELECT t.*, c.numero_compte, cl.nom, cl.prenom, u.nom_utilisateur
    FROM Transaction t
    JOIN Compte c ON t.id_compte = c.id_compte
    JOIN Client cl ON c.id_client = cl.id_client
    JOIN Utilisateur u ON t.id_utilisateur = u.id_utilisateur
    ORDER BY t.date_transaction DESC
    LIMIT 100
");
$transactions = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique des transactions - Bankly V2</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/nav.php'; ?>
    
    <div class="container">
        <h1>Historique des transactions</h1>
        <p>Toutes les opérations bancaires</p>
        
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>N° Compte</th>
                    <th>Client</th>
                    <th>Type</th>
                    <th>Montant</th>
                    <th>Solde avant</th>
                    <th>Solde après</th>
                    <th>Agent</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($transactions) > 0): ?>
                    <?php foreach ($transactions as $trans): ?>
                    <tr>
                        <td><?php echo date('d/m/Y H:i', strtotime($trans['date_transaction'])); ?></td>
                        <td><?php echo htmlspecialchars($trans['numero_compte']); ?></td>
                        <td><?php echo htmlspecialchars($trans['nom'] . ' ' . $trans['prenom']); ?></td>
                        <td>
                            <span style="color: <?php echo $trans['type_transaction'] == 'depot' ? 'var(--success)' : 'var(--danger)'; ?>">
                                <?php echo $trans['type_transaction'] == 'depot' ? '↗ Dépôt' : '↘ Retrait'; ?>
                            </span>
                        </td>
                        <td><?php echo number_format($trans['montant'], 2); ?> MAD</td>
                        <td><?php echo number_format($trans['solde_avant'], 2); ?> MAD</td>
                        <td><?php echo number_format($trans['solde_apres'], 2); ?> MAD</td>
                        <td><?php echo htmlspecialchars($trans['nom_utilisateur']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                            Aucune transaction trouvée.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Floating Add Button -->
    <a href="make_transaction.php" class="floating-add-btn" title="Nouvelle transaction">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
    </a>
</body>
</html>
