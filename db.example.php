<?php


$host = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

$db = mysqli_connect($host, $username, $password, $database);

if (!$db) {
    die("Anslutning misslyckades: " . mysqli_connect_error());
}
?>
