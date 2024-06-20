<?php
require("../../koneksi.php");

if (isset($_GET['id'])) {
    $video_id = $_GET['id'];

    // Mengambil data video berdasarkan ID
    $stmt = $conn->prepare("SELECT * FROM isi_kelas WHERE id = ?");
    $stmt->bind_param("i", $video_id);
    $stmt->execute();
    $video_result = $stmt->get_result()->fetch_assoc();

    if ($video_result) {
        // Menyiapkan data video untuk ditampilkan
        $title = $video_result['title'];
        $description = $video_result['description'];
        $video_path = $video_result['video_path'];

        // Menampilkan video menggunakan tag HTML5 video
        echo "<h5>Title: {$title}</h5>";
        echo "<p>Description: {$description}</p>";
        echo "<video controls class='img-fluid mb-2' style='max-width: 100%; height: auto;'>
                <source src='{$video_path}' type='video/mp4'>
                Your browser does not support the video tag.
                </video>";
        // echo "<iframe class='embed-responsive-item' src='{$video_path}' allowfullscreen></iframe>";
        // echo "</div>";
    } else {
        echo "Video not found.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
