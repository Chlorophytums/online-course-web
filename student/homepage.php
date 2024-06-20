<?php
require("../koneksi.php");
include("../divider/session.php");
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../css/homepage.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg main-color">
        <div class="container">
            <a class="navbar-brand" href="#">Techion</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item me-3">
                        <a class="nav-link active" aria-current="page" href="homepage.php">Home</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="articles/article.php">Berita</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="video-content/video-content.php">Kelas</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="forum-discussion/discussion.php">Komunitas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="courses/courses.php">Kelas Saya</a>
                    </li>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="myCourse.php">My course</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $userName ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="../logout.php"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="wrapper">
        <!-- Home Section -->
        <section id="home" class="text-center">
            <img src="https://www.freepik.com/free-vector/recycle-colorful-geometric-gradient-logo-vector_27230675.htm#fromView=search&page=1&position=14&uuid=39edaa9e-ae94-4551-9dcd-39a4016ea80a" class="img-fluid" alt="Web Development">
            <div class="kolom">
                <p class="deskripsi">Pelatihan Daur Ulang Sampah Plastik</p>
                <h2>Membuat Kerajinan untuk Lingkungan Kita !!</h2>
                <p>CRAVIRO adalah platform pelatihan online yang bisa membantu anda menghasilkan berbagai kerajinan dari bahan sampah untuk membantu lingkungan kita</p>
            </div>
        </section>

        <!-- Article and Video Content Section -->
        <section id="articlevideo" class="text-center mt-5">
            <div class="kolom">
                <p class="deskripsi">Ketahui Lebih Banyak Tentang Kami</p>
                <h2>Puluhan Kelas Terbaik DIpersiapkan untuk Anda</h2>
                <p>Craviro menyediakan berbagai fitur yang dapat mempermudah Anda untuk mengetahui berbagai hal terkait pengolahan sampah. Mari menjelajahi dunia pengolahan sampah bersama ribuan orang antusias lainnya !!</p>
                <p><a href="#" class="tbl-biru">Pelajari Lebih Lanjut</a></p>
            </div>
            <img src="https://www.freepik.com/free-vector/female-student-listening-webinar-online_9175118.htm#fromView=search&page=1&position=12&uuid=928738ae-f13b-459b-9c47-4d0c918164bf" class="img-fluid" alt="Online Learning">
        </section>

    </div>

    <footer id="copyright">
        <div class="wrapper">
            &copy; 2024. <b>Techion</b> All Rights Reserved.
        </div>
    </footer>

    <script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../fontawesome/js/all.min.js"></script>
</body>

</html>