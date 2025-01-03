<?php
include '../api/db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

$query = isset($_GET['query']) ? $_GET['query'] : '';
$query = mysqli_real_escape_string($con, $query);

$sql = "SELECT * FROM users 
          JOIN pending_accounts 
          ON pending_accounts.user_id = users.user_id 
          WHERE users.status != 'Approved'";

if ($query !== '') {
    $sql = "SELECT * FROM users 
          JOIN pending_accounts 
          ON pending_accounts.user_id = users.user_id 
          WHERE (users.status != 'Approved') 
          AND (users.name LIKE '%$query%' 
          OR pending_accounts.account_type LIKE '%$query%')";
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
