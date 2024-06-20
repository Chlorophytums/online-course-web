<?php
require("../../koneksi.php");
include("../../divider/session.php");
checkLoginAdmin();

$id = $_GET['id'];
$query = "DELETE FROM artikel WHERE id = $id";
if (mysqli_query($conn, $query)) {
    header("Location: articles.php");
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
}
?>