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

        if (empty($username) || empty($password)) {
            throw new Exception("Username and password are required");
        }

        // Prepare the SQL statement
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
        if (!$stmt) {
            throw new Exception("Prepare failed");
        }

        $stmt->execute(['username' => $username]);
        
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Verify the password
            if (password_verify($password, $row['password'])) {
                // Password is correct, start session
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

    } catch (Exception $e) {
        error_log("Login error: " . $e->getMessage());
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
?>