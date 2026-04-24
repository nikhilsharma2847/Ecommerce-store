<?php
// Configuration File
session_start();

$host = 'localhost';
$username = 'root';
// $password = 'DkP@IJjpee';
$password = '';

// Assuming the database name is the same as the username. 
// If it varies, please update the $dbname variable.
$dbname = 'ecommerce_web';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset('utf8mb4');

// Helper function for sanitizing user inputs
function sanitize($conn, $input) {
    return mysqli_real_escape_string($conn, htmlspecialchars(strip_tags(trim($input))));
}
?>
