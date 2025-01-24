<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection details
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "brennet";

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed']));
}

// Fetch total users count
$sql = "SELECT COUNT(*) AS total_users FROM reg";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo json_encode(['totalUsers' => $row['total_users']]);
} else {
    echo json_encode(['totalUsers' => 0]);
}

// Close the connection
$conn->close();
?>
