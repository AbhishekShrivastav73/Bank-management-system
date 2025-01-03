<?php
session_start();
if ($_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit();
}

include '../api/db.php';

$userId = $_SESSION['user_id'];

$balance = "SELECT balance FROM accounts WHERE user_id = $userId";
$balanceResult = mysqli_query($con, $balance);

if (!$balanceResult) {
    die('Error fetching balance: ' . mysqli_error($con));
}

$balanceData  = mysqli_fetch_assoc($balanceResult);
$currentBalance = $balanceData['balance'];

// Fetch recent transactions
$transactionsQuery = "SELECT * FROM transactions WHERE user_id = $userId ORDER BY transaction_date DESC LIMIT 5";
$transactionsResult = mysqli_query($con, $transactionsQuery);

if (!$transactionsResult) {
    die('Error fetching transactions: ' . mysqli_error($con));
}

$transactions = [];
while ($row = mysqli_fetch_assoc($transactionsResult)) {
    $transactions[] = $row;
}

// ECHO json_encode($transactionsResult);

// Check if the code exists in the database
$codeQuery = "SELECT code  FROM user_codes WHERE user_id = $userId";
$result = mysqli_query($con, $codeQuery);

if (!$result) {
    die('Error fetching codes: ' . mysqli_error($con));
}

$data  = mysqli_fetch_assoc($result);

// echo json_encode($data);

// echo json_encode($data['code']);
// Check if the code is empty
$isCodeEmpty =  $data['code'] == '0' ? true : false;

mysqli_close($con);
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
    </style>
</head>

<body>

    <div class="d-flex">
        <?php include('../components/sidenav.php'); ?>
        <div class=" flex-grow-1 overflow">
            <nav class="px-2 py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">

                    <i class="ri-menu-2-line fs-5 text-secondary"></i>
                    <input type="text" class="px-3 py-2 border-0" placeholder="Search">
                </div>
                <div class="d-flex align-items-center gap-3">
                    <i class="ri-apps-fill"></i>
                    <i class="ri-visa-fill"></i>
                    <i class="ri-bank-card-fill"></i>
                    <a href="" class="text-decoration-none border border-secondary px-3 py-1 text-dark" "><?php echo $_SESSION['name']; ?></a>
                </div>
            </nav>
            <div style=" height: 100%;" class=" py-4 px-4 bg  ">

                        <h1 class="fs-5 lh-1">Welcome to Canara Bank</h1>
                        <p class="fs-6 lh-1">This is your dashboard.</p>
                        <div class="row gap-3 flex-nowrap ">
                            <div class="col-md-3 rounded-3 position-relative d-flex justify-content-center flex-column bg-white px-4 py-3 flex-shrink-1">
                                <p class="lh-1 font">MY BALANCE </p>
                                <h1 class="lh-1 fs-3"><?php echo $currentBalance; ?></h1>
                                <a href="./account_summary.php" class="lh-1">View Details</a>
                                <i class="ri-money-rupee-circle-line icon position-absolute"></i>
                            </div>
                            <div class="col-md-3 rounded-3 position-relative d-flex justify-content-center flex-column bg-white px-4 py-3 flex-shrink-1">
                                <p class="lh-1 font">RECENT DEPOSIT </p>
                                <h1 class="lh-1 fs-4">
                                    <?php
                                    if (!empty($transactions)) {
                                        echo $transactions[0]['transaction_type'] == 'Deposit' ? $transactions[0]['amount'] : 'NO DEPOSIT';
                                    } else {
                                        echo 'NO DEPOSIT';
                                    }
                                    ?>
                                </h1>
                                <a href="./transaction_history.php" class="lh-1">View Details</a>
                                <i class="ri-skip-down-fill icon position-absolute"></i>
                            </div>
                            <div class="col-md-3 rounded-3 position-relative d-flex justify-content-center flex-column bg-white px-4 py-3 flex-shrink-1">
                                <p class="lh-1 font">RECENT WITHDRAWAL </p>
                                <h1 class="lh-1 fs-4">
                                    <?php
                                    if (!empty($transactions)) {
                                        echo $transactions[0]['transaction_type'] == 'Withdrawal' ? $transactions[0]['amount'] : 'No Withdraw';
                                    } else {
                                        echo 'No Withdraw';
                                    }
                                    ?>
                                </h1>
                                <a href="./transaction_history.php" class="lh-1">View Details</a>
                                <i class="ri-arrow-right-up-box-line icon position-absolute"></i>
                            </div>
                            <div class="col-md-3 rounded-3 position-relative d-flex justify-content-center flex-column bg-white px-4 py-3 flex-shrink-1">
                                <p class="lh-1 font">TOTAL BALANCE </p>
                                <h1 class="lh-1 fs-3"><?php echo $currentBalance; ?></h1>
                                <a class="lh-1">View Details</a>
                                <i class="ri-money-rupee-circle-line icon position-absolute"></i>
                            </div>

                        </div>
                </div>


        </div>
    </div>

    <!-- Bootstrap Modal -->
    <?php if ($isCodeEmpty): ?>
        <div class="modal fade" id="codeSetupModal" tabindex="-1" aria-labelledby="codeSetupModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="codeSetupModalLabel">Setup Your Code</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>It seems like you haven't set up your code yet. Please enter the code to proceed.</p>
                        <p>Password should be combination of name and number : john123</p>
                        <form action="../api/setupcode.php" method="post">
                            <div class="mb-3">
                                <label for="user_code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="user_code" name="user_code" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Setup Code</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Show modal if the code is empty -->
    <script>
        <?php if ($isCodeEmpty): ?>
            setTimeout(function() {
                var myModal = new bootstrap.Modal(document.getElementById('codeSetupModal'));
                myModal.show();
            }, 3000);
        <?php endif; ?>
    </script>

</body>

</html>