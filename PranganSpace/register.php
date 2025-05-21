<?php
include('db/db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $vehicle_no = $_POST['vehicle_no'];
  $password = $_POST['password'];

  // Check if the vehicle number is already registered
  $check_user_query = "SELECT * FROM users WHERE vehicle_no='$vehicle_no'";
  $check_user_result = $conn->query($check_user_query);

  if ($check_user_result->num_rows > 0) {
    echo "Vehicle Number already registered!";
  } else {
    // Register the new user
    $sql = "INSERT INTO users (vehicle_no, password) VALUES ('$vehicle_no', '$password')";

    if ($conn->query($sql) === TRUE) {
      echo "Registration successful!";
      header("Location: login.php");  // Redirect to login after successful registration
      exit();
    } else {
      echo "Error: " . $sql . "<br>" . $conn->error;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Register - PranganSpace</title>
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <div class="register-container">
    <form method="post">
      <h2>Register</h2>
      <input type="text" name="vehicle_no" placeholder="Enter Vehicle No." required>
      <input type="password" name="password" placeholder="Enter Password" required>
      <button type="submit">Register</button>
      
      <!-- Redirect to login page if the user has an account -->
      <p>Already have an account? <a href="login.php">Login</a></p>
    </form>
  </div>
</body>
</html>
