<?php
// Start session
session_start();

define('__CONFIG__', true); // Define __CONFIG__ constant
require_once '../admin/config.php';

// Include Firebase JWT library
require '../vendor/autoload.php';
use \Firebase\JWT\JWT;

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
    $password = $_POST['password'];

    // Prepare SQL statement to fetch user data from 'users' table
    $sql = "SELECT id, email, password_hash, verification_status, first_name FROM users WHERE email = :email";

    try {
        // Prepare and execute the SQL statement
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(':email' => $email));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify if the user exists and the password is correct
        if ($user && password_verify($password, $user['password_hash'])) {
            // Check if the email is verified
            if ($user['verification_status'] == 'verified') {
                // Update last login timestamp
                $updateSql = "UPDATE users SET last_login = :last_login WHERE id = :user_id";
                $updateStmt = $pdo->prepare($updateSql);
                $updateStmt->execute(array(':last_login' => date('Y-m-d H:i:s'), ':user_id' => $user['id']));

                // User is authenticated, generate JWT
                $userData = array(
                    'id' => $user['id'],
                    'email' => $user['email'],
                    'name' => $user['first_name'] // Include user's first name in the session data
                    // Add more user data as needed
                );
                $jwt = generateJWT($userData);

                // Set user data in session
                $_SESSION['user'] = $userData;

                // Set JWT in session for subsequent requests
                $_SESSION['jwt'] = $jwt;

                // Send success response
                $response = array('success' => true, 'message' => 'Login successful');
                echo json_encode($response);
                exit; // Stop further execution
            } else {
                // Email not verified, send error message
                $response = array('success' => false, 'message' => 'Please verify your email address.');
                echo json_encode($response);
                exit;
            }
        } else {
            // Invalid credentials, send error message
            $response = array('success' => false, 'message' => 'Invalid email or password.');
            echo json_encode($response);
            exit;
        }
    } catch (PDOException $e) {
        // Display error message if the SQL query fails
        $response = array('success' => false, 'message' => 'Error: ' . $e->getMessage());
        echo json_encode($response);
        exit;
    }
}

// Function to generate JWT
function generateJWT($userData) {
    // JWT secret key (change this to a random secret for security)
    $secretKey = "your_secret_key";

    // JWT expiration time (e.g., 1 hour)
    $expirationTime = time() + (60 * 60);

    // JWT payload
    $payload = array(
        'exp' => $expirationTime,
        'data' => $userData
    );

    // Generate JWT
    $jwt = JWT::encode($payload, $secretKey);

    return $jwt;
}
?>
