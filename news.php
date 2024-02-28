<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News</title>
    <style>
        .news-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); /* Each column's width is at least 200px and takes up available space */
            grid-gap: 10px; /* Spacing between grid items */
            margin-top: 20px;
        }
        .news-box {
            display: flex; /* Add display flex to enable flexbox */
            flex-direction: column; /* Align items vertically */
            width: 300px;
            height: auto;
            border: none;
            margin: 10px;
            padding: 20px;
            overflow: hidden;
            box-sizing: border-box;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            position: relative;
        }

        .image-container {
            text-align: center; /* Center the image horizontally */
            margin-bottom: 10px; /* Add spacing below the image */
        }

        .centered-image {
            display: inline-block; /* Ensure image is treated as a block element */
            max-width: 100%; /* Ensure image doesn't exceed the width of its container */
            height: auto; /* Maintain aspect ratio */
            border-radius: 10px; /* Adjust the value as needed to round the corners */
        }


        .read-more {
            margin-top: auto; /* Align the button to the bottom */
            align-self: flex-end; /* Align the button to the right */
        }
        .news-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .news-message {
            font-size: 14px;
            line-height: 1.4;
            height: 80px; /* Limiting message height */
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</head>
<body>
    <div class="news-container">
        <?php
        // define('__CONFIG__', true); // Define __CONFIG__ constant
        // Include your config.php file to access database details
        // require_once 'admin/config.php';

        // Connect to the database
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Fetch news articles from the database
            $stmt = $pdo->query("SELECT * FROM news");
            $newsArticles = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Display news articles
            foreach ($newsArticles as $article) {
                ?>
                    <div class="news-box">
                        <!-- Image -->
                        <div class="image-container">
                            <img src="img/bg.png" alt="Image Description" class="centered-image">
                        </div>
                        <!-- Article title and message -->
                        <h3 class="news-title"><?php echo substr(htmlspecialchars($article['title']), 0, 30); ?>...</h3>
                        <p class="news-message"><?php echo substr(htmlspecialchars($article['message']), 0, 130); ?>...</p>
    
                        <!-- Read more button to load article content within the dashboard -->
                        <button onclick="loadArticle(<?php echo $article['id']; ?>);">Read More</button>
                    </div>

                <?php
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </div>
</body>
</html>
