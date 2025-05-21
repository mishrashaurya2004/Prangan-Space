<?php
session_start();
include('db/db_config.php');

$vehicle_no = $_SESSION['vehicle_no'];
$user_query = "SELECT * FROM users WHERE vehicle_no='$vehicle_no'";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Profile - PranganSpace</title>
  <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
  <h2>Your Profile</h2>
  <p>Vehicle No: <?php echo $user['vehicle_no']; ?></p>
  <p>Available Balance: ₹<?php echo $user['balance']; ?></p>

  <form action="add_balance.php" method="POST">
    <input type="number" name="amount" placeholder="Add Balance" required>
    <button type="submit">Add Balance</button>
  </form>

  <a href="index.php">Back to Home</a>
</body>
</html>
