<?php
session_start();

// If user is not logged in, send them to login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
