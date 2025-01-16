<?php
session_start();

$servername = "localhost";
$dbUsername = "root";
$dbPassword = ""; 
$dbname = "DinoDestinations";

// Create connection
$conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Take the posted form data
$username = $_POST['username'];
$password = $_POST['password'];

// Prepare the SQL statement
$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    // Verify the password against the hash in the database
    if (password_verify($password, $row['password'])) {
        // Password is correct, so start a new session
        session_regenerate_id();
        $_SESSION['loggedin'] = TRUE;
        $_SESSION['name'] = $row['username'];
        $_SESSION['id'] = $row['id'];

        header('Location: mainPage.html');
    } else {
        $_SESSION['login_message'] = "Incorrect username or password";
        header('Location: index.php');
    }
} else {
    $_SESSION['login_message'] = "Incorrect username or password";
    header('Location: index.php');
}

$stmt->close();
$conn->close();
?>
