<?php
require("../../koneksi.php");

if (isset($_GET['id'])) {
    $course_id = $_GET['id'];

    // Mengambil data kursus
    $stmt = $conn->prepare("SELECT * FROM tbl_courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $course_result = $stmt->get_result()->fetch_assoc();

    // Mengambil data media
    $stmt_media = $conn->prepare("SELECT * FROM tbl_course_files WHERE course_id = ?");
    $stmt_media->bind_param("i", $course_id);
    $stmt_media->execute();
    $media_result = $stmt_media->get_result();

    if ($course_result) {
        // Menampilkan gambar pertama sebagai logo kursus
        $logo_displayed = false;
        while ($media_row = $media_result->fetch_assoc()) {
            if ($media_row['file_type'] == 'image' && !$logo_displayed) {
                echo "<h4 style='text-align: center; font-weight: bold;'>Logo course</h4>";
                echo "<div style='text-align: center; margin-bottom: 10px;'>";
                echo "<img src='{$media_row['file_path']}' class='img-fluid mb-2' style='width: 200px; height: 200px;'>";
                echo "</div>";
            }
        }

        // Menampilkan informasi kursus
        echo "<div class='course-details'>";
        echo "<h4>{$course_result['title']}</h4>";
        echo "<p>{$course_result['description']}</p>";
        echo "<p>Price: \${$course_result['price']}</p>";
        echo "<p>Label: {$course_result['label']}</p>";
        echo "</div>";

        echo "<h5>Media</h5>";
        // Mengulangi hasil media untuk menampilkan video (jika ada)
        $media_result->data_seek(0); // Kembalikan kursor hasil untuk digunakan kembali
        while ($media_row = $media_result->fetch_assoc()) {
            if ($media_row['file_type'] == 'video') {
                echo "<video controls class='img-fluid mb-2' style='max-width: 100%; height: auto;'>
                <source src='{$media_row['file_path']}' type='video/mp4'>
                Your browser does not support the video tag.
                </video>";
            }
        }
    } else {
        echo "Course not found.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
