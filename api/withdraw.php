<?php
session_start();
include '../api/db.php';

$userId = $_SESSION['user_id'];

$query = "SELECT account_id, balance FROM accounts WHERE user_id = $userId";
$result = mysqli_query($con, $query);

if (!$result) {
    die('Error fetching account ID: ' . mysqli_error($con));
}

$acc = mysqli_fetch_assoc($result);
$accountId = $acc['account_id'];
$balance = $acc['balance'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = $_POST['amount'];
    $code = $_POST['code'];

    $checkCode = "SELECT code FROM user_codes WHERE user_id = $userId";
    $checkResult = mysqli_query($con, $checkCode);
    if (!$checkResult) {
        die('Error fetching user codes: ' . mysqli_error($con));
    }

    $data = mysqli_fetch_assoc($checkResult);
    $userCode = $data['code'];

    if ($code != $userCode) {
        echo "<script>alert('Invalid code. Please try again.'); window.location.href = '../user/dashboard.php';</script>";
        exit;
    }

    if ($amount > $balance) {
        echo "<script>alert('Insufficient balance. Please try again.'); window.location.href = '../user/dashboard.php';</script>";
        exit;
    }

    $transaction = "INSERT INTO transactions (account_id, user_id, transaction_type, amount) VALUES ('$accountId', '$userId', 'Withdrawal', '$amount')";
    if (mysqli_query($con, $transaction)) {
        $updateBalance = "UPDATE accounts SET balance = balance - $amount WHERE user_id = $userId";
        if (mysqli_query($con, $updateBalance)) {
            echo "<script>alert('Withdrawal successful.'); window.location.href = '../user/dashboard.php';</script>";
        } else {
            die('Error updating balance: ' . mysqli_error($con));
        }
    } else {
        die('Error executing transaction: ' . mysqli_error($con));
    }
}

mysqli_close($con);
