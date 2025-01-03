<?php
session_start();
if ($_SESSION['role'] !== 'user') {
    header('Location: ../login.php');
    exit();
}

include '../api/db.php';

$userId = $_SESSION['user_id'];

// Check if the form is submitted and the code is not empty
if (isset($_POST['user_code']) && !empty($_POST['user_code'])) {
    $userCode = mysqli_real_escape_string($con, $_POST['user_code']);
    
    // Check if the code already exists
    $checkQuery = "SELECT code FROM user_codes WHERE user_id = $userId";
    $checkResult = mysqli_query($con, $checkQuery);
    
    if (!$checkResult) {
        die('Error checking existing code: ' . mysqli_error($con));
    }

    if (mysqli_num_rows($checkResult) > 0) {
        // If code exists, update it
        $updateQuery = "UPDATE user_codes SET code = '$userCode' WHERE user_id = $userId";
        if (mysqli_query($con, $updateQuery)) {
            echo "<script>alert('Code updated successfully'); window.location.href = 'profile.php';</script>";
            header('Location: ../user/dashboard.php');
            exit();
        } else {
            echo "<script>alert('Error updating code: " . mysqli_error($con) . "'); window.location.href = 'profile.php';</script>";
            header('Location: ../user/dashboard.php');
            exit();
        }
    } else {
        // If no code exists, insert the new code
        $insertQuery = "INSERT INTO user_codes (user_id, code) VALUES ($userId, '$userCode')";
        if (mysqli_query($con, $insertQuery)) {
            echo "<script>alert('Code set up successfully'); </script>";
            header('Location: ../user/dashboard.php');
            exit();
        } else {
            echo "<script>alert('Error inserting code: " . mysqli_error($con) . "'); window.location.href = 'profile.php';</script>";
        }
    }
} else {
    echo "<script>alert('Code cannot be empty'); window.location.href = 'profile.php';</script>";
}

mysqli_close($con);
?>
