<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Starting connection test...<br>";

try {
    // Database credentials
    $db_host = getenv('DB_HOST') ?: 'aws-0-us-west-1.pooler.supabase.com';
    $db_port = getenv('DB_PORT') ?: '6543';
    $db_name = getenv('DB_NAME') ?: 'postgres';
    $db_user = getenv('DB_USER') ?: 'postgres.sjufpzxbrjdieymdfhwj';
    $db_password = getenv('DB_PASSWORD');

    echo "Attempting to connect with following details:<br>";
    echo "Host: " . $db_host . "<br>";
    echo "Port: " . $db_port . "<br>";
    echo "Database: " . $db_name . "<br>";
    echo "User: " . $db_user . "<br>";

    // Create connection string
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name;user=$db_user;password=$db_password";
    
    // Create connection
    echo "Creating connection...<br>";
    $conn = new PDO($dsn);
    
    // Set error mode
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Connected successfully!<br>";

    // Test creating the users table if it doesn't exist
    echo "Testing table creation...<br>";
    $sql = "CREATE TABLE IF NOT EXISTS users (
        id SERIAL PRIMARY KEY,
        username VARCHAR(50) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        created_at TIMESTAMP WITH TIME ZONE DEFAULT CURRENT_TIMESTAMP
    )";
    
    $conn->exec($sql);
    echo "Table created/verified successfully!<br>";

    // Test query
    echo "Testing simple query...<br>";
    $result = $conn->query("SELECT COUNT(*) FROM users");
    $count = $result->fetchColumn();
    echo "Number of users in database: " . $count . "<br>";

} catch (PDOException $e) {
    echo "Connection failed. Error details:<br>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Error Code: " . $e->getCode() . "<br>";
}
?>