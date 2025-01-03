<?php
$server = "localhost";
$username = "root";
$password = "";
$database = "bankmanagement";

$con = mysqli_connect($server, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>