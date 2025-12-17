<?php
require_once '../includes/session.php';
require_once '../config/database.php';

$success = '';
$error = '';
$client_id = $_GET['id'] ?? 0;

// Get client data
$stmt = $pdo->prepare("SELECT * FROM Client WHERE id_client = ?");
$stmt->execute([$client_id]);
$client = $stmt->fetch();

if (!$client) {
    header("Location: list_clients.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $cin = trim($_POST['cin']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $adresse = trim($_POST['adresse']);
    
    try {
        $stmt = $pdo->prepare("UPDATE Client SET nom=?, prenom=?, cin=?, email=?, telephone=?, adresse=? WHERE id_client=?");
        $stmt->execute([$nom, $prenom, $cin, $email, $telephone, $adresse, $client_id]);
        $success = "Client modifié avec succès!";
        
        // Refresh client data
        $stmt = $pdo->prepare("SELECT * FROM Client WHERE id_client = ?");
        $stmt->execute([$client_id]);
        $client = $stmt->fetch();
    } catch(PDOException $e) {
        if($e->getCode() == 23000) {
            $error = "Ce CIN ou email existe déjà.";
        } else {
            $error = "Erreur: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un client - Bankly V2</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/nav.php'; ?>
    
    <div class="container">
        <h1>Modifier un client</h1>
        <p>Mettez à jour les informations du client</p>
        
        <?php if ($success): ?>
            <div class="success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <div class="form-container">
            <form method="POST">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nom *</label>
                        <input type="text" name="nom" value="<?php echo htmlspecialchars($client['nom']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Prénom *</label>
                        <input type="text" name="prenom" value="<?php echo htmlspecialchars($client['prenom']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>CIN *</label>
                        <input type="text" name="cin" value="<?php echo htmlspecialchars($client['cin']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" name="telephone" value="<?php echo htmlspecialchars($client['telephone']); ?>">
                    </div>
                    
                    <div class="form-group form-grid-full">
                        <label>Email *</label>
                        <input type="email" name="email" value="<?php echo htmlspecialchars($client['email']); ?>" required>
                    </div>
                    
                    <div class="form-group form-grid-full">
                        <label>Adresse</label>
                        <textarea name="adresse" rows="3"><?php echo htmlspecialchars($client['adresse']); ?></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit">Modifier le client</button>
                    <a href="list_clients.php" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
