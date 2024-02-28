<?php
define('__CONFIG__', true); // Define __CONFIG__ constant
// Include your config.php file to access database details
require_once 'admin/config.php';


// Check if article ID is provided in the URL
if (isset($_GET['id'])) {
    $articleId = $_GET['id'];

    // Connect to the database
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Fetch the article details from the database
        $stmt = $pdo->prepare("SELECT * FROM news WHERE id = :id");
        $stmt->execute(['id' => $articleId]);
        $article = $stmt->fetch(PDO::FETCH_ASSOC);

        // Display the article details
        if ($article) {
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Article Details</title>
                <style>
                    /* CSS styles go here */
                    .image-container {
                        text-align: center; /* Center the image horizontally */
                    }
                    .centered-image {
                        width: calc(100%); /* Calculate the width with 20px padding on each side */
                        height: auto; /* Let the height adjust to maintain aspect ratio */
                        max-width: 100%; /* Ensure the image doesn't exceed its container */
                        border-radius: 10px; /* Rounded corners for the image */
                        max-height: 500px; /* Set maximum height to maintain aspect ratio */
                        object-fit: cover; /* Ensure the image covers the container without stretching */
                    }
                    .rounded-border {
                        border-radius: 10px; /* Adjust the radius value as needed */
                        border: 1px solid #ccc; /* Example border styling */
                        padding: 20px; /* Example padding */
                        background-color: #ffffff;
                    }
                    p {
                        font-size: 20px;
                    }
                    body {
                        padding-top: 20px;
                    }
                </style>
            </head>
            <body>
                <div id="article" class="tab-content rounded-border">
                    <!-- Image -->
                    <div class="image-container">
                        <img src="img/bg.png" alt="Image Description" class="centered-image">
                    </div>

                    <h1><?php echo htmlspecialchars($article['title']); ?></h1>
                    <p><?php echo htmlspecialchars($article['message']); ?></p>
                    <!-- Display other details like image, date, etc. -->
                </div>
            </body>
            </html>
            <?php
        } else {
            echo "Article not found.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Article ID not provided.";
}
?>
