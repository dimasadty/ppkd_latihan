<?php
include 'config/config.php'; // Ensure this path is correct

if (isset($db_latihanujikom)) {
    echo "PDO connection successful!";
} else {
    echo "PDO connection failed!";
}
?>