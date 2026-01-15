<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit;
}

if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];
    $uid = $_SESSION['user']['id'];

    $check = mysqli_query($conn, "SELECT id FROM items WHERE id=$id AND user_id=$uid");
    
    if (mysqli_num_rows($check) > 0) {
        if ($action == 'mark_found') {
            $query = "UPDATE items SET status='claimed' WHERE id=$id";
            if (mysqli_query($conn, $query)) {
            }
        }
    }
}

header("Location: ../profile.php");
exit;
?>
