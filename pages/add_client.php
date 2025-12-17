<?php
require_once '../includes/session.php';
require_once '../config/database.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $cin = trim($_POST['cin']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $adresse = trim($_POST['adresse']);
    
    try {
        $stmt = $pdo->prepare("INSERT INTO Client (nom, prenom, cin, email, telephone, adresse) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $cin, $email, $telephone, $adresse]);
        $success = "Client ajouté avec succès!";
        
        // Clear form
        $_POST = array();
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
    <title>Ajouter un client - Bankly V2</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <?php include '../includes/nav.php'; ?>
    
    <div class="container">
        <h1>Ajouter un client</h1>
        <p>Remplissez les informations du nouveau client</p>
        
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
                        <input type="text" name="nom" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Prénom *</label>
                        <input type="text" name="prenom" required>
                    </div>
                    
                    <div class="form-group">
                        <label>CIN *</label>
                        <input type="text" name="cin" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input type="text" name="telephone">
                    </div>
                    
                    <div class="form-group form-grid-full">
                        <label>Email *</label>
                        <input type="email" name="email" required>
                    </div>
                    
                    <div class="form-group form-grid-full">
                        <label>Adresse</label>
                        <textarea name="adresse" rows="3"></textarea>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit">Ajouter le client</button>
                    <a href="list_clients.php" class="btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
