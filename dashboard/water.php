<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['user_id'])) {
    echo "NO_SESSION";
    exit;
}

$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');

$sql = "SELECT intake_ml FROM water_intake 
        WHERE user_id='$user_id' AND intake_date='$today'";
$res = mysqli_query($conn, $sql);

if (!$res) {
    echo "QUERY_ERROR";
    exit;
}

if (mysqli_num_rows($res) == 0) {

    mysqli_query($conn,
        "INSERT INTO water_intake (user_id, intake_ml, goal_ml, intake_date)
         VALUES ('$user_id', 200, 2000, '$today')");

} else {

    mysqli_query($conn,
        "UPDATE water_intake
         SET intake_ml = intake_ml + 200
         WHERE user_id='$user_id' AND intake_date='$today'");
}

echo "success";
