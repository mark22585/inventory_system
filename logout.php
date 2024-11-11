<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();  // Start the session
session_destroy();  // Destroy the session to log out
header('Location: index.php');  // Redirect to login page
exit;
?>
