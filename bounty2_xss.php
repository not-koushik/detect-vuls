<?php
session_start(); // Start the session to access logged-in user information

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
    // Retrieve the inputs from the form
    $input = $_POST['input']; 
    $guess = $_POST['guess'];

    // Output the input directly (simulating an XSS vulnerability)
    echo "Your input: " . $input . "<br>";

    // Fetch the user from the database using the session's username
    $sql = "SELECT * FROM users WHERE username = '$loggedInUser'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found
        $user = $result->fetch_assoc();
        echo "User found: " . $loggedInUser . "<br>";

        // Check if the guess is "XSS"
        if ($guess === "XSS") {
            echo "You have earned +75 points!<br>";

            // Retrieve current points and add 10
            $currentPoints = $user['points'];
            $newPoints = $currentPoints + 75;

            // Update the user's points in the database
            $updateSql = "UPDATE users SET points = $newPoints WHERE username = '$loggedInUser'";
            if ($conn->query($updateSql) === TRUE) {
                echo "Your points have been updated successfully. You now have $newPoints points.";
            } else {
                echo "Error updating points: " . $conn->error;
            }
        } else {
            echo "WRONG!";
        }
    } else {
        // No user found
        echo "No user found.<br>";
    }
}

$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>Today's Bounty 2</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: rgb(32, 32, 32);
            color: white;
            padding: 20px;
        }

        h2 {
            font-size: 1.8em;
            color: #f1c40f;
            margin-bottom: 20px;
        }

        label {
            font-size: 1.2em;
        }

        input[type="text"] {
            width: 100%;
            padding: 15px;
            font-size: 1.2em;
            margin-top: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
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

        .separator {
            border: 1px solid #444;
            width: 100%;
            margin: 20px 0;
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
    <h2>Good old Twitter days...</h2>
    
    <form method="POST">
        <!-- Input field for regular input -->
        <div class="form-group">
            <label for="input">Enter something:</label>
            <br>
            <input type="text" name="input" id="input">
        </div>

        <!-- Input field for guessing -->
        <div class="form-group">
            <label for="guess">Your guess:</label>
            <br>
            <input type="text" name="guess" id="guess">
        </div>

        <button type="submit">Submit</button>
    </form>

    <hr class='separator'>
    <p>Go back to <a href="home.html">Home</a>.</p>
</body>
</html>
