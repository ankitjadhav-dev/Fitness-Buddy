<?php
session_start();
include "../config/db.php";

if(isset($_POST['password'])){

$email = $_SESSION['reset_email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

mysqli_query($conn,"UPDATE users SET password='$password' WHERE email='$email'");

session_destroy();

echo "Password changed successfully";

}
?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="otp-page">

<div class="auth-container">

<h1>Reset Password</h1>

<form method="POST">

<input type="password" name="password" placeholder="New Password" required>

<button type="submit">Update Password</button>
<a href="../index.php">← Back to Login</a>

</form>

</div>

</div>

</body>
</html>