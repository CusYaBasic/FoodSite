<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        
        .login-box {
            width: 300px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.7);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }
        
        .login-box h2 {
            text-align: center;
            color: #333;
        }
        
        .login-box input[type="email"],
        .login-box input[type="password"],
        .login-box button,
        .login-box a {
            width: 100%;
            padding: 10px;
            margin: 5px 0; /* Adjusted margin */
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
            text-align: left;
            display: block;
        }

        
        .login-box button {
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            text-align: center;
        }
        
        .login-box button:hover {
            background-color: #0056b3;
        }

        #loginMessage {
            text-align: center;
            margin-top: 10px;
        }

        .register-container {
            display: flex;
            justify-content: space-between;
            white-space: nowrap; /* Prevent text from wrapping onto multiple lines */
            align-items: center;
        }

    </style>
</head>
<body>
<div class="login-box">
    <h2>Login</h2>
    <div id="loginMessage"></div>
    <form id="loginForm" action="./api/login.php" method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <a href="forgot_password.php">Forgot Password?</a> <!-- Link to the forgot password page -->
        <button type="button" onclick="login()">Login</button>
        <p class="register-container">Don't have an account? <a href="register.php">Register here!</a>.</p>
    </form>
</div>

<script>
    function login() {
        var form = document.getElementById("loginForm");
        var loginMessage = document.getElementById("loginMessage");

        var xhr = new XMLHttpRequest();
        xhr.open("POST", form.action, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    loginMessage.textContent = response.message;
                    if (response.success) {
                        window.location.href = "./dashboard.php";
                    }
                } else {
                    loginMessage.textContent = "Error: " + xhr.status;
                }
            }
        };
        xhr.send(new URLSearchParams(new FormData(form)));
    }
</script>
</body>
</html>
