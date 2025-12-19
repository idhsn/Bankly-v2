<?php
require_once '../includes/session.php';
require_once '../config/database.php';

$account_id = $_GET['id'] ?? 0;

try {
    $stmt = $pdo->prepare("DELETE FROM Compte WHERE id_compte = ?");
    $stmt->execute([$account_id]);
} catch(PDOException $e) {
    // Error - cascade delete will handle transactions
}

header("Location: list_accounts.php");
exit();
?>
