<?php
require ("../../koneksi.php");
include ("../../divider/session.php");
checkLoginUser();

$email = $_SESSION['email'];
$query = "SELECT name FROM pengguna WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $userName = $row['name'];
} else {
    // Jika tidak ada data user, handle sesuai kebutuhan
    $userName = "User";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Poppins', sans-serif !important;
        }

        .card {
            border: none !important;
        }

        .card-subtitle {
            font-size: 8px;
        }

        .card-title:hover {
            color: tomato !important;
            cursor: pointer !important;
        }

        .btn-orange {
            font-size: 14px;
            background-color: #FF8C00;
            color: #ffffff;
            border: none;
            padding: 10px 10px;
            text-decoration: none;
            display: inline-block;
            border-radius: 10px;
            transition: background-color 0.3s ease;
        }

        .btn-orange:hover {
            background-color: #e37e02;
            color: #ffffff;
        }

        .main-color {
            background-image: linear-gradient(to bottom, rgb(255, 138, 8), rgb(255, 101, 0));
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
        }

        .nav-link {
            font-weight: 500 !important;
            color: white !important;
            transition: .3s !important;
        }

        .nav-link:hover {
            scale: .9;
        }

        .article-header {
            font-weight: 600;
            text-align: center;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 40px 0;
        }

        .footer p {
            margin: 0;
        }

        .article-container {
            max-height: 650px;
            overflow-y: auto;
        }

        .article-card {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg main-color">
        <div class="container">
            <a class="navbar-brand" href="#">Techion</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item me-3">
                        <a class="nav-link active" aria-current="page" href="../homepage.php">Home</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="article.php">Article</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="../video-content/video-content.php">Video Content</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="../forum-discussion/discussion.php">Discussion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../courses/courses.php">Courses</a>
                    </li>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false"></a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="../courses/myCourse.php">My course</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $userName ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="../../logout.php"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container mt-4">
        <h2 class="article-header">Articles</h2>
        <hr>
        <p class="fw-light text-center">Temukan pengetahuan baru dan kembangkan keterampilan Anda dengan berbagai
            pilihan course kami. Kami menawarkan kursus-kursus terbaik dalam berbagai bidang, dari teknologi hingga
            bisnis.</p>
        <div class="row py-4">
            <div class="col-md-4">
                <div class="card">
                    <img src="../../img/5g.png" class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text text-muted">TECHNOLOGY • JUNE 5TH '24</p>
                        <h5 class="card-title">Revolusi 5G: Mengubah Lanskap Komunikasi dan Teknologi di Era Modern</h5>
                        <p class="card-text">Teknologi 5G merupakan evolusi terbaru dalam jaringan seluler yang
                            menjanjikan kecepatan internet lebih tinggi, latensi lebih rendah, dan konektivitas yang
                            lebih andal dibandingkan dengan generasi sebelumnya. Seiring dengan implementasinya di
                            berbagai negara, 5G membawa dampak signifikan pada berbagai sektor, mulai dari komunikasi
                            hingga industri.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-5">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <img src="../../img/applevisionpro.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <p class="card-text text-muted">TECHNOLOGY • JUNE 5TH '24</p>
                                <h5 class="card-title">Apple Vision Pro: Mengubah Paradigma Realitas dengan Teknologi Augmented Reality Terbaru</h5>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <img src="../../img/chatgpt.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <p class="card-text text-muted">TECHNOLOGY • JUNE 5TH '24</p>
                                <h5 class="card-title">ChatGPT: Perkembangan Teknologi dan Dampaknya terhadap Interaksi Manusia-Komputer</h5>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <img src="../../img/iphone.jpg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <p class="card-text text-muted">TECHNOLOGY • JUNE 5TH '24</p>
                                <h5 class="card-title">Masa Depan dalam Genggaman: Perkembangan iPhone 15</h5>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <img src="../../img/iot.jpeg" class="card-img-top" alt="...">
                            <div class="card-body">
                                <p class="card-text text-muted">TECHNOLOGY • JUNE 5TH '24</p>
                                <h5 class="card-title">Melangkah ke Masa Depan: Perkembangan Teknologi Internet of Things (IoT)</h5>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <!-- Scrollable container for articles -->
                <div class="article-container">
                    <?php

                    $email = $_SESSION['email'];
                    $query = "SELECT id, title, DATE_FORMAT(created_at, '%d %b') AS formatted_date, content FROM artikel ORDER BY created_at DESC";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        // Output data of each row
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="card article-card">';
                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $row["title"] . '</h5>';
                            echo '<h6 class="card-subtitle mb-2 text-muted fw-light fs-6">' . $row["formatted_date"] . '</h6>';
                            echo '<p class="card-text">' . substr($row["content"], 0, 100) . '...</p>';
                            echo '<a href="article_details.php?id=' . $row["id"] . '" class="btn-orange">View Detail</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>No articles found.</p>';
                    }
                    $conn->close();
                    ?>
                </div>
            </div>

        </div>
    </div>
    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2024 Techion. All rights reserved.</p>
        </div>
    </footer>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
</body>

</html>