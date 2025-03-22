<?php
$host = "localhost";
$user = "root";
$password = "12345678";
$dbname = "user_system";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

session_start();
?>
