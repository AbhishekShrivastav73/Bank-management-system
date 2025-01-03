<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header('Location: ../login.php');
    exit();
}

include '../api/db.php';

// Fetch all users and their account balances
$query = "SELECT users.user_id, users.name, users.email, accounts.account_id,accounts.account_type, accounts.balance 
          FROM users 
          JOIN accounts 
          ON users.user_id = accounts.user_id";
$result = mysqli_query($con, $query);

$data = [];
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

mysqli_free_result($result);
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - User Accounts</title>
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

        .overflow {
            height: 100vh !important;
            overflow: auto;
            width: 80vw !important;
        }

        body,
        .bg {
            background-color: #F3F3F9;
        }

        input {
            background: #F3F3F9;
        }

        h1 {
            font-size: 20px !important;
        }
        thead tr{
            color: white;
            font-weight: 600;
            background-color: #405189;
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
                <h1 class="lh-1">User accounts</h1>
                <p class="text-secondary lh-1">List of all active users</p>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">A/C No</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Account Type</th>
                            <th scope="col">Avail. Bal</th>
                            <th scope="col">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $user): ?>
                            <tr>
                                <td><?= $user['account_id'] ?></td>
                                <td><?= $user['name'] ?></td>
                                <td><?= $user['email'] ?></td>
                                <td><?= $user['account_type'] ?></td>
                                <td><?= $user['balance'] ?></td>
                                <td>
                                    <a href="user_details.php?user_id=<?= $user['user_id'] ?>">View Details</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
                url: '../api/search_users.php',
                method: 'GET',
                data: { query: query },
                dataType: 'json',
                success: function (data) {
                    console.log(data);
                    tableBody.empty();

                    if (data.length === 0) {
                        tableBody.append('<tr><td colspan="6">No results found</td></tr>');
                        return;
                    }

                    $.each(data, function (index, user) {
                        const row = `
                            <tr>
                                <td>${user.account_id}</td>
                                <td>${user.name}</td>
                                <td>${user.email}</td>
                                <td>${user.account_type}</td>
                                <td>${user.balance}</td>
                                <td><a href="user_details.php?user_id=${user.user_id}">View Details</a></td>
                            </tr>
                        `;
                        tableBody.append(row);
                    });
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching search results:', error);
                    // tableBody.empty();
                    // tableBody.append('<tr><td colspan="6">Error fetching data</td></tr>');
                }
            });
        });
    });
</script>



</body>

</html>