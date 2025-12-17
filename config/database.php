<?php
// Database credentials - like keys to enter your database
$host = 'localhost';        // Where is your database? (usually localhost)
$dbname = 'bankly_v2';      // Name of your database
$username = 'root';         // Your MySQL username
$password = '';             // Your MySQL password (empty by default in XAMPP/WAMP)

// Try to connect to the database
try {
    // PDO = PHP Data Objects - it's a way to talk to your database safely
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // These 2 lines make PDO show errors clearly (helps you learn!)
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch(PDOException $e) {
    // If connection fails, show the error
    die("Connection failed: " . $e->getMessage());
}
?>
