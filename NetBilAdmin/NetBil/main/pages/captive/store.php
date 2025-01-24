<?php
// Database connection details
$servername = "127.0.0.1";
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "brennet"; // Database name

// Connect to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process the form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Validate input fields
    if (!isset($_POST['phoneNumber']) || !isset($_POST['code'])) {
        die("Phone number or PIN is missing. Please fill in all fields.");
    }

    // Sanitize input values
    $phoneNumber = $conn->real_escape_string($_POST['phoneNumber']);
    $code = $conn->real_escape_string($_POST['code']);

    // Check if the phone number already exists
    $checkUserQuery = "SELECT * FROM reg WHERE phone = '$phoneNumber'";
    $result = $conn->query($checkUserQuery);

    if ($result->num_rows > 0) {
        // User exists, fetch the stored PIN (code)
        $row = $result->fetch_assoc();
        $storedCode = $row['code'];

        if ($storedCode === $code) {
            // PIN matches
            echo "SUCCESSFUL LOGIN. Redirecting...";
            header("Refresh: 2; URL=dashboard.html");
        } else {
            // PIN does not match
            echo "RECORD DOES NOT MATCH. Please try again.";
        }
    } else {
        // User does not exist, register new user
        $insertQuery = "INSERT INTO reg (phone, code) VALUES ('$phoneNumber', '$code')";

        if ($conn->query($insertQuery) === TRUE) {
            echo "New user registered successfully. Redirecting...";
            header("Refresh: 2; URL=offers.html");
        } else {
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>
