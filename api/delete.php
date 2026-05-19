<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
require 'db.php';

$type = $_GET['type'] ?? '';
$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    if ($type == 'user') {
        $conn->query("DELETE FROM users WHERE id=$id");
    } elseif ($type == 'booking') {
        $conn->query("DELETE FROM bookings WHERE id=$id");
    } elseif ($type == 'clinic') {
        $conn->query("DELETE FROM clinics WHERE id=$id");
    }
}

header("Location: dashboard.php");
exit();
?>
