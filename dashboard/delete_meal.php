<?php
session_start();
include "../config/db.php";

$id = $_POST['id'];
$user_id = $_SESSION['user_id'];

mysqli_query($conn, "DELETE FROM meals WHERE id='$id' AND user_id='$user_id'");
echo "success";
