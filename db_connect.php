<?php
$host = "localhost"; 
$user = "root"; 
$password = "12345678"; 
$dbname = "file_uploads"; 

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
