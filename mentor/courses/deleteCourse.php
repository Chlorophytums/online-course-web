<?php
require("../../koneksi.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id'])) {
    $course_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM tbl_courses WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    
    if ($stmt->execute()) {
        header("Location: courses.php");
        exit();
    } else {
        echo "Failed to delete course.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>
