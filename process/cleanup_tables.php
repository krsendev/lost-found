<?php
require '../config/db.php';

$tables_to_drop = ['reports', 'posts'];

foreach ($tables_to_drop as $table) {
    $check = mysqli_query($conn, "SHOW TABLES LIKE '$table'");
    if (mysqli_num_rows($check) > 0) {
        $sql = "DROP TABLE $table";
        if (mysqli_query($conn, $sql)) {
            echo "Table '$table' berhasil dihapus.<br>";
        } else {
            echo "Gagal menghapus table '$table': " . mysqli_error($conn) . "<br>";
        }
    } else {
        echo "Table '$table' tidak ditemukan (sudah bersih).<br>";
    }
}

echo "Database cleanup selesai.";
?>
