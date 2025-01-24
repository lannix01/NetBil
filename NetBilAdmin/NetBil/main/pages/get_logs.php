<?php
// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'bank_db');

// Check connection
if ($mysqli->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $mysqli->connect_error]));
}

// Query to fetch logs from the 'logs' table
$query = "SELECT action, details, timestamp FROM logs ORDER BY timestamp ASC"; // Assuming there's a 'timestamp' column

$result = $mysqli->query($query);

// Check if query was successful
if (!$result) {
    die(json_encode(['error' => 'Query failed: ' . $mysqli->error]));
}

// Fetch all logs
$logs = [];
while ($row = $result->fetch_assoc()) {
    $logs[] = "[" . $row['timestamp'] . "] " . $row['action'] . " - " . $row['details']; // Format log message
}

// Close the database connection
$mysqli->close();

// Return the logs as a JSON response
echo json_encode(['logs' => $logs]);
?>
