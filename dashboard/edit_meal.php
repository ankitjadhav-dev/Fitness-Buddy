<?php
session_start();
include "../config/db.php";

$id = $_POST['id'];
$desc = $_POST['desc'];
$user_id = $_SESSION['user_id'];

$calories = 0;
if (stripos($desc, 'idli') !== false) $calories += 70 * 4;
if (stripos($desc, 'chutney') !== false) $calories += 80;

mysqli_query($conn,
"UPDATE meals SET description='$desc', calories='$calories'
 WHERE id='$id' AND user_id='$user_id'");
echo "success";