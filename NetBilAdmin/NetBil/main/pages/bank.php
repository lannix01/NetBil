<?php
header('Content-Type: application/json');

// Database connection
$mysqli = new mysqli('localhost', 'root', '', 'bank_db');

// Check database connection
if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Get the action from the request
$action = isset($_POST['action']) ? $_POST['action'] : '';

switch ($action) {
    // Create a new account
    case 'create':
        $account_number = isset($_POST['account_number']) ? $_POST['account_number'] : '';
        $account_holder = isset($_POST['account_holder']) ? $_POST['account_holder'] : '';
        $initial_balance = isset($_POST['initial_balance']) ? $_POST['initial_balance'] : 0;

        // Validate inputs
        if (empty($account_number) || empty($account_holder)) {
            echo json_encode(['success' => false, 'message' => 'Account number and account holder are required']);
            exit;
        }

        // Insert new account into database
        $stmt = $mysqli->prepare("INSERT INTO bnk_accounts (account_number, account_holder, balance) VALUES (?, ?, ?)");
        $stmt->bind_param('ssd', $account_number, $account_holder, $initial_balance);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error creating account', 'error' => $stmt->error]);
        }
        $stmt->close();
        break;

    // Deposit money into an account
    case 'deposit':
        $account_number = isset($_POST['account_number']) ? $_POST['account_number'] : '';
        $amount = isset($_POST['amount']) ? $_POST['amount'] : 0;

        // Validate inputs
        if (empty($account_number) || $amount <= 0) {
            echo json_encode(['success' => false, 'message' => 'Valid account number and positive amount are required']);
            exit;
        }

        // Deposit money into the account
        $stmt = $mysqli->prepare("UPDATE bnk_accounts SET balance = balance + ? WHERE account_number = ?");
        $stmt->bind_param('ds', $amount, $account_number);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error depositing money', 'error' => $stmt->error]);
        }
        $stmt->close();
        break;

    // Withdraw money from an account
    case 'withdraw':
        $account_number = isset($_POST['account_number']) ? $_POST['account_number'] : '';
        $amount = isset($_POST['amount']) ? $_POST['amount'] : 0;

        // Validate inputs
        if (empty($account_number) || $amount <= 0) {
            echo json_encode(['success' => false, 'message' => 'Valid account number and positive amount are required']);
            exit;
        }

        // Withdraw money from the account
        $stmt = $mysqli->prepare("UPDATE bnk_accounts SET balance = balance - ? WHERE account_number = ?");
        $stmt->bind_param('ds', $amount, $account_number);

        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error withdrawing money', 'error' => $stmt->error]);
        }
        $stmt->close();
        break;

    // Transfer money between accounts
    case 'transfer':
        $from_account = isset($_POST['from_account']) ? $_POST['from_account'] : '';
        $to_account = isset($_POST['to_account']) ? $_POST['to_account'] : '';
        $amount = isset($_POST['amount']) ? $_POST['amount'] : 0;

        // Validate inputs
        if (empty($from_account) || empty($to_account) || $amount <= 0) {
            echo json_encode(['success' => false, 'message' => 'Valid source and destination accounts and positive amount are required']);
            exit;
        }

        // Start transaction
        $mysqli->begin_transaction();
        try {
            // Check if source account exists and has enough balance
            $stmt1 = $mysqli->prepare("SELECT balance FROM bnk_accounts WHERE account_number = ?");
            $stmt1->bind_param('s', $from_account);
            $stmt1->execute();
            $stmt1->bind_result($from_balance);
            $stmt1->fetch();
            $stmt1->close();

            if ($from_balance < $amount) {
                echo json_encode(['success' => false, 'message' => 'Insufficient funds']);
                $mysqli->rollback();
                exit;
            }

            // Update the source account
            $stmt2 = $mysqli->prepare("UPDATE bnk_accounts SET balance = balance - ? WHERE account_number = ?");
            $stmt2->bind_param('ds', $amount, $from_account);
            $stmt2->execute();

            // Update the destination account
            $stmt3 = $mysqli->prepare("UPDATE bnk_accounts SET balance = balance + ? WHERE account_number = ?");
            $stmt3->bind_param('ds', $amount, $to_account);
            $stmt3->execute();

            // Commit transaction
            $mysqli->commit();
            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            // Rollback transaction on failure
            $mysqli->rollback();
            echo json_encode(['success' => false, 'message' => 'Error transferring money', 'error' => $e->getMessage()]);
        }
        break;

    // Check account balance
    case 'check_balance':
        $account_number = isset($_POST['account_number']) ? $_POST['account_number'] : '';

        // Validate inputs
        if (empty($account_number)) {
            echo json_encode(['success' => false, 'message' => 'Account number is required']);
            exit;
        }

        // Get account balance
        $stmt = $mysqli->prepare("SELECT account_number, account_holder, balance FROM bnk_accounts WHERE account_number = ?");
        $stmt->bind_param('s', $account_number);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            echo json_encode(['success' => true, 'account' => $row]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Account not found']);
        }
        $stmt->close();
        break;

    // Default case for invalid action
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

$mysqli->close();
?>
