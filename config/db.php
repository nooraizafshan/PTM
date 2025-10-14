<?php
// config/db.php

$db_config = [
    'host' => 'localhost',
    'username' => 'root',
    'password' => '',
    'database' => 'educonnect'
];

// Database connection function
function dbConnect() {
    global $db_config;
    $conn = new mysqli(
        $db_config['host'],
        $db_config['username'],
        $db_config['password'],
        $db_config['database']
    );

    if ($conn->connect_error) {
        die("Database connection failed: " . $conn->connect_error);
    }
    return $conn;
}
?>
