<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('../img/bg.png'); /* Replace 'path_to_your_background_image.jpg' with the path to your background image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        
        .verification-box {
            width: 300px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.7); /* Semi-transparent white background */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Drop shadow effect */
            text-align: center;
        }
        
        h2 {
            color: #333;
        }
        
        p {
            margin-top: 20px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="verification-box">
        <?php
        define('__CONFIG__', true); // Define __CONFIG__ constant
        require_once '../admin/config.php';

        // Establish database connection
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            // Set PDO error mode to exception
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }

        // Check if token is provided in the query string
        if (isset($_GET['token'])) {
            $verification_token = $_GET['token'];

            // Prepare SQL statement to update verification status in 'users' table
            $sql = "UPDATE users SET verification_status = 'verified' WHERE verification_token = :verification_token";

            try {
                // Prepare and execute the SQL statement
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(
                    ':verification_token' => $verification_token
                ));

                // Check if any rows were affected
                if ($stmt->rowCount() > 0) {
                    // Email verification successful
                    echo "<h2>Email Verified Successfully</h2>";
                    echo "<p>Your email address has been successfully verified.</p>";
                    echo "<script>setTimeout(function() { window.location.href = '../index.php'; }, 3000);</script>"; // Redirect after 3 seconds
                } else {
                    // Verification token not found
                    echo "<h2>Error</h2>";
                    echo "<p>Invalid verification token.</p>";
                }
            } catch (PDOException $e) {
                // Display error message if the SQL query fails
                echo "<h2>Error</h2>";
                echo "<p>Error: " . $e->getMessage() . "</p>";
            }
        } else {
            // No token provided in the query string
            echo "<h2>Error</h2>";
            echo "<p>Verification token not provided.</p>";
        }
        ?>
    </div>
</body>
</html>
