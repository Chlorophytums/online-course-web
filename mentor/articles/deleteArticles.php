<?php
require("../../koneksi.php");
include("../../middleware/session.php");
checkLoginMentor();

$id = $_GET['id'];
$query = "DELETE FROM tbl_articles WHERE id = $id";
if (mysqli_query($conn, $query)) {
    header("Location: articles.php");
} else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
}
?>