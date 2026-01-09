<?php
session_start();
require '../config/db.php';
if (!isset($_SESSION['user'])) { header("Location: ../login.php"); exit; }

$id = (int)$_GET['id'];
$uid = $_SESSION['user']['id'];

$check = mysqli_query($conn, "SELECT id FROM items WHERE id=$id AND user_id=$uid");
if (mysqli_num_rows($check) > 0) {
    mysqli_query($conn, "DELETE FROM items WHERE id=$id AND user_id=$uid");
}

header("Location: ../profile.php");
?>
