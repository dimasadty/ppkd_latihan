<?php
$host_connection = "localhost";
$username_connection = "root";
$password_connection = "";
$database_connection = "db_latihanujikom";

try {
    $db_latihanujikom = new PDO("mysql:host=$host_connection;dbname=$database_connection", $username_connection, $password_connection);
    $db_latihanujikom->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>