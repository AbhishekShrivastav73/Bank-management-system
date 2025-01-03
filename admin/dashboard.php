<?php
session_start();

// Validate session
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

include '../api/db.php';

// Fetch user and account data
$query = "SELECT COUNT(users.user_id) AS user_count, SUM(accounts.balance) AS total_balance FROM users JOIN accounts ON users.user_id = accounts.user_id";
$result = mysqli_query($con, $query);
if (!$result) {
    die('Error fetching user and account data: ' . mysqli_error($con));
}
$data = mysqli_fetch_assoc($result);

// Fetch transaction data
$query2 = "SELECT transaction_type, SUM(amount) AS total_amount FROM transactions GROUP BY transaction_type";
$result2 = mysqli_query($con, $query2);
if (!$result2) {
    die('Error fetching transaction data: ' . mysqli_error($con));
}

$transaction = [];
while ($row = mysqli_fetch_assoc($result2)) {
    $transaction[$row['transaction_type']] = $row['total_amount'];
}

// Fetch user counts over time
$query3 = "SELECT DATE(created_at) AS reg_date, COUNT(user_id) AS user_count FROM users GROUP BY DATE(created_at) ORDER BY reg_date ASC";
$result3 = mysqli_query($con, $query3);
if (!$result3) {
    die('Error fetching user registration data: ' . mysqli_error($con));
}

$userData = [];
while ($row = mysqli_fetch_assoc($result3)) {
    $userData[] = $row;
}

$cashinflowQuery = "SELECT SUM(amount) AS total_amount FROM transactions WHERE transaction_type = 'Deposit'";
$cashinflowResult = mysqli_query($con, $cashinflowQuery);

if (!$cashinflowResult) {
    die('Error fetching cash inflow data: '. mysqli_error($con));
}

$cashinflow = mysqli_fetch_assoc($cashinflowResult)['total_amount'];


$cashoutflowQuery = "SELECT SUM(amount) AS total_amount FROM transactions WHERE transaction_type = 'Withdrawal'";

$cashoutflowResult = mysqli_query($con, $cashoutflowQuery);

if (!$cashoutflowResult) {
    die('Error fetching cash outflow data: '. mysqli_error($con));
}

$cashoutflow = mysqli_fetch_assoc($cashoutflowResult)['total_amount'];


mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body,
        .bg {
            background-color: #F3F3F9;
        }

        input {
            background: #F3F3F9;
        }

       nav i {
            font-size: 22px;
            color: rgb(128, 128, 128);
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

        #myChart, #userChart {
            background-color: white;
            padding: 8px 12px;
            border-radius: 8px;
            margin-top: 10px;
            width: 50% !important;
            height: 60vh !important;
        }
        .overflow{
            height: 100vh !important;
            overflow: auto;
            width: 80vw !important;
        }
    </style>
</head>

<body>
    <div class="d-flex h-100 ">
        <?php include('../components/sidenav.php'); ?>
        <div class="flex-grow-1 overflow bg overflow">
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
            <div style="height: 100%;" class="py-4 px-4 bg">
                <h1 class="fs-5 lh-1">Welcome Back, Admin!</h1>
                <p class="fs-6 lh-1">This is your dashboard.</p>
                <div class="row gap-3 flex-nowrap">
                    <div class="col-md-3 rounded-3 position-relative d-flex justify-content-center flex-column bg-white px-4 py-3 flex-shrink-1">
                        <p class="lh-1 font">TOTAL MONEY </p>
                        <h1 class="lh-1 fs-3">₹<?php echo number_format($data['total_balance'], 2); ?></h1>
                        <a class="lh-1">View Details</a>
                        <i class="ri-money-rupee-circle-line icon position-absolute"></i>
                    </div>
                    <div class="col-md-3 rounded-3 position-relative d-flex justify-content-center flex-column bg-white px-4 py-3 flex-shrink-1">
                        <p class="lh-1 font">ACTIVE ACCOUNTS </p>
                        <h1 class="lh-1 fs-4"><?php echo $data['user_count']; ?></h1>
                        <a href="./user_management.php" class="lh-1">View Details</a>
                        <i class="ri-skip-down-fill icon position-absolute"></i>
                    </div>
                    <div class="col-md-3 rounded-3 position-relative d-flex justify-content-center flex-column bg-white px-4 py-3 flex-shrink-1">
                        <p class="lh-1 font">CASH OUT-FLOW </p>
                        <h1 class="lh-1 fs-4">₹<?php echo $cashoutflow ?></h1>
                        <a href="./cashoutflow.php" class="lh-1">View Details</a>
                        <i class="ri-arrow-right-up-line icon position-absolute"></i>
                    </div>
                    <div class="col-md-3 rounded-3 position-relative d-flex justify-content-center flex-column bg-white px-4 py-3 flex-shrink-1">
                        <p class="lh-1 font">CASH IN-FLOW </p>
                        <h1 class="lh-1 fs-4">₹<?php echo $cashinflow ?></h1>
                        <a href="./cashinflow.php" class="lh-1">View Details</a>
                        <i class="ri-arrow-left-down-line icon position-absolute"></i>
                    </div>
                </div>
                <div class="mt-2 gap-3  justify-content-between d-flex">

                    <canvas class=" flex-shrink-1" id="myChart"></canvas>
                    <canvas id="userChart" class=" flex-shrink-1"></canvas>

                </div>
            </div>
        </div>
    </div>

    <script>
        const transactionData = <?php echo json_encode($transaction); ?>;
        const userData = <?php echo json_encode($userData); ?>;


        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: Object.keys(transactionData),
                datasets: [{
                    label: 'Transaction Amount',
                    data: Object.values(transactionData),
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Transactions by Type'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        const userLabels = userData.map(data => data.reg_date);
const userCounts = userData.map(data => data.user_count);

const userCtx = document.getElementById('userChart').getContext('2d');
const userChart = new Chart(userCtx, {
    type: 'line',
    data: {
        labels: userLabels,
        datasets: [{
            label: 'Number of Users',
            data: userCounts,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 2,
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'User Registrations Over Time'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

    </script>
</body>

</html>