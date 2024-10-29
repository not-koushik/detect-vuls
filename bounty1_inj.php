<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Bounty"; 
$dbport = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $dbport);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $guess = $_POST['guess'];
    
    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found
        $user = $result->fetch_assoc();
        echo "User found: " . $username . "<br>";

        if ($guess === "SQL Injection") {
            echo "You have earned +75 points!<br>";

            // Retrieve current points and add 10
            $currentPoints = $user['points'];
            $newPoints = $currentPoints + 75;

            // Update the user's points in the database
            $updateSql = "UPDATE users SET points = $newPoints WHERE username = '$username'";
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Today's Bounty 1</title>
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
        p {
            font-size: 1.2em;
            color: #ccc;
            margin-bottom: 20px;
        }
        a {
            color: #f1c40f;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h2>‣ Find a small glitch and win big rewards!</h2>
    <form method="POST">
        <div class="form-group">
            <label for="username">What could this lead us to..?</label>
            <br>
            <input type="text" name="username" id="username">
        </div>

        <hr class='separator'>
        <br>

        <div class="form-group">
            <label for="guess">Shoot your guess:</label>
            <br>
            <input type="text" name="guess" id="guess">
        </div>

        <button type="submit">Submit</button>
        <p>Go back to <a href="home.html">Home</a>.</p>
    </form>
</body>
</html>
