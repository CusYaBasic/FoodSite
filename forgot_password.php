<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        
        body {
            background-image: url('./img/bg.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .forgot-password-box {
            width: 300px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        
        .forgot-password-box h2 {
            text-align: center;
            color: #333;
        }
        
        .forgot-password-box input[type="email"],
        .forgot-password-box button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
        }
        
        .forgot-password-box button {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }
        
        .forgot-password-box button:hover {
            background-color: #0056b3;
        }

        #forgotPasswordMessage {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="forgot-password-box">
        <h2>Forgot Password</h2>
        <div id="forgotPasswordMessage"></div>
        <form id="forgotPasswordForm">
            <label for="email">Enter your email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            <button type="button" onclick="sendResetLink()">Send Reset Link</button>
        </form>
        <p>Remembered your password? <a href="index.php">Login here</a>.</p>
    </div>

    <script>
        function sendResetLink() {
            var form = document.getElementById("forgotPasswordForm");
            var message = document.getElementById("forgotPasswordMessage");

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "./api/send_reset_link.php", true); // Update the URL according to your server endpoint
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        message.textContent = xhr.responseText;
                    } else {
                        message.textContent = "Error: " + xhr.status;
                    }
                }
            };
            xhr.send(new URLSearchParams(new FormData(form)));
        }
    </script>
</body>
</html>
