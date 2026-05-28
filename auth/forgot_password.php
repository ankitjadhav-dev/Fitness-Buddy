<?php
session_start();
include "../config/db.php";

if(isset($_POST['email'])){

$email = $_POST['email'];

$result = mysqli_query($conn,"SELECT * FROM users WHERE email='$email'");

if(mysqli_num_rows($result)>0){

$otp = rand(100000,999999);

$_SESSION['reset_email'] = $email;
$_SESSION['reset_otp'] = $otp;

require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';

$mail = new PHPMailer\PHPMailer\PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'ankit333jadhav@gmail.com';
$mail->Password = 'hjdawunuanudxpos';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('YOUR_GMAIL@gmail.com','Fitness Buddy');
$mail->addAddress($email);

$mail->isHTML(true);
$mail->Subject = "Password Reset OTP";

$mail->Body = "
<h2>Fitness Buddy Password Reset</h2>
<p>Your OTP is:</p>
<h1>$otp</h1>
<p>This OTP expires in 5 minutes</p>
";

$mail->send();

header("Location: verify_reset_otp.php");
exit();

}
else{
echo "Email not registered";
}

}
?>

<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<div class="otp-page">

<div class="auth-container">

<h1>Forgot Password</h1>

<form method="POST">

<input type="email" name="email" placeholder="Enter your email" required>

<button type="submit">Send OTP</button>

</form>

</div>

</div>

</body>
</html>