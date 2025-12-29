<?php
require 'config/db.php';

$sql = file_get_contents('database/migration_otp.sql');

if (mysqli_multi_query($conn, $sql)) {
    do {
        // store first result set
        if ($result = mysqli_store_result($conn)) {
            mysqli_free_result($result);
        }
    } while (mysqli_next_result($conn));
    echo "Migration successful!";
} else {
    echo "Error executing migration: " . mysqli_error($conn);
}
?>
