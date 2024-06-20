<?php
$conn = mysqli_connect("localhost","root","","db_craviro");

if(mysqli_connect_errno()){
    echo "Gagal terhubung: " . mysqli_connect_errno();
} else {
    //yah
}
?>