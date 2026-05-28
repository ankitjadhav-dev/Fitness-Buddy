<?php
$error = "";

if (isset($_GET['error'])) {
    if ($_GET['error'] == "empty") {
        $error = "All fields are required";
    }
    if ($_GET['error'] == "invalid") {
        $error = "Invalid email or password";
    }
    if ($_GET['error'] == "notfound") {
        $error = "User not found";
    }
    if (isset($_GET['success']) && $_GET['success'] == "registered") {
        $error = "Registration successful. Please login.";
    }
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>Fitness Buddy | Login</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="auth-page">
  <div class="auth-container">
  <img src="assets/images/logo.png">

  <h1>Fitness Buddy</h1>
  <p>Train smart. Stay consistent.</p>

  <?php if ($error != "") { ?>
  <div class="error-box"><?php echo $error; ?></div>
<?php } ?>


  <form action="auth/login.php" method="POST">
    <input type="email" name="email" placeholder="Email address">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Login</button>
  </form>

  <a href="register.php">New here? Create account</a>
  <a href="auth/forgot_password.php">Forgot Password?</a>
</div>

</body>
</html>
