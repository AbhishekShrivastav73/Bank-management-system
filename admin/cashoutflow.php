<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

include '../api/db.php';

$query = "SELECT * FROM transactions 
          JOIN accounts ON transactions.account_id = accounts.account_id 
          JOIN users ON transactions.user_id = users.user_id 
          WHERE transaction_type = 'Withdrawal'";

$result = mysqli_query($con, $query);

if (!$result) {
    die('Error fetching cashout transactions: ' . mysqli_error($con));
}

$transactions = [];
$totalAmount = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $transactions[] = $row;
    $totalAmount += $row['amount'];
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashout Flow | Canara Bank</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional: Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>

    <style>
        body,
        .bg {
            background-color: #F3F3F9;
        }

        h1 {
            color: rgb(36, 45, 74);
        }

        h4 {
            color: rgb(118, 118, 118);
            font-weight: bold;
            letter-spacing: -0.8px;
        }

        .card {
            border-radius: 10px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .card .card-body {
            padding: 20px;
        }

        .dashboard-card {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
        }

        .dashboard-card h5 {
            color: #6c757d;
        }

        .dashboard-card p {
            font-size: 1.2rem;
            color: #5a5a5a;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .transaction-list {
            margin-top: 20px;
        }

        .transaction-item {
            background-color: #fff;
            border-radius: 10px;
            padding: 10px;
            margin-bottom: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .transaction-item .date {
            color: #888;
            font-size: 0.9rem;
        }

        .transaction-item .amount {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .quick-actions {
            margin-top: 30px;
        }

        .quick-action-btn {
            background-color: #5cb85c;
            color: white;
            border-radius: 10px;
            padding: 10px 20px;
            text-align: center;
            width: 48%;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .quick-action-btn:hover {
            background-color: #4cae4c;
        }

        input {
            background-color: #F3F3F9;
        }

        .font {
            color: #6c757d;
            font-weight: 500;
            letter-spacing: -0.5px;
            font-size: 14px;
        }

        .icon {
            color: #0AB39C;
            font-size: 25px;
            top: 13px;
            right: 13px;
            background: rgba(10, 179, 156, 0.19);
            padding: 4px 10px;
        }

        .overflow {
            height: 100vh;
            overflow: auto;
        }

        .table-container {
            margin-top: 20px;
        }

        /* Styling for the transactions table */
        .transactions-table th,
        .transactions-table td {
            text-align: center;
            vertical-align: middle;
        }

        .transactions-table th {
            background-color: #0AB39C;
            color: white;
        }

        .transactions-table td {
            background-color: #f8f9fa;
        }

        .transactions-table tbody tr:nth-child(even) {
            background-color: #f1f1f1;
        }

        .transactions-table tbody tr:hover {
            background-color: #e9ecef;
        }

        thead tr {
            background: #405189;
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <?php include('../components/sidenav.php'); ?>
        <div class="flex-grow-1 overflow bg">
            <nav class="px-2 bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <i class="ri-menu-2-line fs-5 text-secondary"></i>
                    <input type="text" class="px-3 py-2 border-0" placeholder="Search">
                </div>
                <div class="d-flex align-items-center gap-3">
                    <i class="ri-apps-fill"></i>
                    <i class="ri-visa-fill"></i>
                    <i class="ri-bank-card-fill"></i>
                </div>
            </nav>

            <div class="py-4 px-4">
                <div class="d-flex  align-items-center justify-content-between">

                    <h1 class="fs-5 lh-1">Cash Out-flow Details</h1>
                    <p class="fs-5 lh-1 fw-bold text-secondary">Total Cash Out-flow : <?php echo number_format($totalAmount, 2); ?></p>
                </div>

                <!-- Transaction Table -->
                <div class="table-container">
                    <table class="table table-striped table-bordered transactions-table">
                        <thead>
                            <tr>
                                <th>Transaction ID</th>
                                <th>User Name</th>
                                <th>Account Number</th>
                                <th>Transaction Date</th>
                                <th>Amount</th>
                                <th>Transaction Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr>
                                    <td><?php echo $transaction['transaction_id']; ?></td>
                                    <td><?php echo $transaction['name']; ?></td>
                                    <td><?php echo $transaction['account_id']; ?></td>
                                    <td><?php echo date('d M Y', strtotime($transaction['transaction_date'])); ?></td>
                                    <td>₹<?php echo number_format($transaction['amount'], 2); ?></td>
                                    <td><span class="badge bg-danger"><?php echo $transaction['transaction_type']; ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>