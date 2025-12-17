<?php
session_start();
require_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['nom_utilisateur']);
    $password = trim($_POST['mot_de_passe']);
    
    $stmt = $pdo->prepare("SELECT * FROM Utilisateur WHERE nom_utilisateur = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION['user_id'] = $user['id_utilisateur'];
        $_SESSION['username'] = $user['nom_utilisateur'];
        $_SESSION['email'] = $user['email'];
        
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Nom d'utilisateur ou mot de passe incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bankly V2 - Connexion</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="login-container">
        <h1>Bankly V2</h1>
        <h2>Connexion</h2>
        
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <label>Nom d'utilisateur</label>
            <input type="text" name="nom_utilisateur" required>
            
            <label>Mot de passe</label>
            <input type="password" name="mot_de_passe" required>
            
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
