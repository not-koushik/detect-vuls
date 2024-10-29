<?php
session_start(); // Start the session

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

$user = $_POST['username'];
$pass = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $user, $pass);

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Store the username in the session
    $_SESSION['username'] = $user;
    header("Location: home.html"); // Redirect to home page after successful login
    exit();
} else {
    header("Location: error.html"); // Redirect to error page if login fails
    exit();
}

$stmt->close();
$conn->close();
?>