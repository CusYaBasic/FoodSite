<?php
// Start session
session_start();

define('__CONFIG__', true); // Define __CONFIG__ constant
// Include your config.php file to access database details
require_once 'admin/config.php';

// Check if user data is available
$userData = isset($_SESSION['user']) ? $_SESSION['user'] : null;

// Function to highlight active tab
function isActive($tabName) {
    if (isset($_GET['tab']) && $_GET['tab'] == $tabName) {
        return 'active';
    }
    return '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            background-image: url('img/pagebg.png'); /* Path to your background image */
            background-size: cover; /* Cover the entire background */
            background-position: center; /* Center the background image */
            background-repeat: no-repeat; /* Do not repeat the background image */
        }
        /* Reset margin and padding for all elements */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        /* Basic styling for the sidebar */
        .sidebar {
            width: 200px;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background: linear-gradient(0deg, rgba(8,6,4,1) 0%, rgba(56,56,56,1) 100%);
            padding-top: 20px;
            color: #fff;
            z-index: 1; /* Ensure sidebar is above other content */
            margin-top: 40px; /* Adjust according to header height */
            text-align: center;
            font-size: 20px;
        }
        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 5px;
        }
        /* Style for the sidebar links */
        .sidebar ul li a {
            display: block;
            padding: 15px;
            border-radius: 10px;
            transition: background-color 0.3s;
            color: #fff; /* Set font color to white */
            text-decoration: none; /* Remove underline */
        }

        /* Style for the selected tab */
        .sidebar ul li a.active {
            background-color: rgba(200, 200, 200, 0.3);
        }

        /* Hover effect */
        .sidebar ul li a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: #fff; /* Keep font color white */
        }
        /* Basic styling for the content */
        .content {
            margin-left: 220px; /* Adjust according to sidebar width */
            padding: 20px;
            margin-top: 40px; /* Adjust according to header height */
        }
        /* Position the "Account" tab at the bottom */
        .sidebar ul li:last-child {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            padding-bottom: 50px;
            text-align: center;
        }
        /* Header styling */
        .header {
            background: rgba(56,56,56,1);
            color: #fff;
            padding: 10px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 2; /* Ensure header is above other content */
        }
        .header h1 {
            margin: 0;
            padding-left: 20px;
        }
        .header .header-left {
            display: flex;
            align-items: center;
        }
        .header .header-right {
            display: flex;
            align-items: center;
            margin-left: auto; /* Push to the right */
        }
        .header .header-right p {
            margin: 0;
            margin-right: 10px; /* Add right margin for spacing */
        }
        .login {
            text-decoration: none;
            color: #fff;
            padding-right: 10px;
        }
            .login-btn {
            border: 1px solid #ccc; /* Add a 1px solid border with light gray color */
            padding: 8px 16px; /* Add some padding to the button */
            border-radius: 5px; /* Add border-radius for rounded corners */
            text-decoration: none; /* Remove underline for anchor element */
            display: inline-block; /* Ensure the button behaves like an inline element */
            margin-right: 10px; /* Add some margin for spacing */
            color: #fff;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="header-left">
            <h1><?php echo $sitename; ?></h1>
        </div>
            <div class="header-right">
                <?php if ($userData) : ?>
                    <p>Hello, <?php echo $userData['name']; ?>!</p>
                    <a href="logout.php" class="login-btn">Logout</a> <!-- Change class to login-btn -->
                <?php else : ?>
                    <p>Hello, Guest!</p>
                    <a href="index.php" class="login-btn">Login</a> <!-- Change class to login-btn -->
                <?php endif; ?>
            </div>
         </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul>
            <!-- "News" link -->
            <li><a href="dashboard.php?tab=news" class="<?php echo isActive('news'); ?>"><i class="fa fa-newspaper-o" aria-hidden="true"></i> News</a></li>
            <!-- "Menu" link -->
            <li><a href="dashboard.php?tab=menu" class="<?php echo isActive('menu'); ?>"><i class="fa fa-cutlery" aria-hidden="true"></i> Menu</a></li>
            <!-- "Account" link -->
            <li><a href="dashboard.php?tab=account" class="<?php echo isActive('account'); ?>"><i class="fa fa-user-circle-o" aria-hidden="true"></i> Account</a></li>
        </ul>
    </div>


    <!-- Content -->
    <div class="content">
        <?php
        // Include content based on the selected tab
        $defaultTab = isset($_GET['tab']) ? $_GET['tab'] : 'news'; // Set the default tab to 'news' if no tab is provided
        switch ($defaultTab) {
            case 'news':
                include 'news.php'; // Assuming you have a separate file for news content
                break;
            case 'menu':
                include 'menu.php'; // Assuming you have a separate file for menu content
                break;
            case 'account':
                include 'account.php'; // Assuming you have a separate file for account content
                break;
            default:
                include 'news.php'; // Default to 'news' tab if an invalid tab is provided
                break;
        }
        ?>
    </div>
    <script>
    // Function to dynamically load article content within the dashboard
    function loadArticle(articleId) {
        // Fetch article content using AJAX
        var xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // Inject the article content into the dashboard content area
                    document.querySelector('.content').innerHTML = xhr.responseText;
                } else {
                    // Handle error
                    console.error("Failed to load article content.");
                }
            }
        };
        xhr.open("GET", "article.php?id=" + articleId, true);
        xhr.send();
    }
    </script>

</body>
</html>