<?php
// Check if the constant __CONFIG__ is defined
if (!defined('__CONFIG__')) {
    exit('Direct access not allowed.');
}

// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = ''; // Empty password
$dbname = 'test'; // Change this to your MySQL database name

$siteaddress = "127.0.0.1";
$sitename = "Food Site";

// SMTP configuration settings
$smtp_host = 'sandbox.smtp.mailtrap.io';
$smtp_username = '16f57fad185447';
$smtp_password = '30e0be159652ac';
$smtp_secure = 'tls';
$smtp_port = 587;
$smtp_from_email = 'from@example.com';
$smtp_from_name = 'Test Web';
?>
