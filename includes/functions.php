<?php

/**
 * Sanitize user input
 */
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Generate unique account number
 */
function generate_account_number() {
    // Format: BNK + Year + Random 6 digits
    // Example: BNK2025123456
    return 'BNK' . date('Y') . rand(100000, 999999);
}

/**
 * Format currency
 */
function format_currency($amount) {
    return number_format($amount, 2, '.', ' ') . ' MAD';
}

/**
 * Check if account exists
 */
function account_exists($pdo, $numero_compte) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Compte WHERE numero_compte = ?");
    $stmt->execute([$numero_compte]);
    return $stmt->fetchColumn() > 0;
}

?>
