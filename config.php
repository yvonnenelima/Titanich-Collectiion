<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "titanic_collection";

// Exchange rates array
$exchange_rates = [
    'KSH' => 1.0,
    'USD' => 0.0067 // Update regularly
];

try {
    // Create a new PDO instance
    $dbh = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);

    // Set the PDO error mode to exception
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Optional: Set default fetch mode to associative array
    $dbh->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // echo "Connected successfully to database!"; // For testing, remove in production
} catch (PDOException $e) {
    // If connection fails, stop script execution and display error
    die("Connection failed: " . $e->getMessage());
}

// Also create MySQLi connection for compatibility with existing code
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check MySQLi connection
if (!$conn) {
    die("MySQLi Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8mb4 for proper character encoding
mysqli_set_charset($conn, "utf8mb4");
?>