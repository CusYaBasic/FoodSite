<?php
require '../ThirdParty/PHPMailer/src/PHPMailer.php';
require '../ThirdParty/PHPMailer/src/SMTP.php';
require '../ThirdParty/PHPMailer/src/Exception.php';

define('__CONFIG__', true); // Define __CONFIG__ constant
require_once '../admin/config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Establish database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Generate a reset token
        $reset_token = bin2hex(random_bytes(16));

        // Update reset_token in the database
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ? WHERE email = ?");
        $stmt->execute([$reset_token, $email]);

        // Create a new PHPMailer object
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $smtp_host;            // Your SMTP server hostname
        $mail->SMTPAuth = true;
        $mail->Username = $smtp_username;    // Your SMTP username
        $mail->Password = $smtp_password;    // Your SMTP password
        $mail->SMTPSecure = $smtp_secure;    // Enable TLS encryption, `ssl` also accepted
        $mail->Port = $smtp_port;            // TCP port to connect to
        $mail->setFrom($smtp_from_email, $smtp_from_name); // Set email "From" address and name
        
        // Set email recipients, subject, and body
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset';
        $mail->Body = 'Please click the following link to reset your password: http://' . $siteaddress . '127.0.0.1/api/reset_password.php?token=' . $reset_token;

        // Send the email
        if (!$mail->send()) {
            $response = array('success' => false, 'message' => 'Failed to send password reset email: ' . $mail->ErrorInfo);
        } else {
            $response = array('success' => true, 'message' => 'Password reset instructions sent to your email.');
        }
    } else {
        // Email not found
        $response = array('success' => false, 'message' => 'Email not found.');
    }

    // Output response in JSON format
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
