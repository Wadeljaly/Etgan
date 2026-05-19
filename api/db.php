<?php
$servername = "localhost";
$username = "root"; // Update for cPanel
$password = "";     // Update for cPanel
$dbname = "clinic_db"; // Update for cPanel

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
