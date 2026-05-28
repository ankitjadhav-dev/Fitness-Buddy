<?php
$error = "";

if (isset($_GET['error'])) {
    if ($_GET['error'] == "empty")  $error = "All fields are required";
    if ($_GET['error'] == "exists") $error = "Email already registered";
    if ($_GET['error'] == "failed") $error = "Registration failed";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Fitness Buddy | Register</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-page">
  <div class="auth-container">
  <img src="assets/images/logo.png">

  <h1>Create Account</h1>

  <?php if ($error != "") { ?>
    <p class="error"><?php echo $error; ?></p>
  <?php } ?>

  <form action="auth/register.php" method="POST">
    <input type="text" name="name" placeholder="Full Name">
    <input type="email" name="email" placeholder="Email address">
    <input type="password" name="password" placeholder="Password">
    <button type="submit">Register</button>
  </form>

  <a href="index.php">Already have an account? Login</a>
</div>

</body>
</html>
