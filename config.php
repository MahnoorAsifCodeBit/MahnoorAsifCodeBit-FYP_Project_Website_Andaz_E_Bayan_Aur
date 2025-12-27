<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "andazebayan";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$conn->set_charset("utf8mb4");
?>


