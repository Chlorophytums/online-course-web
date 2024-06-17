<?php
$conn = mysqli_connect("localhost","root","","db_techion");

if(mysqli_connect_errno()){
    echo "Gagal terhubung: " . mysqli_connect_errno();
} else {
    // echo "koneksi berhasil";
}
?>