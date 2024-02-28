<?php
require_once '../admin/config.php';

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
    $token = $_POST['reset_token'];
    $password = $_POST['password'];

    // Hash the password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Update password in the database
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ?, reset_token = NULL WHERE reset_token = ?");
    $stmt->execute([$password_hash, $token]);

    // Check if password was updated successfully
    if ($stmt->rowCount() > 0) {
        // Password updated successfully
        $response = array('success' => true, 'message' => 'Password updated successfully.');
        // Redirect to login page after successful password reset
        setTimeout(function() {
           window.location.href = "../index.php";
        }, 3000); // Redirect after 3 seconds
    } else {
        // Invalid or expired token
        $response = array('success' => false, 'message' => 'Invalid or expired token.');
    }

    // Output response in JSON format
    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
