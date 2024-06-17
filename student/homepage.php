<?php
require("../koneksi.php");
include("../middleware/session.php");
checkLoginStudent();

$email = $_SESSION['email'];
$query = "SELECT name FROM tbl_users WHERE email = ?";
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
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');
        body{
            font-family: 'Poppins', sans-serif !important;
        }

        .main-color{
            background-image: linear-gradient(to bottom, rgb(255, 138, 8), rgb(255, 101, 0));
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
        }
        .nav-link{
            font-weight: 500 !important;
            color: white !important;
            transition: .3s !important;
        }

        .nav-link:hover{
            scale: .9;
        }
        
        .wrapper {
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }

        .kolom {
            margin-top: 20px;
        }

        .kolom h2 {
            margin-top: 10px;
        }

        .tbl-pink,
        .tbl-biru {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .tbl-pink {
            background-color: #e91e63;
        }

        .tbl-biru {
            background-color: #007bff;
        }

        .courses-list {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 20px;
        }

        .kartu-course {
            flex: 1 1 calc(25% - 20px);
            border: 1px solid #ddd;
            border-radius: 10px;
            overflow: hidden;
            text-align: center;
            background-color: #f8f9fa;
        }

        .kartu-course img {
            width: 100%;
            height: auto;
        }

        .kartu-course p {
            padding: 10px;
        }

        #copyright {
            background-color: #343a40;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
    </style>
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
                        <a class="nav-link" href="articles/article.php">Article</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="#">Video Content</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="forum-discussion/discussion.php">Discussion</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="courses/courses.php">Courses</a>
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
            <img src="https://img.freepik.com/free-vector/web-development-programmer-engineering-coding-website-augmented-reality-interface-screens-developer-project-engineer-programming-software-application-design-cartoon-illustration_107791-3863.jpg?size=626&ext=jpg&ga=GA1.2.1769275626.1605867161" class="img-fluid" alt="Web Development">
            <div class="kolom">
                <p class="deskripsi">Edukasi dan Pelatihan Teknologi</p>
                <h2>Kembangkan Skill dan Pengetahuan Anda</h2>
                <p>Techion adalah platform komprehensif yang menyediakan berbagai kursus online interaktif untuk meningkatkan keterampilan teknologi pengguna. Fitur-fiturnya meliputi artikel terkait, konten video, kursus online, dan forum komunitas.</p>
                <p><a href="#" class="tbl-pink">Pelajari Lebih Lanjut</a></p>
            </div>
        </section>

        <!-- Article and Video Content Section -->
        <section id="articlevideo" class="text-center mt-5">
            <div class="kolom">
                <p class="deskripsi">You Will Need This</p>
                <h2>Articles and Video Content</h2>
                <p>Menyediakan fitur artikel yang mencakup beragam topik mulai dari tren industri terbaru hingga panduan mendalam tentang teknologi spesifik, ditulis oleh pakar untuk memperkaya pengetahuan pengguna.</p>
                <p>Video konten meliputi tutorial langkah-demi-langkah, webinar interaktif, studi kasus nyata, penjelasan konsep, wawancara dengan ahli, review produk teknologi, latihan coding, dan penugasan praktis, semuanya dirancang untuk memberikan pembelajaran yang komprehensif dan aplikatif.</p>
                <p><a href="#" class="tbl-biru">Pelajari Lebih Lanjut</a></p>
            </div>
            <img src="https://img.freepik.com/free-vector/online-learning-isometric-concept_1284-17947.jpg?size=626&ext=jpg&ga=GA1.2.1769275626.1605867161" class="img-fluid" alt="Online Learning">
        </section>

        <!-- Top Courses Section -->
        <section id="course" class="text-center mt-5">
            <div class="tengah">
                <div class="kolom">
                    <p class="deskripsi">Our Top Courses</p>
                    <h2>Courses</h2>
                    <p> Menawarkan konten multimedia interaktif oleh mentor berpengalaman dan sertifikasi digital yang dapat diunduh setelah menyelesaikan kursus, serta akses mobile dan offline untuk belajar kapan saja dan di mana saja.</p>
                </div>
                <div class="courses-list">
                    <div class="kartu-course">
                        <img src="../img/pemweb.jpg" alt="Pemrograman Web">
                        <p>Pemrograman Web</p>
                    </div>
                    <div class="kartu-course">
                        <img src="../img/pemmob.png" alt="Pemrograman Mobile">
                        <p>Pemrograman Mobile</p>
                    </div>
                    <div class="kartu-course">
                        <img src="../img/dataanalis.jpg" alt="Data Analis">
                        <p>Data Analis</p>
                    </div>
                    <div class="kartu-course">
                        <img src="../img/uiux.jpg" alt="UI/UX">
                        <p>UI/UX</p>
                    </div>
                </div>
            </div>
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