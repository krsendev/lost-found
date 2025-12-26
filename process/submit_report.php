<?php
session_start();
require '../config/db.php';

if (!isset($_SESSION['user'])) {
    die("Akses ditolak");
}

$user_id = $_SESSION['user']['id'];
$type = $_POST['type'];
$item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$date = $_POST['date'];
$location = mysqli_real_escape_string($conn, $_POST['location']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);

$image_path = null;
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
    $target_dir = "../uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        $image_path = $new_filename;
    }
}

$query = "INSERT INTO items (user_id, item_name, description, location, found_date, contact_phone, image, type, status) 
          VALUES ('$user_id', '$item_name', '$description', '$location', '$date', '$phone', '$image_path', '$type', 'available')";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Laporan berhasil dikirim!'); window.location='../index.php';</script>";
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
}
?>
