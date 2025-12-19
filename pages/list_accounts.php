<?php
require_once '../includes/session.php';
require_once '../config/database.php';

// Get all accounts with client info
$stmt = $pdo->query("
    SELECT c.*, cl.nom, cl.prenom 
    FROM Compte c 
    JOIN Client cl ON c.id_client = cl.id_client 
    ORDER BY c.date_creation DESC
");
$accounts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des comptes - Bankly V2</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/nav.php'; ?>
    
    <div class="container">
        <h1>Liste des comptes</h1>
        <p>Gérez tous les comptes bancaires</p>
        
        <table>
            <thead>
                <tr>
                    <th>N° Compte</th>
                    <th>Client</th>
                    <th>Type</th>
                    <th>Solde</th>
                    <th>Statut</th>
                    <th>Date création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($accounts) > 0): ?>
                    <?php foreach ($accounts as $account): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($account['numero_compte']); ?></td>
                        <td><?php echo htmlspecialchars($account['nom'] . ' ' . $account['prenom']); ?></td>
                        <td><?php echo ucfirst($account['type_compte']); ?></td>
                        <td><?php echo number_format($account['solde'], 2); ?> MAD</td>
                        <td>
                            <span style="color: <?php echo $account['statut'] == 'actif' ? 'var(--success)' : 'var(--warning)'; ?>">
                                <?php echo ucfirst($account['statut']); ?>
                            </span>
                        </td>
                        <td><?php echo date('d/m/Y', strtotime($account['date_creation'])); ?></td>
                        <td>
                            <a href="edit_account.php?id=<?php echo $account['id_compte']; ?>">Modifier</a>
                            <a href="delete_account.php?id=<?php echo $account['id_compte']; ?>" onclick="return confirm('Supprimer ce compte?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                            Aucun compte trouvé. Cliquez sur + pour créer un compte.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Floating Add Button -->
    <a href="add_account.php" class="floating-add-btn" title="Ajouter un compte">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
    </a>
</body>
</html>
