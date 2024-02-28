<?php
// Start session
session_start();

// Unset JWT token and user data
unset($_SESSION['jwt']);
unset($_SESSION['user']);

// Redirect user to the login page
header("Location: dashboard.php");
exit;
?>
