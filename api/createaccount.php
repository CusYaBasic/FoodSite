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
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password); // Use variables from config.php
    // Set PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $response = array('success' => false, 'message' => 'Database connection failed: ' . $e->getMessage());
    // Output response in JSON format
    header('Content-Type: application/json');
    echo json_encode($response);
    exit(); // Terminate script execution
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existingUser = $stmt->fetch();

    if ($existingUser) {
        // Email already registered
        $response = array('success' => false, 'message' => 'An account with this email already exists. Please use a different email.');
    } else {
        // Hash the password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Generate a verification token
        $verification_token = bin2hex(random_bytes(16));

        // Set default values for missing fields
        $created_at = date('Y-m-d H:i:s'); // Current timestamp
        $last_login = null; // Default to null
        $role = 'user'; // Default role
        $status = 'active'; // Default status
        $verification_status = 'pending'; // Default verification status

        // Prepare SQL statement to insert data into 'users' table
        $sql = "INSERT INTO users (first_name, last_name, email, phone, address, password_hash, created_at, last_login, role, status, verification_status, verification_token) 
                VALUES (:first_name, :last_name, :email, :phone, :address, :password_hash, :created_at, :last_login, :role, :status, :verification_status, :verification_token)";
        
        try {
            // Prepare and execute the SQL statement
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array(
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':email' => $email,
                ':phone' => $phone,
                ':address' => $address,
                ':password_hash' => $password_hash,
                ':created_at' => $created_at,
                ':last_login' => $last_login,
                ':role' => $role,
                ':status' => $status,
                ':verification_status' => $verification_status,
                ':verification_token' => $verification_token
            ));

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
            $mail->Subject = 'Email Verification';
            $mail->Body = 'Please click the following link to verify your email address: http://' . $siteaddress . '/api/verify.php?token=' . $verification_token;

            // Send the email
            if (!$mail->send()) {
                $response = array('success' => false, 'message' => 'Failed to send verification email: ' . $mail->ErrorInfo);
            } else {
                $response = array('success' => true, 'message' => 'Registration successful. Please check your email for verification instructions.');
            }
        } catch (PDOException $e) {
            // Display error message if the SQL query fails
            $response = array('success' => false, 'message' => 'Error: ' . $e->getMessage());
        }
    }

    // Output response in JSON format
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
