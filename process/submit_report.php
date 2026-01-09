<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require '../config/db.php';

if (!isset($_SESSION['user'])) {
    die("Akses ditolak: User belum login.");
}

$user_id = $_SESSION['user']['id'];
$type = $_POST['type'];
$item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$date = $_POST['date'];
$location = mysqli_real_escape_string($conn, $_POST['location']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);

$image_path = null;
$message = "";

if (!isset($_FILES['image'])) {
    if (empty($_POST) && $_SERVER['CONTENT_LENGTH'] > 0) {
        $message .= "POST data empty! Possible reasons: post_max_size exceeded. ";
    }
} else {
    if ($_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/";
        
        if (!file_exists($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                die("CRITICAL ERROR: Failed to create uploads directory at $target_dir. Check server permissions."); 
            }
        }
        
        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($file_extension, $allowed)) {
             $new_filename = uniqid() . '.' . $file_extension;
             $target_file = $target_dir . $new_filename;
             
             if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                 $image_path = $new_filename;
             } else {
                 die("UPLOAD ERROR: Failed to move uploaded file to destination. Check directory permissions for 'uploads/'.");
             }
        } else {
            die("VALIDATION ERROR: File format not allowed. Only JPG, JPEG, PNG, GIF, WEBP.");
        }
    } elseif ($_FILES['image']['error'] != 4) { 
        $uploadErrors = array(
            1 => 'The uploaded file exceeds the upload_max_filesize directive in php.ini',
            2 => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
            3 => 'The uploaded file was only partially uploaded',
            6 => 'Missing a temporary folder',
            7 => 'Failed to write file to disk.',
            8 => 'A PHP extension stopped the file upload.',
        );
        $errCode = $_FILES['image']['error'];
        die("UPLOAD ERROR (" . $errCode . "): " . ($uploadErrors[$errCode] ?? 'Unknown error'));
        
    }
}

$query = "INSERT INTO items (user_id, item_name, description, location, found_date, contact_phone, image, type, status) 
          VALUES ('$user_id', '$item_name', '$description', '$location', '$date', '$phone', " . ($image_path ? "'$image_path'" : "NULL") . ", '$type', 'available')";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Laporan berhasil dikirim! " . $message . "'); window.location='../index.php';</script>";
} else {
    echo "<h1>Database Error</h1>";
    echo "Query: " . $query . "<br>";
    echo "Error: " . mysqli_error($conn);
    exit;
}
?>
