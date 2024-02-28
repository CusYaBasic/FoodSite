<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        
        body {
            background-image: url('../img/bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .reset-box {
            width: 300px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        
        .reset-box h2 {
            text-align: center;
            color: #333;
        }
        
        .reset-box input[type="password"],
        .reset-box button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
        }
        
        .reset-box button {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        
        .reset-box button:hover {
            background-color: #0056b3;
        }

        #resetMessage {
            text-align: center;
            margin-top: 10px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="reset-box">
        <h2>Reset Password</h2>
        <div id="resetMessage"></div>
        <?php
        // Database connection parameters
        $host = 'localhost';
        $username = 'root';
        $password = ''; // Empty password
        $dbname = 'test'; // Change this to your MySQL database name

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
            $reset_token = $_GET['token'];

            // Check if the token exists in the database
            $sql = "SELECT * FROM users WHERE reset_token = :reset_token";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['reset_token' => $reset_token]);
            $user = $stmt->fetch();

            if ($user) {
                // Token exists, display password reset form
                ?>
                <form id="resetForm" action="update_password.php" method="post">
                    <input type="hidden" name="email" value="<?php echo $user['email']; ?>">
                    <input type="hidden" name="reset_token" value="<?php echo $reset_token; ?>">
                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password" required>
                    <label for="confirm_password">Confirm New Password:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                    <button type="button" onclick="resetPassword()">Reset Password</button>
                </form>
                <?php
            } else {
                // Token not found in the database
                echo "<p id='resetMessage'>Invalid reset token.</p>";
            }
        } else {
            // No token provided in the query string
            echo "<p id='resetMessage'>Reset token not provided.</p>";
        }
        ?>
    </div>

    <script>
        function resetPassword() {
            var form = document.getElementById("resetForm");
            var resetMessage = document.getElementById("resetMessage");

            var xhr = new XMLHttpRequest();
            xhr.open("POST", form.action, true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        resetMessage.textContent = xhr.responseText;
                        if (xhr.responseText === "Password reset successful") {
                            // Redirect to login page after successful password reset
                            setTimeout(function() {
                                window.location.href = "../index.php";
                            }, 3000); // Redirect after 3 seconds
                        }
                    } else {
                        resetMessage.textContent = "Error: " + xhr.status;
                    }
                }
            };
            xhr.send(new URLSearchParams(new FormData(form)));
        }
    </script>
</body>
</html>
