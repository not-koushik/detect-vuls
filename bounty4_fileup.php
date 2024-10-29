<?php
session_start(); // Start the session to get the logged-in user's details

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Bounty"; 
$dbport = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $dbport);

// Check if the connection was successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If the user is not logged in, redirect to the login page
    header("Location: home.html");
    exit();
}

// Get the logged-in user's username from the session
$loggedInUser = $_SESSION['username'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Handle file upload
    if (isset($_FILES['file'])) {
        $file = $_FILES['file'];

        // Vulnerable file upload (no file type validation)
        if (move_uploaded_file($file['tmp_name'], "uploads/" . $file['name'])) {
            echo "<p>File uploaded successfully!</p>";
        } else {
            echo "<p>Failed to upload file.</p>";
        }
    }

    // Handle guess submission
    if (isset($_POST['guess'])) {
        $guess = $_POST['guess'];

        // Check if the guess is "INSECURE FILE UPLOAD"
        if (strtoupper($guess) == "INSECURE FILE UPLOAD") {
            echo "<p>You have earned +75 points!</p>";

            // Fetch the current points for the logged-in user
            $sql = "SELECT points FROM users WHERE username = '$loggedInUser'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // User found, update the points
                $user = $result->fetch_assoc();
                $currentPoints = $user['points'];
                $newPoints = $currentPoints + 75;

                // Update the user's points in the database
                $updateSql = "UPDATE users SET points = $newPoints WHERE username = '$loggedInUser'";
                if ($conn->query($updateSql) === TRUE) {
                    echo "<p>Your points have been updated successfully. You now have $newPoints points.</p>";
                } else {
                    echo "<p>Error updating points: " . $conn->error . "</p>";
                }
            } else {
                echo "<p>No user found.</p>";
            }
        } else {
            echo "<p>Wrong</p>";
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Upload Vulnerability</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: rgb(32, 32, 32);
            color: white;
            padding: 20px;
        }

        h2 {
            font-size: 2em;
            color: #f1c40f;
            margin-bottom: 20px;
        }

        label {
            font-size: 1.2em;
        }

        input[type="file"] {
            font-size: 1.2em;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        input[type="text"] {
            font-size: 1.2em;
            padding: 10px;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button {
            padding: 15px 30px;
            font-size: 1.2em;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #2980b9;
        }

        .form-group {
            margin-bottom: 20px;
        }

        a {
            color: #f1c40f;
            text-decoration: none;
        }

        p {
            font-size: 1.2em;
            color: #ccc;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Try messing around with the contents</h2>

    <!-- Wrap everything inside a single form -->
    <form method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="file">Upload a file:</label>
            <input type="file" name="file" id="file">
        </div>
        
        <div class="form-group">
            <label for="guess">Guess the vulnerability:</label>
            <input type="text" name="guess" id="guess">
        </div>

        <button type="submit">Submit Guess and Upload</button>
    </form>

    <p>Go back to <a href="home.html">Home</a>.</p>
</body>
</html>