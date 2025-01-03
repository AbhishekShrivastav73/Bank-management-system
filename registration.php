<?php
session_start();
include './api/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    $account_type = $_POST['account_type'];  // Corrected variable name

    if (!$name || !$email || !$password || !$phone || !$address || !$gender || !$account_type) {
        echo "All fields are required";
        exit();
    }

    // Check if email already exists
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($con, $sql);
    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email already exists'); window.location.href = 'registration.php';</script>";
        exit();
    }

    $sql = "INSERT INTO users (name, email, password, phone, address, gender, status) 
            VALUES ('$name', '$email', '$password', '$phone', '$address', '$gender', 'Pending')";
    $result = mysqli_query($con, $sql);

    // Fetch the last inserted user ID
    $user_id = mysqli_insert_id($con);

    if ($result) {
        $_SESSION['success_message'] = "Registration successful. Please check your email for verification.";

        // Corrected SQL query to insert into pending_accounts
        $pending = "INSERT INTO pending_accounts (user_id, account_type) 
                    VALUES ($user_id, '$account_type')";
        $result_pending = mysqli_query($con, $pending);

        if ($result_pending) {
            $_SESSION['message'] = "Your application has been submitted. You will receive an email once approved. Then you can log in.";
            header('Location: ./sucessfull.php');
            exit();
        } else {
            echo "Error: " . $pending . "<br>" . mysqli_error($con);
            exit();
        }
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>

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
            /* background-color: #f8f9fa; */
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
                    <img src="https://i.pinimg.com/736x/0e/17/ea/0e17eaf90cfd6de3c7aa52437a2b7ee4.jpg" alt="">
                    <h1 class="text-white text-center fw-bold mt-3">Open your Account in a few minutes.</h1>
                    <p class="text-white text-center">Just fill this registration form and we will get back to you</p>
                    <p class="text-white">Already have an account? <a href="login.php" class="text-white fw-bold">Login</a></p>
                </div>

                <!-- Right Side Registration Form -->
                <div class="col-md-6 d-flex flex-column align-items-center justify-content-center p-5 gap-2">
                    <h2 class="text-center">Registration Form</h2>
                    <form  method="POST" class="w-100">
                        <div class="row mb-3">
                            <div class="col-6">


                                <div class="">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control">
                                </div>


                            </div>
                            <div class="col-6">
                                <div class="">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">


                                <div class="">
                                    <label for="name">Password</label>
                                    <input type="password" name="password" id="password" class="form-control">
                                </div>


                            </div>
                            <div class="col-6">
                                <div class="">
                                    <label for="phone">Phone</label>
                                    <input type="phone" name="phone" id="phone" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="">
                                    <label for="gender">Gender</label>
                                    <select class="form-select" name="gender" id="gender">
                                        <option value="male">Male</option>
                                        <option value="female">Female</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="">
                                    <label for="account_type">Account Type</label>
                                    <select class="form-select" name="account_type" id="gender">
                                        <option value="savings">Savings</option>
                                        <option value="current">Current</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="address">Address</label>
                            <input type="text" name="address" id="address" class="form-control">
                        </div>
                        <!-- Submit Button -->
                        <div class="d-grid">
                            <button type="submit" style="background-color: #405189 ;" class="btn text-white">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>