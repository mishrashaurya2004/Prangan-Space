<?php
session_start();
include('db/db_config.php');

if (!isset($_SESSION['vehicle_no'])) {
  header("Location: login.php");
  exit();
}

$vehicle_no = $_SESSION['vehicle_no'];

// Fetch user and slots data
$user_query = "SELECT * FROM users WHERE vehicle_no='$vehicle_no'";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();

$slots_query = "SELECT * FROM slots";
$slots_result = $conn->query($slots_query);

// $available_slots = $conn->query("SELECT COUNT(*) AS available FROM slots WHERE is_reserved=0")->fetch_assoc()['available'];
// $booked_slots = $conn->query("SELECT COUNT(*) AS booked FROM slots WHERE is_reserved=1")->fetch_assoc()['booked'];
$available_slots_query = "SELECT COUNT(*) AS available_count FROM slots WHERE is_reserved = FALSE";
$booked_slots_query = "SELECT COUNT(*) AS booked_count FROM slots WHERE is_reserved = TRUE";

$available_slots_result = $conn->query($available_slots_query);
$booked_slots_result = $conn->query($booked_slots_query);

$available_slots = $available_slots_result->fetch_assoc()['available_count'];
$booked_slots = $booked_slots_result->fetch_assoc()['booked_count'];

$history_query = "SELECT slots.id AS slot_id, slots.reservation_time, users.vehicle_no 
                  FROM slots 
                  JOIN users ON slots.reserved_by = users.id 
                  WHERE slots.reserved_by = '{$_SESSION['vehicle_no']}'";

$history_result = $conn->query($history_query);

?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>PranganSpace</title>
  <link rel="stylesheet" href="assets/css/styles.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
  <nav>
    <div class="logo-brand">
    <img src="assets/img/logo prangan.png" alt="PranganSpace Logo">
    <h5>Prangan Space</h5>
    </div>
    <ul>
      <li><a href="#">About</a></li>
      <li><a href="#">Contact Us</a></li>
      <li><a href="profile.php">Profile</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </nav>

  <div class="container">
    <h1>Welcome to PranganSpace!</h1>
    <p>Available Balance: ₹<?php echo $user['balance']; ?></p>
    <p>Used Balance: ₹<?php echo (1000 - $user['balance']); ?></p>
    <p>Available Slots: <?php echo $available_slots; ?></p>
    <p>Booked Slots: <?php echo $booked_slots; ?></p>


<!-- HTML to Display Slots -->
<div class="slots-section">
    <h4>Available Slots: <?php echo $available_slots; ?></h4>
    <h4>Booked Slots: <?php echo $booked_slots; ?></h4>

    <div class="slots-grid">
        <?php
        // Fetch all slots and display them dynamically
        $slots_query = "SELECT * FROM slots";
        $slots_result = $conn->query($slots_query);

        while($slot = $slots_result->fetch_assoc()) {
            $slot_status = $slot['is_reserved'] ? 'red' : 'green'; // Red for booked, Green for available
            echo "<div class='slot-box' style='background-color: {$slot_status};'>
                    Slot {$slot['id']}
                  </div>";
        }
        ?>
    </div>
</div>

    <form action="reserve.php" method="POST">
      <button type="submit">Reserve Slot</button>
    </form>

    <h3>Reservation History</h3>
    <table>
    <thead>
        <tr>
            <th>S.No</th>
            <th>Vehicle No.</th>
            <th>Slot</th>
            <th>Reservation Time</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $serial_number = 1;
        while($row = $history_result->fetch_assoc()) {
            echo "<tr>
                    <td>{$serial_number}</td>
                    <td>{$row['vehicle_no']}</td>
                    <td>{$row['slot_id']}</td>
                    <td>{$row['reservation_time']}</td>
                  </tr>";
            $serial_number++;
        }
        ?>
    </tbody>
</table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>
