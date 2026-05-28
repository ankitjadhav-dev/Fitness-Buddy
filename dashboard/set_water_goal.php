<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) exit;

$user_id = $_SESSION['user_id'];
$goal = intval($_POST['goal']);
$today = date('Y-m-d');

$check = "SELECT * FROM water_intake 
          WHERE user_id='$user_id' AND intake_date='$today'";
$result = mysqli_query($conn, $check);

if (mysqli_num_rows($result) == 0) {
    mysqli_query($conn,
        "INSERT INTO water_intake (user_id, intake_ml, goal_ml, intake_date)
         VALUES ('$user_id', 0, '$goal', '$today')");
} else {
    mysqli_query($conn,
        "UPDATE water_intake 
         SET goal_ml='$goal'
         WHERE user_id='$user_id' AND intake_date='$today')");
}
