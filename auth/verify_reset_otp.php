<?php
session_start();

if(isset($_POST['otp'])){

if($_POST['otp'] == $_SESSION['reset_otp']){

header("Location: reset_password.php");
exit();

}
else{
echo "Invalid OTP";
}

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

<h1>Verify OTP</h1>

<form method="POST">

<input type="text" name="otp" placeholder="Enter OTP" required>

<button type="submit">Verify OTP</button>

</form>

</div>

</div>

</body>
</html>