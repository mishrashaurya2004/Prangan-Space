<?php
session_start();
include('db/db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $vehicle_no = $_POST['vehicle_no'];
  $password = $_POST['password'];

  $sql = "SELECT * FROM users WHERE vehicle_no='$vehicle_no' AND password='$password'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $_SESSION['vehicle_no'] = $vehicle_no;
    header("Location: index.php");
  } else {
    echo "Invalid credentials!";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login - PranganSpace</title>
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <div class="login-container">
    <form method="post">
      <h2>Login</h2>
      <input type="text" name="vehicle_no" placeholder="Enter Vehicle No." required>
      <input type="password" name="password" placeholder="Enter Password" required>
      <button type="submit">Login</button>
      
      <!-- Sign up link to redirect to the registration page -->
      <p>Don't have an account? <a href="register.php">Sign up</a></p>
    </form>
  </div>
</body>
</html>
