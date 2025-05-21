<?php
session_start();
include('db/db_config.php');

$vehicle_no = $_SESSION['vehicle_no'];
$amount = $_POST['amount'];

$conn->query("UPDATE users SET balance=balance+$amount WHERE vehicle_no='$vehicle_no'");

header("Location: profile.php");