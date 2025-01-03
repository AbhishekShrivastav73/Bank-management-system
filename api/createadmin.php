<?php 

include "../api/db.php";

$username = 'admin';
$role = 'admin';
$email = 'admin@gmail.com';
$password = password_hash('123', PASSWORD_DEFAULT);

$sql =  "INSERT INTO admins (name, email, password) VALUES ('$username', '$email', '$password')";

if (mysqli_query($con, $sql)) {
    echo "New user created successfully";
} else {
    echo "Error: ". $sql. "<br>". mysqli_error($con);
}

mysqli_close($con);

?>