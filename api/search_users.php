<?php
include '../api/db.php';

$query = isset($_GET['query']) ? $_GET['query'] : '';
$query = mysqli_real_escape_string($con, $query);

$sql = "SELECT users.user_id, users.name, users.email, accounts.account_id, accounts.account_type, accounts.balance 
        FROM users 
        JOIN accounts 
        ON users.user_id = accounts.user_id";

if ($query !== '') {
    $sql .= " WHERE users.name LIKE '%$query%' 
              OR users.email LIKE '%$query%' 
              OR accounts.account_id LIKE '%$query%' 
              OR accounts.account_type LIKE '%$query%'";
}

$result = mysqli_query($con, $sql);
$data = [];

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

mysqli_free_result($result);
mysqli_close($con);

header('Content-Type: application/json');
echo json_encode($data);
?>
