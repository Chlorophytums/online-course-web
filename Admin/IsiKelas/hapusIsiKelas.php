<?php
require("../../koneksi.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $course_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM isi_kelas WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    
    if ($stmt->execute()) {
        header("Location: videos.php");
        exit();
    } else {
        echo "Failed to delete video.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
