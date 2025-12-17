<?php
require_once '../includes/session.php';
require_once '../config/database.php';

$client_id = $_GET['id'] ?? 0;

try {
    $stmt = $pdo->prepare("DELETE FROM Client WHERE id_client = ?");
    $stmt->execute([$client_id]);
} catch(PDOException $e) {
    // Error
}

header("Location: list_clients.php");
exit();
?>
