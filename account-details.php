<?php
session_start(); // Start the session

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // If not logged in, redirect to the login page
    header("Location: index.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "Bounty";
$dbport = 3307;

$conn = new mysqli($servername, $username, $password, $dbname, $dbport);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the logged-in user's username from the session
$loggedInUser = $_SESSION['username'];

// Fetch user details from the database
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $loggedInUser);
$stmt->execute();
$result = $stmt->get_result();

// Check if the user was found
if ($result->num_rows > 0) {
    $userDetails = $result->fetch_assoc();
} else {
    echo "User not found.";
    exit();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Account</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: rgb(32, 32, 32);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .account-box {
            background-color: rgb(44, 44, 44);
            padding: 40px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
        }
        .account-box h1 {
            margin-bottom: 20px;
            color: #f1c40f;
        }
        .account-box p {
            font-size: 1.2em;
            margin: 10px 0;
        }
        .back-link {
            display: block;
            margin-top: 20px;
            color: #3498db;
            text-decoration: none;
            font-size: 1.1em;
            transition: color 0.3s ease;
        }
        .back-link:hover {
            color: #5dade2; /* Lighter blue on hover */
        }
    </style>
</head>
<body>

    <div class="account-box">
        <h1>Account Details</h1>
        <p><strong>Username:</strong> <?php echo htmlspecialchars($userDetails['username']); ?></p>
        <p><strong>Points:</strong> <?php echo htmlspecialchars($userDetails['points']); ?></p>
        <a href="home.html" class="back-link">Back to Home</a>
    </div>

</body>
</html>
