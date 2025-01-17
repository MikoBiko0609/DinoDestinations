<?php
// Set error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Start session
session_start();

// CORS headers - must be before any output
header('Access-Control-Allow-Origin: https://dino-destinations.vercel.app');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept, Origin');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Include database configuration
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Log incoming request data for debugging
        error_log("Received POST request: " . print_r($_POST, true));

        // Take the posted form data
        $username = isset($_POST['username']) ? $_POST['username'] : null;
        $password = isset($_POST['password']) ? $_POST['password'] : null;
        $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : null;

        // Basic validation
        if (empty($username) || empty($password)) {
            throw new Exception("All fields are required");
        }

        if ($password !== $confirmPassword) {
            throw new Exception("Passwords do not match");
        }

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if username exists
        $checkStmt = $conn->prepare("SELECT username FROM users WHERE username = ?");
        $checkStmt->bind_param("s", $username);
        $checkStmt->execute();
        $result = $checkStmt->get_result();
        
        if ($result->num_rows > 0) {
            throw new Exception("Username already exists");
        }
        
        // Prepare the insert statement
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("ss", $username, $hashedPassword);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        echo json_encode([
            'success' => true,
            'message' => 'Registration successful',
            'redirect' => 'index.html'
        ]);

        $stmt->close();
    } catch (Exception $e) {
        // Log the error
        error_log("Registration error: " . $e->getMessage());
        
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => $e->getMessage()
        ]);
    }
} else {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
}

$conn->close();
?>