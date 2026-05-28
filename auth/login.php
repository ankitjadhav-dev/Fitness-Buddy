<?php
session_start();
include "../config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if ($email == "" || $password == "") {
        header("Location: ../index.php?error=empty");
        exit;
    }

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];

            header("Location: ../dashboard/dashboard.php");
            exit;
        } else {
            header("Location: ../index.php?error=invalid");
            exit;
        }

    } else {
        header("Location: ../index.php?error=notfound");
        exit;
    }
}
