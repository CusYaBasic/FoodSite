<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-image: url('./img/bg.png'); /* Replace 'path_to_your_background_image.jpg' with the path to your background image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }
        
        h2 {
            text-align: center;
            color: #333;
        }
        
        form {
            width: 300px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.7); /* Semi-transparent white background */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Drop shadow effect */
            margin: 100px auto; /* Center the form vertically and horizontally */
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            color: #333;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px 0;
            border: none;
            border-radius: 5px;
            box-sizing: border-box;
        }
        
        button {
            width: 100%;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #007bff; /* Blue button color */
            color: #fff;
            cursor: pointer;
        }
        
        button:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
        
        p {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
    </style>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBQIku83LoGtSM-rSfTEFBehKuUuqpasKQ&libraries=places"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var input = document.getElementById('address');
            var options = {
                types: ['geocode'] // Return geocoding results
            };
            var autocomplete = new google.maps.places.Autocomplete(input, options);
            
            // Add event listener to the form submission
            document.getElementById('register-form').addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission
                
                // Fetch form data
                var formData = new FormData(this);
                
                // Send form data to createaccount.php using fetch API
                fetch('api/createaccount.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    // Display response message
                    var messageContainer = document.getElementById('message');
                    if (data.success) {
                        messageContainer.style.color = 'green';
                    } else {
                        messageContainer.style.color = 'red';
                    }
                    messageContainer.textContent = data.message;
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            });
        });
    </script>
</head>
<body>
    <form id="register-form">

        <h2>Register</h2>

        <p id="message"></p>

        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required>
        
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone">
        
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" autocomplete="off" required>
        
        <button type="submit">Register</button>
        
         <p>Already have an account? <a href="index.php">Login here</a>.</p>
    </form>
    
</body>
</html>
