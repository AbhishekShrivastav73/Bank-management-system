<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Submitted</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Custom CSS for Theme -->
    <style>
        body {
            background-color: #f8f9fa;
            color: #333;
        }

        .message-container {
            background-color: #405189;
            color: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            /* margin-top:10px; */
        }

        .message-container h1 {
            font-size: 2rem;
            font-weight: bold;
        }

        .message-container p {
            font-size: 1.1rem;
            margin-top: 20px;
        }

        .btn-back {
            background-color: #f8f9fa;
            color: #405189;
            font-weight: bold;
            border: 1px solid #405189;
            margin-top: 30px;
        }

        .btn-back:hover {
            background-color: #405189;
            color: white;
        }
    </style>
</head>

<body>

<div class="container d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="message-container">
        <h1>
            <?php 
            echo $_SESSION['status'] == 'Pending' ? 'Your application is pending.' : 'Your application has been rejected.';
            ?>
        </h1>
        <p>
            <?php 
            echo $_SESSION['status'] == 'Pending' ? 'You will receive an email once your application is approved.' : 'Your account is rejected. Please contact support.';
            ?>
        </p>
        <a href="./login.php" class="btn btn-back">Back to Login</a>
    </div>
</div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>
