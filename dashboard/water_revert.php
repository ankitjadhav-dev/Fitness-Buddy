<?php
session_start();
include "../config/db.php";

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

mysqli_query($conn,
"UPDATE water_intake
 SET intake_ml = GREATEST(intake_ml - 200, 0)
 WHERE user_id='$user_id' AND intake_date='$today'");
