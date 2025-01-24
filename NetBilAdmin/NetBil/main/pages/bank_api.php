<?php
header('Content-Type: application/json');

// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'bank_db');
if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed', 'logs' => []]);
    exit;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';
$response = ['logs' => []]; // Initialize logs to empty array

// Helper function to insert logs into the logs table
function insertLog($action, $details, $mysqli) {
    $stmt = $mysqli->prepare("INSERT INTO logs (action, details) VALUES (?, ?)");
    $stmt->bind_param('ss', $action, $details);
    $stmt->execute();
    $stmt->close();
}

switch ($action) {
    case 'create':
        $account_number = $_POST['account_number'];
        $account_holder = $_POST['account_holder'];
        $initial_balance = $_POST['initial_balance'];

        $stmt = $mysqli->prepare("INSERT INTO bank_accounts (account_number, account_holder, balance) VALUES (?, ?, ?)");
        $stmt->bind_param('ssd', $account_number, $account_holder, $initial_balance);

        if ($stmt->execute()) {
            $log_message = "Account created for: $account_holder";
            insertLog($action, $log_message, $mysqli); // Insert log into logs table
            $response = [
                'success' => true,
                'logs' => [$log_message]
            ];
        } else {
            $log_message = 'Account creation failed.';
            insertLog($action, $log_message, $mysqli); // Insert log into logs table
            $response = ['success' => false, 'message' => 'Account creation failed', 'logs' => [$log_message]];
        }
        $stmt->close();
        break;

    case 'deposit':
        $account_number = $_POST['account_number'];
        $amount = $_POST['amount'];

        $stmt = $mysqli->prepare("UPDATE bank_accounts SET balance = balance + ? WHERE account_number = ?");
        $stmt->bind_param('ds', $amount, $account_number);

        if ($stmt->execute()) {
            $log_message = "Deposited $amount to account: $account_number";
            insertLog($action, $log_message, $mysqli); // Insert log into logs table
            $response = [
                'success' => true,
                'logs' => [$log_message]
            ];
        } else {
            $log_message = 'Deposit failed.';
            insertLog($action, $log_message, $mysqli); // Insert log into logs table
            $response = ['success' => false, 'message' => 'Deposit failed', 'logs' => [$log_message]];
        }
        $stmt->close();
        break;

    case 'withdraw':
        $account_number = $_POST['account_number'];
        $amount = $_POST['amount'];

        $stmt = $mysqli->prepare("UPDATE bank_accounts SET balance = balance - ? WHERE account_number = ?");
        $stmt->bind_param('ds', $amount, $account_number);

        if ($stmt->execute()) {
            $log_message = "Withdrew $amount from account: $account_number";
            insertLog($action, $log_message, $mysqli); // Insert log into logs table
            $response = [
                'success' => true,
                'logs' => [$log_message]
            ];
        } else {
            $log_message = 'Withdrawal failed.';
            insertLog($action, $log_message, $mysqli); // Insert log into logs table
            $response = ['success' => false, 'message' => 'Withdrawal failed', 'logs' => [$log_message]];
        }
        $stmt->close();
        break;

    case 'transfer':
        $from_account = $_POST['from_account'];
        $to_account = $_POST['to_account'];
        $amount = $_POST['amount'];

        $mysqli->begin_transaction();
        try {
            // Withdraw from the source account
            $stmt1 = $mysqli->prepare("UPDATE bank_accounts SET balance = balance - ? WHERE account_number = ?");
            $stmt1->bind_param('ds', $amount, $from_account);
            $stmt1->execute();

            // Deposit into the destination account
            $stmt2 = $mysqli->prepare("UPDATE bank_accounts SET balance = balance + ? WHERE account_number = ?");
            $stmt2->bind_param('ds', $amount, $to_account);
            $stmt2->execute();

            $mysqli->commit();

            $log_message = "Transferred $amount from $from_account to $to_account";
            insertLog($action, $log_message, $mysqli); // Insert log into logs table
            $response = [
                'success' => true,
                'logs' => [$log_message]
            ];
        } catch (Exception $e) {
            $mysqli->rollback();
            $log_message = 'Transfer failed.';
            insertLog($action, $log_message, $mysqli); // Insert log into logs table
            $response = ['success' => false, 'message' => 'Transfer failed', 'logs' => [$log_message]];
        }
        break;

    case 'check_balance':
        $account_number = $_POST['account_number'];

        if (empty($account_number)) {
            $response = ['success' => false, 'message' => 'Account number required.', 'logs' => []];
            break;
        }

        $stmt = $mysqli->prepare("SELECT account_holder, balance FROM bank_accounts WHERE account_number = ?");
        $stmt->bind_param('s', $account_number);
        $stmt->execute();
        $stmt->bind_result($account_holder, $balance);
        $stmt->fetch();
        $stmt->close();

        if ($account_holder !== null) {
            $log_message = "Checked balance for account: $account_number";
            insertLog($action, $log_message, $mysqli); // Insert log into logs table
            $response = [
                'success' => true,
                'account' => [
                    'number' => $account_number,
                    'holder' => $account_holder,
                    'balance' => $balance
                ],
                'logs' => [$log_message]
            ];
        } else {
            $log_message = 'Account not found.';
            insertLog($action, $log_message, $mysqli); // Insert log into logs table
            $response = ['success' => false, 'message' => 'Account not found.', 'logs' => [$log_message]];
        }
        break;

    default:
        $log_message = 'Invalid action.';
        insertLog($action, $log_message, $mysqli); // Insert log into logs table
        $response = ['success' => false, 'message' => 'Invalid action.', 'logs' => []];
        break;
}

echo json_encode($response);
?>
