<?php
// config.php
$servername = getenv('DB_HOST') ?: 'db4free.net';  
$dbUsername = getenv('DB_USER') ?: 'dinomiko';  
$dbPassword = getenv('DB_PASSWORD') ?: 'dino1223';  
$dbname = getenv('DB_NAME') ?: 'dinodestinations';  

try {
    // Increase timeout as db4free is slow
    $conn = new mysqli($servername, $dbUsername, $dbPassword, $dbname);
    $conn->options(MYSQLI_OPT_CONNECT_TIMEOUT, 30);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => 'Database connection failed']);
    exit();
}
?>