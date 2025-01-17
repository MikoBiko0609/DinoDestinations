<?php
session_start();
require_once 'config.php';  // This will use the connection from config.php

// Add CORS headers for the API
header('Access-Control-Allow-Origin: https://dino-destinations-pm0xmrbb9-michael-de-leons-projects.vercel.app');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Take the posted form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    try {
        // Prepare the SQL statement
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }

        $stmt->bind_param("s", $username);
        
        if (!$stmt->execute()) {
            throw new Exception("Execute failed: " . $stmt->error);
        }

        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Verify the password against the hash in the database
            if (password_verify($password, $row['password'])) {
                // Password is correct, so start a new session
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['name'] = $row['username'];
                $_SESSION['id'] = $row['id'];

                echo json_encode([
                    'success' => true,
                    'message' => 'Login successful',
                    'redirect' => 'mainPage.html'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Incorrect username or password'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Incorrect username or password'
            ]);
        }

        $stmt->close();
    } catch (Exception $e) {
        error_log($e->getMessage());
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'An error occurred during login'
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