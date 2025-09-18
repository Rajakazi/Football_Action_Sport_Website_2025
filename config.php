<?php
$host = "localhost";
$user = "root";
$pass = "Milan@1234";
$dbname = "football_action";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}
?>
