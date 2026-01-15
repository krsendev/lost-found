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
        $message .= "Data POST kosong! Kemungkinan ukuran file melebihi batas post_max_size server. ";
    }
} else {
    if ($_FILES['image']['error'] == 0) {
        $target_dir = "../uploads/";
        
        if (!file_exists($target_dir)) {
            if (!mkdir($target_dir, 0777, true)) {
                die("ERROR KRITIS: Gagal membuat direktori uploads di $target_dir. Periksa izin server."); 
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
                die("ERROR UPLOAD: Gagal memindahkan file yang diupload. Periksa izin direktori 'uploads/'.");
            }
        } else {
            die("ERROR VALIDASI: Format file tidak diizinkan. Hanya JPG, JPEG, PNG, GIF, WEBP.");
        }
    } elseif ($_FILES['image']['error'] != 4) { 
        $uploadErrors = array(
            1 => 'Ukuran file terlalu besar (melebihi batas server).',
            2 => 'Ukuran file terlalu besar (melebihi batas form).',
            3 => 'File hanya terupload sebagian, silakan coba lagi.',
            6 => 'Terjadi kesalahan sistem (folder sementara hilang). Hubungi admin.',
            7 => 'Gagal menyimpan file. Penyimpanan server mungkin penuh.',
            8 => 'Upload dihentikan oleh sistem. Hubungi admin.',
        );
        $errCode = $_FILES['image']['error'];
        die("ERROR UPLOAD (" . $errCode . "): " . ($uploadErrors[$errCode] ?? 'Error tidak diketahui'));
        
    }
}

$query = "INSERT INTO items (user_id, item_name, description, location, found_date, contact_phone, image, type, status) 
        VALUES ('$user_id', '$item_name', '$description', '$location', '$date', '$phone', " . ($image_path ? "'$image_path'" : "NULL") . ", '$type', 'available')";

if (mysqli_query($conn, $query)) {
    echo "<script>alert('Laporan berhasil dikirim! " . $message . "'); window.location='../index.php';</script>";
} else {
    echo "<h1>Error Database</h1>";
    echo "Query: " . $query . "<br>";
    echo "Error: " . mysqli_error($conn);
    exit;
}
?>
