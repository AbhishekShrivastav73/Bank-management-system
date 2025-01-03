<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

include '../api/db.php';

if($_GET['user_id']) {
    $userId = $_GET['user_id'];
    
    // echo $userId;

    // Retrieve user data
    
$userData = "SELECT * FROM users WHERE user_id = $userId";

$users = mysqli_query($con, $userData);

if (!$users) {
    die('Error fetching user: ' . mysqli_error($con));
}

$user  = mysqli_fetch_assoc($users);

$accDets = "SELECT * FROM accounts WHERE user_id = $userId";

$accounts = mysqli_query($con, $accDets);

if (!$accounts) {
    die('Error fetching accounts: ' . mysqli_error($con));
}

$account  = mysqli_fetch_assoc($accounts);


// Fetch user information
$userQuery = "SELECT * FROM users WHERE user_id = $userId";
$userResult = mysqli_query($con, $userQuery);

if (!$userResult) {
    die('Error fetching user: ' . mysqli_error($con));
}

$userData  = mysqli_fetch_assoc($userResult);

// Fetch account information
$accountQuery = "SELECT * FROM accounts WHERE user_id = $userId";
$accountResult = mysqli_query($con, $accountQuery);

// if (!$accountResult) {


$balance = "SELECT balance FROM accounts WHERE user_id = $userId";
$balanceResult = mysqli_query($con, $balance);

if (!$balanceResult) {
    die('Error fetching balance: ' . mysqli_error($con));
}

$balanceData  = mysqli_fetch_assoc($balanceResult);
$currentBalance = $balanceData['balance'];

// Fetch recent transactions
$transactionsQuery = "SELECT * FROM transactions WHERE user_id = $userId ORDER BY transaction_date DESC";
$transactionsResult = mysqli_query($con, $transactionsQuery);

if (!$transactionsResult) {
    die('Error fetching transactions: ' . mysqli_error($con));
}

$transactions = [];
while ($row = mysqli_fetch_assoc($transactionsResult)) {
    $transactions[] = $row;
}

}

// $query = "SELECT * FROM users WHERE role = 'user'";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Canara Bank</title>

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
        thead tr{
            background-color: #405189;
            color: white;
        }
    </style>
</head>

<body>

    <div class="d-flex">
        <?php include('../components/sidenav.php'); ?>
        <div class=" flex-grow-1 overflow bg">
            <nav class="px-2 bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">

                    <i class="ri-menu-2-line fs-5 text-secondary"></i>
                    <input type="text" class="px-3 py-2 border-0" placeholder="Search">
                </div>
                <div class="d-flex align-items-center gap-3">
                    <i class="ri-apps-fill"></i>
                    <i class="ri-visa-fill"></i>
                    <i class="ri-bank-card-fill"></i>
                    <!-- <a href="" class="text-decoration-none border border-secondary px-3 py-1 text-dark" "><?php echo $_SESSION['name']; ?></a> -->
                </div>
            </nav>
            <!-- <div class=" container mt-5"> -->
                        <!-- User Information -->
                      
                     

                <div class=" py-4 px-4 ">

                    <h1 class=" fs-5 lh-1">Account Holder Details</h1>
                    <!-- <p class="fs-6 lh-1">dea</p> -->
                   
                   

                </div>

                <div class="px-4">
                        <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
                        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                        <p><strong>Account Number:</strong> <?php echo $account['account_id']; ?></p>
                        <p><strong>Registered Since:</strong> <?php echo date('d M Y', strtotime($user['created_at'])); ?></p>
                    </div>


                <div class="container mt-4">
                    <h1 class="fs-5 lh-1">Transaction History</h1>
                    <p>Account holder's recent withdrawals and deposits are listed below:</p>


                    <div class="btn-group mb-3" role="group" aria-label="Transaction Filters">
                        <button type="button" onclick="filterTransactions('all')" class="btn btn-outline-primary">All</button>
                        <button type="button" onclick="filterTransactions('deposit')" class="btn btn-outline-success">Deposits</button>
                        <button type="button" onclick="filterTransactions('withdrawal')" class="btn btn-outline-danger">Withdrawals</button>
                    </div>


                    <table class="table table-striped">
                        <thead class="">
                            <tr>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $transaction): ?>
                                <tr class="transaction-row <?php echo strtolower($transaction['transaction_type']); ?>">
                                    <td><?php echo date('d M Y', strtotime($transaction['transaction_date'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $transaction['transaction_type'] == 'Deposit' ? 'success' : 'danger'; ?>">
                                            <?php echo ucfirst($transaction['transaction_type']); ?>
                                        </span>
                                    </td>
                                    <td>₹<?php echo $transaction['amount']; ?></td>
                                    <td><?php echo $transaction['transaction_date'] ?? 'N/A'; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        </div>
    </div>

    <!-- Bootstrap Modal -->
 
    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Show modal if the code is empty -->

   
    <script>
        function filterTransactions(type) {
            const rows = document.querySelectorAll('.transaction-row');

            rows.forEach(row => {
                // Show all rows if 'all' is selected
                if (type === 'all') {
                    row.style.display = '';
                } else {
                    // Show only rows matching the selected type
                    if (row.classList.contains(type)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }
    </script>
</body>

</html>