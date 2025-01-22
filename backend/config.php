<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get environment variables
$db_host = getenv('DB_HOST') ?: 'aws-0-us-west-1.pooler.supabase.com';
$db_port = getenv('DB_PORT') ?: '6543';
$db_name = getenv('DB_NAME') ?: 'postgres';
$db_user = getenv('DB_USER') ?: 'postgres.sjufpzxbrjdieymdfhwj';
$db_password = getenv('DB_PASSWORD');

try {
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name;user=$db_user;password=$db_password";
    $conn = new PDO($dsn);
    
    // Set error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    error_log("Database connection error: " . $e->getMessage());
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode([
        'error' => 'Database connection failed', 
        'details' => $e->getMessage()
    ]);
    exit();
}
?>