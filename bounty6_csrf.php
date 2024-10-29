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
    // Vulnerable Command Injection
    if (isset($_POST['command'])) {
        $command = $_POST['command'];

        // Command injection vulnerability: No input sanitization, user input directly in command
        $output = shell_exec($command); // Executes the command directly

        if ($output === null) {
            echo "Error: Command failed.";
        } else {
            echo "<pre>$output</pre>";
        }
    }

    // Handle guess input
    if (isset($_POST['guess'])) {
        $guess = $_POST['guess'];
        if (strtoupper($guess) === "COMMAND INJECTION") {
            echo "<br>You have earned +75 points!";

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
            echo "<br>Wrong!";
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
    <title>Bounty 5</title>
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

        a {
            color: #f1c40f;
            text-decoration: none;
        }

        p {
            font-size: 1.2em;
            color: #ccc;
            margin-bottom: 20px;
        }

        .separator {
            margin-top: 20px;
            border-top: 1px solid #ccc;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <h2>Will I listen to you?</h2>
    
    <!-- Command execution form -->
    <form method="POST">
        <div class="form-group">
            <label for="command">Try me, friend:</label>
            <input type="text" name="command" id="command" required>
        </div>

        <button type="submit">SEND!</button>
    </form>

    <hr class="separator">

    <!-- Guessing form -->
    <div class="form-group">
        <label for="guess">Guess the vulnerability name:</label>
        <br>
        <form method="POST">
            <input type="text" name="guess" id="guess" required>
            <button type="submit">Submit Guess</button>
        </form>
    </div>

    <p>Go back to <a href="home.html">Home</a>.</p>
</body>
</html>
