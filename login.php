<?php
session_start();
include "./api/db.php";


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $sql);
    $user = mysqli_fetch_assoc($result);
    if ($user) {

        if ($user['status'] == 'Pending') {
            // echo "Your account is pending. Please wait for approval.";
            $_SESSION['status'] = 'Pending';
            header('Location: ./status.php');
            exit();
        } elseif ($user['status'] == 'Rejected') {
            // echo "Your account is rejected. Please contact support.";
            $_SESSION['status'] = 'Rejected';
            header('Location:./status.php');
            exit();
        } else {
            if (password_verify($password, $user['password'])) {
                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = 'user';
                $_SESSION['name'] = $user['name'];
                header('Location: ./user/dashboard.php');
                exit();
            } else {

                echo "Invalid email or password";
            }
        }
    } else {
        $sql = "SELECT * FROM admins WHERE email = '$email'";
        $result = mysqli_query($con, $sql);
        $admin = mysqli_fetch_assoc($result);
        if ($admin) {
            if (password_verify($password, $admin['password'])) {

                $_SESSION['logged_in'] = true;
                $_SESSION['user_id'] = $admin['admin_id'];
                $_SESSION['role'] = 'admin';
                header('Location: ./admin/dashboard.php');
                exit();
            } else {
                echo "Invalid email or password";
            }
        } else {
            echo "Invalid email or password";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .authBanner {
            background-color: #405189;
            /* Bootstrap primary color */
            height: 100vh;
        }

        .authBanner img {
            max-width: 50%;
            height: auto;
            border-radius: 8px;
        }

        @media (max-width: 768px) {
            .authBanner {
                height: auto;
                padding: 2rem 1rem;
            }
        }

        form {
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <!-- Left Side Banner -->
                <div class="col-md-6 d-flex flex-column p-4 align-items-center justify-content-center authBanner">
                    <img src="https://i.pinimg.com/736x/0e/17/ea/0e17eaf90cfd6de3c7aa52437a2b7ee4.jpg" alt="Banner">
                    <h1 class="text-white text-center fw-bold mt-3">Welcome Back!</h1>
                    <p class="text-white text-center">Login to your account and continue exploring.</p>
                    <p class="text-white">Don’t have an account? <a href="registration.php" class="text-white fw-bold">Register</a></p>
                </div>

                <!-- Right Side Login Form -->
                <div class="col-md-6 d-flex flex-column align-items-center justify-content-center p-5 gap-2">
                    <h2 class="text-center">Login</h2>
                    <form method="POST" class="w-100">
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" style="background-color: #405189;" class="btn text-white">Login</button>
                        </div>
                        <p class="text-center mt-3">
                            <a href="forgot-password.php" class="text-muted">Forgot Password?</a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>