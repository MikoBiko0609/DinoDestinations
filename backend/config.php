<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$servername = getenv('DB_HOST') ?: 'db4free.net';
$dbUsername = getenv('DB_USER') ?: 'dinomiko';
$dbPassword = getenv('DB_PASSWORD') ?: 'dino1223';
$dbname = getenv('DB_NAME') ?: 'dinodestinations';

try {
    // Create connection with error reporting
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
    
    // Set longer timeout for db4free
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 30);
    
    // Check connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        throw new Exception("Database connection failed");
    }
    
    // Set charset to utf8mb4
    $conn->set_charset('utf8mb4');
    
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed', 'details' => $e->getMessage()]);
    exit();
}
?>