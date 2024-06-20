<?php
require_once "../../koneksi.php"; // Sesuaikan dengan lokasi file koneksi.php Anda
include "../../divider/session.php";

// Pastikan pengguna sudah login
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
    $userName = "User";
}

// Ambil data video dari tabel isi_kelas
$query = "SELECT * FROM isi_kelas ORDER BY created_at DESC";
$result = $conn->query($query);

if (!$result) {
    die("Query Error: " . $conn->error);
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Content</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Poppins', sans-serif !important;
            overflow-x: hidden;
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

        .text-justify {
            text-align: justify;
        }

        .wrapper {
            display: flex;
            flex-wrap: nowrap;
        }

        #main-content {
            flex: 1;
            padding: 20px;
        }

        .video-card {
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            transition: transform 0.2s;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .video-card:hover {
            transform: scale(1.01);
            cursor: pointer;
        }

        .video-thumbnail {
            width: 400px;
            height: 200px;
            object-fit: cover;
        }

        .text-justify {
            text-align: justify;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg main-color">
        <div class="container">
            <a class="navbar-brand" href="#">Techion</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item me-3">
                        <a class="nav-link active" aria-current="page" href="../homepage.php">Home</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="../articles/article.php">Article</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="video-content.php">Video Content</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="../forum-discussion/discussion.php">Discussion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../courses/courses.php">Courses</a>
                    </li>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="../courses/myCourse.php">My course</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
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
    <!-- END NAVBAR -->

    <!-- Main Content -->
    <div id="main-content">
        <h1 class="title p-1 fw-bolder">Video Content</h1>

        <div class="row">
            <?php while ($video = $result->fetch_assoc()) : ?>
                <div class="col-md-12 mb-4">
                    <div class="video-card p-3" onclick="window.location.href='watch-video.php?id=<?= $video['id'] ?>'">
                        <video src="<?= $video['video_path'] ?>" class="video-thumbnail me-5" muted loop></video>
                        <div class="video-info ms-4">
                            <h5 class="video-title fw-bolder"><?= htmlspecialchars($video['title']) ?></h5>
                            <p class="video-description fw-light text-justify"><?= htmlspecialchars($video['description']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
    <!-- END Main Content -->

    <!-- Scripts -->
    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script src="../../js/sidebar.js"></script>
</body>

</html>