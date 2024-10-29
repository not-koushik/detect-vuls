<?php
session_start(); // Start session to get logged-in user's details

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
    // Handle file inclusion
    if (isset($_POST['filename'])) {
        $filename = $_POST['filename'];

        // Vulnerable file inclusion (allows directory traversal)
        $content = @file_get_contents("./uploads/" . $filename);
        
        if ($content === false) {
            echo "<p>Error: Could not read the file.</p>";
        } else {
            echo nl2br($content);
        }
    }

    // Handle guess input
    if (isset($_POST['guess'])) {
        $guess = $_POST['guess'];
        
        // Check if the guess is "DIRECTORY TRAVERSAL"
        if (strtoupper($guess) == "DIRECTORY TRAVERSAL") {
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
    <title>Bounty 4</title>
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

        .guess-section {
            margin-top: 20px;
            padding: 10px;
            background-color: #444;
            border-radius: 5px;
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
    <h2>What can you possibly access from this?</h2>

    <!-- File inclusion form -->
    <form method="POST">
        <div class="form-group">
            <label for="filename">Enter File Name:</label>
            <input type="text" name="filename" id="filename">
        </div>

        <button type="submit">Submit File</button>
    </form>

    <!-- Guess section -->
    <div class="guess-section">
        <h3>Guess the vulnerability:</h3>
        <form method="POST">
            <input type="text" name="guess" id="guess" placeholder="Type your guess here...">
            <button type="submit">Submit Guess</button>
        </form>
    </div>

    <p>Go back to <a href="home.html">Home</a>.</p>
</body>
</html>
