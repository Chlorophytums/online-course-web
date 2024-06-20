<?php
require("../../koneksi.php");

if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    // Mengambil data kursus
    $stmt = $conn->prepare("SELECT * FROM artikel WHERE id = ?");
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $article_result = $stmt->get_result()->fetch_assoc();

    if ($article_result) {
        echo "<h4 class='text-center mb-2 fw-bolder'>{$article_result['title']}</h4>";
        echo "<p class='text-justify'>{$article_result['content']}</p>";
        
    } else {
        echo "Article not found.";
    }
} else {
    echo "Invalid request.";
}

$conn->close();
?>