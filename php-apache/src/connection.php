<?php
$host = "mariadb";
$user = "admin";
$pass = "test";

$conn = mysqli_connect($host, $user, $pass);

if (!$conn) {
    echo "Error: Unable to connect to MySQL.\r\n";
    echo "Debugging errno: " . mysqli_connect_errorno() . "<br/>";
    echo "Debugging error: " . mysqli_connect_error() . "<br/>";
    exit;
}
