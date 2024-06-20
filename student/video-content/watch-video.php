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

// Ambil data video berdasarkan ID yang di-pass melalui URL
if (isset($_GET['id'])) {
    $video_id = $_GET['id'];

    // Ambil data video dari tabel isi_kelas
    $stmt = $conn->prepare("SELECT * FROM isi_kelas WHERE id = ?");
    $stmt->bind_param("i", $video_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $video = $result->fetch_assoc();
    } else {
        echo "Video not found.";
        exit;
    }
} else {
    echo "Video ID is required.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Watch Video</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/student.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Poppins', sans-serif !important;
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

        .wrapper {
            display: flex;
            flex-wrap: nowrap;
        }

        #main-content {
            flex: 1;
            padding: 20px;
        }

        .video-card {
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .video-wrapper {
            max-width: 100%;
            height: auto;
            overflow: hidden;
        }

        .video-wrapper video {
            width: 100%;
            height: auto;
            display: block;
            object-fit: cover;
        }

        .title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 10px;
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
    <!-- END NavBAR -->

    <!-- Main Content -->
    <div id="main-content">

        <!-- VIDEO CARD -->
        <div class="video-card">
            <div class="video-wrapper">
                <video class="p-4" controls>
                    <source src="<?= $video['video_path'] ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
            <div class="card-details p-4">
                <h2 class="title fw-bolder text-start mb-2"><?= htmlspecialchars($video['title']) ?></h2>
                <p class="text-justify"><?= htmlspecialchars($video['description']) ?></p>
            </div>
        </div>
        <!-- END VIDEO CARD -->
    </div>
    <!-- END Main Content -->


    <!-- Scripts -->
    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script src="../../js/sidebar.js"></script>
</body>

</html>