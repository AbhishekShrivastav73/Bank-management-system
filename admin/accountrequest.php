<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

include '../api/db.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';
require '../phpmailer/src/Exception.php';

// Function to send email
function sendEmail($toEmail, $subject, $body) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'abhisheks.infoseek@gmail.com'; // Replace with your email
        $mail->Password = 'pcibjosjogilugeg'; // Replace with your email password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('abhisheks.infoseek@gmail.com', 'Canara Bank'); // Replace with your email and name
        $mail->addAddress($toEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

// Retrieve the pending users for approval
$users = "SELECT * FROM users 
          JOIN pending_accounts 
          ON pending_accounts.user_id = users.user_id 
          WHERE users.status = 'Pending'";
$result = mysqli_query($con, $users);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Handle the user search

// Handle Approve or Deny actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $userId = $_GET['id'];
    $action = $_GET['action'];

    // Fetch user details for email
    $userQuery = "SELECT email, name FROM users WHERE user_id = $userId";
    $userResult = mysqli_query($con, $userQuery);
    $user = mysqli_fetch_assoc($userResult);

    if ($action == 'approve') {
        $accountTypeQuery = "SELECT account_type FROM pending_accounts WHERE user_id = $userId";
        $accountTypeResult = mysqli_query($con, $accountTypeQuery);

        if ($accountTypeResult && mysqli_num_rows($accountTypeResult) > 0) {
            $accountTypeRow = mysqli_fetch_assoc($accountTypeResult);
            $accountType = $accountTypeRow['account_type'];
            

            $createAccountQuery = "INSERT INTO accounts (user_id, account_type) VALUES ('$userId', '$accountType')";
            if (mysqli_query($con, $createAccountQuery)) {
                $accountId = mysqli_insert_id($con);
                $updateUserQuery = "UPDATE users SET status = 'Approved' WHERE user_id = $userId";
                mysqli_query($con, $updateUserQuery);

                $insertCode = "INSERT INTO user_codes (user_id,account_id) VALUES ('$userId', '$accountId')";
                mysqli_query($con, $insertCode);

                $deletePendingQuery = "DELETE FROM pending_accounts WHERE user_id = $userId";
                mysqli_query($con, $deletePendingQuery);

                // Send Approval Email
                $subject = "Account Approved";
                $body = "Dear {$user['name']},<br>Your account has been approved. Welcome!";
                sendEmail($user['email'], $subject, $body);

                header("Location: ./dashboard.php");
                exit();
            }
        }
    } elseif ($action == 'deny') {
        $updateUserQuery = "UPDATE users SET status = 'Rejected' WHERE user_id = $userId";
        if (mysqli_query($con, $updateUserQuery)) {
            $deletePendingQuery = "DELETE FROM pending_accounts WHERE user_id = $userId";
            mysqli_query($con, $deletePendingQuery);

            // Send Rejection Email
            $subject = "Account Rejected";
            $body = "Dear {$user['name']},<br>We regret to inform you that your account request has been rejected.";
            sendEmail($user['email'], $subject, $body);

            header("Location: ./dashboard.php");
            exit();
        }
    }
}

mysqli_close($con);
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts Management</title>
    <style>
        h1 {
            color: #405189;
            letter-spacing: -1px;
            font-weight: 700 !important;
        }

        .btn {
            background: #405189 !important;
            color: white !important;
        }

        .topnav {
            box-shadow: 1px 1px 5px rgb(198, 198, 198);
            width: 79vw !important;
            position: sticky;
            top: 0;
            z-index: 99;
        }

        .search-box input,
        .search-box button {
            background: #F3F3F9;
        }

        .search-box input {
            padding: 10px 20px;
            border: 1px solid #ddd;
            outline: none;
            width: 300px;
        }

        .search-box button {
            background-color: #405189;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            margin-left: 10px;
        }

        table th,
        table td {
            vertical-align: middle !important;
        }

        .action-links a {
            margin-right: 10px;
            font-weight: 600;
            font-size: 16px;
        }

        .action-links .approve {
            color: #28a745;
        }

        .action-links .deny {
            color: #dc3545;
        }

        .approve {
            background: greenyellow !important;
            color: white !important;
            text-decoration: none !important;
            padding: 4px 12px;
            border-radius: 10px;
        }

        .deny {
            background: red !important;
            color: white !important;
            text-decoration: none !important;
            padding: 4px 12px;
            border-radius: 10px;
        }
        .overflow{
            height: 100vh !important;
            overflow: auto;
            width: 80vw !important;
        }
        body,
        .bg {
            background-color: #F3F3F9;
        }
        input{
            background: #F3F3F9;
        }
        h1{
            font-size: 20px !important; 
        }
        thead tr{
            background: #405189;
            color: white;
            text-align: center;
        }
        tbody {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <?php include('../components/sidenav.php'); ?>
        <div class="flex-grow-1 overflow bg overflow">
            <nav class="px-2 bg-white py-3 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <i class="ri-menu-2-line fs-5 text-secondary"></i>
                    <div class="search-box">
                        <input type="text" placeholder="Search users">
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <i class="ri-apps-fill"></i>
                    <i class="ri-visa-fill"></i>
                    <i class="ri-bank-card-fill"></i>
                </div>
            </nav>
            <div class="p-4">
                <h1>Accounts for Approval</h1>
                <p class="fs-6">Approve or deny accounts for approval</p>
                <?php if (empty($data)) : ?>
                    <p>No accounts for approval</p>
                <?php else : ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Account Type</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data as $user) : ?>
                                <tr>
                                    <td><?= $user['name'] ?></td>
                                    <td><?= $user['email'] ?></td>
                                    <td><?= $user['phone'] ?></td>
                                    <td><?= $user['account_type'] ?></td>
                                    <td class="action-links">
                                        <a href="?action=approve&id=<?= $user['user_id'] ?>" class="approve">Approve</a>
                                        <a href="?action=deny&id=<?= $user['user_id'] ?>" class="deny">Deny</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        const searchInput = $('.search-box input');
        const tableBody = $('table tbody');

        searchInput.on('input', function () {
            const query = searchInput.val().trim();

            $.ajax({
                url: '../api/status_search.php',
                method: 'GET',
                data: { query: query },
                dataType: 'json',
                success: function (data) {
                    tableBody.empty();

                    if (data.length === 0) {
                        tableBody.append('<tr><td colspan="4" class="text-center fw-bold text-primary">No results found</td></tr>');
                        return;
                    }

                    $.each(data, function (index, user) {
                        const row = `
                            <tr>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>${user.account_type}</td>
                                <td class="action-links">
                                    <a href="?action=approve&id=${user.user_id}" class="approve">Approve</a>
                                    <a href="?action=deny&id=${user.user_id}" class="deny">Deny</a>
                                </td>
                            </tr>
                        `;
                        tableBody.append(row);
                    });
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching search results:', error);
                    tableBody.empty();
                    tableBody.append('<tr><td colspan="4">Error fetching data</td></tr>');
                }
            });
        });
    });
</script>
</body>

</html>
