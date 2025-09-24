<?php
// Turn on error reporting for testing
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Include your db.php
require_once __DIR__ . '/config/db.php';

// Try to connect
$conn = dbConnect();

if ($conn) {
    echo "<h2 style='color:green;'>✅ Database connection successful!</h2>";
    echo "Connected to database: <b>" . $conn->server_info . "</b>";
    $conn->close();
} else {
    echo "<h2 style='color:red;'>❌ Database connection failed.</h2>";
}
?>
