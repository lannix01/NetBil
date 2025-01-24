<?php
// Enable error reporting for debugging during development
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fetch notifications data from the API
function fetchNotifications($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Disable SSL verification for testing
    curl_setopt($ch, CURLOPT_TIMEOUT, 10); // Set timeout to prevent hanging

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return ['error' => 'Error fetching notifications: ' . curl_error($ch)];
    }

    curl_close($ch);

    // Attempt to decode JSON response
    $decodedResponse = json_decode($response, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return ['error' => 'JSON decoding error: ' . json_last_error_msg()];
    }

    return $decodedResponse;
}

// Calculate total revenue from the notification data
function calculateRevenue($notifications) {
    $totalRevenue = 0;

    // Ensure data is an array
    if (!is_array($notifications)) {
        return 0;
    }

    foreach ($notifications as $notification) {
        if (isset($notification['amount']) && is_numeric($notification['amount'])) {
            $totalRevenue += (float) $notification['amount'];
        }
    }

    return $totalRevenue;
}

// API URL
$apiUrl = 'https://net1.amazons.co.ke/notifications';

// Fetch data from the API
$notifications = fetchNotifications($apiUrl);

// Check for errors in the response
if (isset($notifications['error'])) {
    $revenue = 0; // Default to 0 if there's an error
    $errorMessage = $notifications['error'];
} else {
    $revenue = calculateRevenue($notifications);
    $errorMessage = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Revenue</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
        }
        .dashboard {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            width: 300px;
        }
        .dashboard h1 {
            margin: 0;
            font-size: 2em;
            color: #333;
        }
        .dashboard p {
            font-size: 1.2em;
            margin: 20px 0;
            color: #666;
        }
        .dashboard button {
            padding: 10px 20px;
            font-size: 1em;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .dashboard button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <h1>Revenue</h1>
        <?php if ($errorMessage): ?>
            <p class="error"><?= htmlspecialchars($errorMessage); ?></p>
        <?php else: ?>
            <p>Total Revenue: <strong>KES <?= number_format($revenue, 2); ?></strong></p>
        <?php endif; ?>
        <form method="GET">
            <button type="submit">Refresh</button>
        </form>
    </div>
</body>
</html>
