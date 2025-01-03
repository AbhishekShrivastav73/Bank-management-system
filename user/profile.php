<?php
session_start();
include '../api/db.php';

$userId = $_SESSION['user_id'];

// Fetch user details
$query = "SELECT name, address, email, phone FROM users WHERE user_id = $userId";
$result = mysqli_query($con, $query);

$accuntQuery = "SELECT * FROM accounts WHERE user_id = $userId";

$accountResult = mysqli_query($con, $accuntQuery);

if (!$accountResult) {
    die('Error fetching account details: ' . mysqli_error($con));
};

$account = mysqli_fetch_assoc($accountResult);

if (!$result) {
    die('Error fetching user details: ' . mysqli_error($con));
}

$user = mysqli_fetch_assoc($result);

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $address = mysqli_real_escape_string($con, $_POST['address']);

    $updateQuery = "UPDATE users SET 
                    name = '$name', 

                    address = '$address',
                    phone = '$phone' 
                    WHERE user_id = $userId";

    if (mysqli_query($con, $updateQuery)) {
        $successMessage = "Profile updated successfully.";
        // Refresh user details after update
        $result = mysqli_query($con, $query);
        $user = mysqli_fetch_assoc($result);
    } else {
        $errorMessage = "Error updating profile: " . mysqli_error($con);
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .bg {
            background-color: #F3F3F9;
        }

        h1 {
            color: #405189;
            letter-spacing: -1.5px;

            font-weight: 500 !important;
        }

        /* Modal styling */
        .modal-content {
            border-radius: 8px;
        }

        .overflow {
            height: 100vh;
            overflow: auto;
        }

        input {
            background-color: #F3F3F9;
        }
    </style>
</head>

<body class="bg-light">

    <div class="d-flex overflow bg">
        <?php include('../components/sidenav.php'); ?>

        <div class="flex-grow-1 ">
            <nav class="px-2 py-3 d-flex bg-white justify-content-between align-items-center">
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

            <h1 class=" px-4 pt-2">Your Profile</h1>
                        <div class=" p-4 d-flex gap-4 ">




                            <!-- Success/Error Messages -->
                          

                            <!-- Display User Details -->
                            <div class=" ">
                                <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                                <p><strong>Address:</strong> <?php echo htmlspecialchars($user['address']); ?></p>
                                <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                                <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
                                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">Edit Profile</button>
                            </div>
                            <div>
                                <p><strong>Account No:</strong> <?php echo htmlspecialchars($account['account_id']); ?></p>
                                <p><strong>Account Type:</strong> <?php echo htmlspecialchars($account['account_type']); ?></p>
                                <p><strong>Account Balance:</strong> <?php echo htmlspecialchars($account['balance']); ?></p>
                            </div>
                        </div>
                        <?php if (isset($successMessage)): ?>
                                <div class=" alert alert-success"><?php echo $successMessage; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($errorMessage)): ?>
                                <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
                            <?php endif; ?>
                </div>
          

                <!-- Edit Profile Modal -->
                <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form action="profile.php" method="post">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editProfileModalLabel">Edit Profile</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>