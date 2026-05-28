<?php
session_start();
include "../config/db.php";

if(isset($_POST['verify'])){

$user_otp = $_POST['otp'];

if($user_otp == $_SESSION['register_otp']){

$name = $_SESSION['register_name'];
$email = $_SESSION['register_email'];
$password = $_SESSION['register_password'];

mysqli_query($conn,
"INSERT INTO users(name,email,password)
VALUES('$name','$email','$password')");

unset($_SESSION['register_otp']);

header("Location: ../index.php");

}
else{

echo "Invalid OTP";

}

}
?>
<html>
    <head>
        <link rel="stylesheet" href="../assets/css/style.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="otp-page">

<div class="auth-container">

  <img src="../assets/images/logo.png" alt="Fitness Buddy">

  <h1>Email Verification</h1>
  <p>Enter the OTP sent to your email</p>

  <form method="POST">
    <input type="text" name="otp" placeholder="Enter 6 digit OTP" maxlength="6" required>
    <button type="submit" name="verify">Verify OTP</button>
  </form>

  <a href="../index.php">← Back to Login</a>

</div>

</div>

</body>
</html>