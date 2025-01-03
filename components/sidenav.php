<?php
// session_start(); // Start the session here

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();

    // Ensure no output has been sent before calling header()
    header('Location: ../login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidenav Example</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />

    <!-- Custom CSS for Sidenav -->
    <style>
        .sidenav {
            height: 100vh;
            width: 20vw;
            background-color: #405189;
            padding-top: 20px;
        }

        .sidenav a {
            color: white;
            padding: 10px 15px;
            margin-left: 12px;
            text-decoration: none;
            font-size: 15px;
            display: block;
        }

        .sidenav a:hover {
            background-color: #575757;
        }

        #btn {
            color: white;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
        }

        #title {
            font-size: 12px;
            color: rgb(193, 193, 193);
            letter-spacing: 0.1px;
        }

        @media (max-width: 768px) {
            .sidenav {
                width: 100%;
                height: auto;
                position: relative;
            }

            .content {
                margin-left: 0;
            }

        }
    </style>
</head>

<body>

    <div class="sidenav d-flex flex-column align-items-center gap-2">
        <img class="w-50 " src="https://i.pinimg.com/736x/0e/17/ea/0e17eaf90cfd6de3c7aa52437a2b7ee4.jpg" alt="">
        <h2 class="text-white text-uppercase fs-5 fw-bold text-center">DIGITAL PALTFORM</h2>
        <div class="w-100 ">
            <p id="title" class="px-4  fw-bold">MENU</p>
            <a href="dashboard.php"><i class="ri-home-2-line p-2"></i>Home</a>
            <a href="profile.php"> <i class="ri-profile-fill p-2"></i>Profile</a>
            <p id="title" class="px-4  fw-bold mt-2">ACCOUNTS MANAGEMENT </p>
            <?php if (isset($_SESSION['role'])): ?>
                <?php if ($_SESSION['role'] == 'admin'): ?>
                    <a href="../admin/accountrequest.php"> <i class="ri-admin-line p-2"></i>Account Approval</a>
                    <a href="user_management.php"><i class="ri-group-line p-2"></i> Account Management</a>
                    <a href="../admin/cashinflow.php"><i class="ri-arrow-left-down-line p-2"></i> Cash In-Flow</a>
                    <a href="../admin/cashoutflow.php"><i class="ri-arrow-right-up-line p-2"></i> Cash Out-Flow</a>
                    <?php elseif ($_SESSION['role'] == 'user'): ?>
                        <a href="account_summary.php"><i class="ri-bank-line p-2"></i> Passbook</a>
                    <a href="transaction_history.php"><i class="ri-history-line p-2"></i> Transaction History</a>
                    <a href="../user/withdraw.php"><i class="ri-history-line p-2"></i> Withdraw</a>
                    <a href="../user/deposit.php"><i class="ri-history-line p-2"></i> Deposit</a>
                <?php endif; ?>
            <?php endif; ?>
            <p id="title" class="px-4 mt-2 fw-bold">SETTINGS</p>
            <a href="settings.php"> <i class="ri-settings-2-line p-2"></i>Settings</a>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <button id="btn" type="submit" name="logout" class="btn  w-50 mt-2"> <i class="ri-logout-box-line p-2"></i>Logout</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>
