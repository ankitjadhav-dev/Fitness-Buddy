<?php
session_start();
include "../config/db.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/Exception.php';
require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';

$name = $_POST['name'];
$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

$otp = rand(100000,999999);

/* store data temporarily in session */

$_SESSION['register_name'] = $name;
$_SESSION['register_email'] = $email;
$_SESSION['register_password'] = $password;
$_SESSION['register_otp'] = $otp;

$mail = new PHPMailer(true);

try {

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

$mail->Subject = "Fitness Buddy Email Verification";

$mail->Body = "
<div style='font-family:Arial;padding:20px;background:#0f172a;color:white;text-align:center'>

<h2 style='color:#22c55e'>Fitness Buddy</h2>

<p>Welcome to Fitness Buddy 💪</p>

<p>Your verification code is:</p>

<h1 style='background:#22c55e;color:black;padding:10px;border-radius:8px;display:inline-block'>
$otp
</h1>

<p>This code will expire in 5 minutes.</p>

<p style='font-size:12px;color:gray'>
If you didn't request this, please ignore this email.
</p>

</div>
";

$mail->send();

header("Location: verify_otp.php");

}
catch(Exception $e){
    echo "Email failed: " . $mail->ErrorInfo;


}
?>