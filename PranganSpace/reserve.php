<?php
session_start();
include('db/db_config.php');

$vehicle_no = $_SESSION['vehicle_no'];
$user_query = "SELECT * FROM users WHERE vehicle_no='$vehicle_no'";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();

if ($user['balance'] < 20) {
  echo "Insufficient balance!";
  exit();
}
if (isset($_POST['reserve_slot'])) {
  $slot_id = $_POST['slot_id'];
  $user_id = $_SESSION['vehicle_no'];

  // Reserve the slot
  $reserve_query = "UPDATE slots SET is_reserved = TRUE, reserved_by = $user_id, reservation_time = NOW() 
                    WHERE id = $slot_id AND is_reserved = FALSE";

  if ($conn->query($reserve_query) === TRUE) {
      echo "Slot reserved successfully!";
  } else {
      echo "Error: " . $conn->error;
  }
}

// Fetch available slots for the dropdown
$available_slots_query = "SELECT id FROM slots WHERE is_reserved = FALSE";
$available_slots_result = $conn->query($available_slots_query);
$reserve_query = "UPDATE slots SET is_reserved=1, reserved_by=".$user['id']." WHERE is_reserved=0 LIMIT 1";
$conn->query($reserve_query);

$conn->query("UPDATE users SET balance=balance-20 WHERE id=".$user['id']);

header("Location: index.php")
?>

<form method="POST">
    <select name="slot_id">
        <?php while($slot = $available_slots_result->fetch_assoc()): ?>
            <option value="<?php echo $slot['id']; ?>">Slot <?php echo $slot['id']; ?></option>
        <?php endwhile; ?>
    </select>
    <button type="submit" name="reserve_slot">Reserve Slot</button>
</form>

