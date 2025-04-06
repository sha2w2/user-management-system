<?php
$host = "localhost"; // or "127.0.0.1"
$username = "root"; // default XAMPP user
$password = ""; // default XAMPP password is empty
$database = "test"; // your database name

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully.";
}
?>