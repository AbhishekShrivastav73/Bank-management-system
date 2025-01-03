<?php
session_start();
if ($_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit();
}

include '../api/db.php';

$userId = $_SESSION['user_id'];

// Fetch user code from the database
$codeQuery = "SELECT code  FROM user_codes WHERE user_id = $userId";
$result = mysqli_query($con, $codeQuery);

if (!$result) {
    die('Error fetching codes: ' . mysqli_error($con));
}

$data = mysqli_fetch_assoc($result);
$isCodeEmpty = $data['code'] == '0' ? true : false;

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Withdraw Money</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional: Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.12.0/dist/cdn.min.js"></script>
    <style>
        h1{
            color : #405189;
            letter-spacing: -1px;
            font-weight: 400 !important;
        }
    </style>
</head>

<body class="bg-light">
    <div class="d-flex">
        <?php include('../components/sidenav.php'); ?>

        <div class="p-4">
            <h1>Withdraw money from your account</h1>
            <p>Withdraw funds from your account.</p>
            <form action="../api/withdraw.php" method="post">
                <div class="mb-3">
                    <label for="amount" class="form-label">Withdraw Amount</label>
                    <input type="number" class="form-control" id="amount" name="amount" required>
                </div>
                <div class="mb-3">
                    <label for="code" class="form-label">Enter your code</label>
                    <input type="password" class="form-control" id="code" name="code" required>
                </div>
                <button type="submit" class="btn btn-primary">Withdraw</button>
            </form>


        </div>
    </div>

    <!-- Modal for Code Setup -->
    <?php if ($isCodeEmpty): ?>
        <div class="modal fade" id="codeSetupModal" tabindex="-1" aria-labelledby="codeSetupModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="codeSetupModalLabel">Setup Your Code</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>It seems like you haven't set up your code yet. Please enter your code to proceed with transactions.</p>
                        <form action="../api/setupcode.php" method="post">
                            <div class="mb-3">
                                <label for="user_code" class="form-label">Code</label>
                                <input type="text" class="form-control" id="user_code" name="user_code" placeholder="Set a secure code" required>
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
            var myModal = new bootstrap.Modal(document.getElementById('codeSetupModal'));
            myModal.show();
        <?php endif; ?>
    </script>
</body>

</html>