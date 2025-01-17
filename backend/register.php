<?php
session_start();
require_once 'config.php';

// Add CORS headers
header('Access-Control-Allow-Origin: https://dino-destinations.vercel.app');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Accept, Origin');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Take the posted form data
        $username = $_POST['username'] ?? null;
        $password = $_POST['password'] ?? null;
        $confirmPassword = $_POST['confirmPassword'] ?? null;

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
        $checkStmt = $conn->prepare("SELECT username FROM users WHERE username = :username");
        $checkStmt->execute(['username' => $username]);
        
        if ($checkStmt->rowCount() > 0) {
            throw new Exception("Username already exists");
        }
        
        // Insert new user
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
        if (!$stmt) {
            throw new Exception("Prepare failed");
        }

        $success = $stmt->execute([
            'username' => $username,
            'password' => $hashedPassword
        ]);
        
        if (!$success) {
            throw new Exception("Failed to create user");
        }

        echo json_encode([
            'success' => true,
            'message' => 'Registration successful',
            'redirect' => 'index.html'
        ]);

    } catch (Exception $e) {
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
?>