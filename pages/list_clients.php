<?php
require_once '../includes/session.php';
require_once '../config/database.php';

// Get all clients
$stmt = $pdo->query("SELECT * FROM Client ORDER BY date_creation DESC");
$clients = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des clients - Bankly V2</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/nav.php'; ?>
    
    <div class="container">
        <h1>Liste des clients</h1>
        <p>Gérez tous vos clients</p>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>CIN</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($clients) > 0): ?>
                    <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?php echo $client['id_client']; ?></td>
                        <td><?php echo htmlspecialchars($client['nom']); ?></td>
                        <td><?php echo htmlspecialchars($client['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($client['cin']); ?></td>
                        <td><?php echo htmlspecialchars($client['email']); ?></td>
                        <td><?php echo htmlspecialchars($client['telephone']); ?></td>
                        <td>
                            <a href="edit_client.php?id=<?php echo $client['id_client']; ?>">Modifier</a>
                            <a href="delete_client.php?id=<?php echo $client['id_client']; ?>" onclick="return confirm('Supprimer ce client?')">Supprimer</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                            Aucun client trouvé. Cliquez sur + pour ajouter un client.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- Floating Add Button -->
    <a href="add_client.php" class="floating-add-btn" title="Ajouter un client">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round">
            <line x1="12" y1="5" x2="12" y2="19"></line>
            <line x1="5" y1="12" x2="19" y2="12"></line>
        </svg>
    </a>
</body>
</html>
