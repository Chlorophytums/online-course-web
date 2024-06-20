<?php
require("../../koneksi.php");
include("../../divider/session.php");
checkLoginUser();

// Kode untuk mengambil nama dari database
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

$stmt->close();

$user_id = $_SESSION['user_id'];

// Ambil kursus yang sudah dibeli pengguna
$query_purchased = "SELECT course_id FROM tbl_transaksi WHERE user_id = ? AND status IN ('pending', 'approved')";
$stmt_purchased = $conn->prepare($query_purchased);
$stmt_purchased->bind_param("i", $user_id);
$stmt_purchased->execute();
$result_purchased = $stmt_purchased->get_result();
$purchased_courses = [];
while ($row = $result_purchased->fetch_assoc()) {
    $purchased_courses[] = $row['course_id'];
}
$stmt_purchased->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css">

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

        .text-justify {
            text-align: justify;
        }

        .courses-section {
            padding: 30px 0;
        }

        .courses-section h2 {
            text-align: center;
            font-weight: 600;
            color: #333;
        }

        .card {
            border: none;
            transition: transform 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            margin-bottom: 20px;
        }

        .card-title{
            color: #FF8C00;
        }

        .card:hover {
            transform: translateY(-10px);
        }

        .card-img-top {
            object-fit: cover;
            height: 200px;
            padding: 10px;
            border-radius: 30px 30px 0 0;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 40px 0;
        }

        .footer p {
            margin: 0;
        }

        .carousel-control-prev,
        .carousel-control-next {
            width: auto;
            height: auto;
            border-radius: 20px;
            padding: 30px;
            transition: .2s;
        }

        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            background-color: rgba(0, 0, 0, 0.05);
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            filter: invert(0%);
        }

        .btn-orange{
            background-color: #FF8C00; 
            color: #ffffff; 
            border: none; 
            padding: 10px 20px; 
            text-decoration: none; 
            display: inline-block; 
            border-radius: 5px;
            transition: background-color 0.3s ease; 
        }
        .btn-orange:hover{
            background-color: #e37e02;
            color: #ffffff;
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
                        <a class="nav-link active" aria-current="page" href="../homepage.php">Home</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="../articles/article.php">Article</a>
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
                            <li><a class="dropdown-item" href="../../logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="courses" class="courses-section">
        <div class="container">
            <h2>Courses</h2>
            <hr>
            <p class="fw-light text-center mb-5">Temukan pengetahuan baru dan kembangkan keterampilan Anda dengan berbagai pilihan course kami. Kami menawarkan kursus-kursus terbaik dalam berbagai bidang, dari teknologi hingga bisnis.</p>
            <div id="courseCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    // Query untuk mengambil data courses
                    $query = "SELECT c.*, f.file_path
                                FROM tbl_courses c
                                JOIN file_kelas f ON c.id = f.course_id
                                WHERE f.file_type = 'image'
                                ORDER BY c.created_at DESC";
                    $result = mysqli_query($conn, $query);

                    // Loop untuk menampilkan setiap course dalam grup 3 item
                    if (!$result) {
                        echo '<p>No Courses found.</p>';
                    } else {
                        $active_class = 'active';
                        $course_count = 0;
                        while ($row = mysqli_fetch_assoc($result)) {
                            if ($course_count % 3 == 0) {
                                echo '<div class="carousel-item ' . $active_class . '">';
                                echo '<div class="row">';
                                $active_class = ''; // Hanya set active pada item pertama
                            }

                            $course_id = $row['id'];
                            $title = $row['title'];
                            $price = $row['price'];
                            $label = $row['label'];
                            $image_url = $row['file_path'];

                            echo '<div class="col-md-4">';
                            echo '<div class="card">';
                            echo "<h5 class='card-title fw-normal text-center mt-3 mb-1'>$title</h5>";
                            echo "<img src='$image_url' class='card-img-top' alt='Course Image'>";
                            echo '<div class="card-body">';
                            echo "<p class='card-text text-center' ><strong></strong> IDR. $price</p>";

                            // Tampilkan tombol "Buy Course" hanya jika kursus belum dibeli
                            if (!in_array($course_id, $purchased_courses)) {
                                echo "<div class='d-flex justify-content-center'>
                                        <a href='buy-course.php?id=$course_id' class='btn-orange'>Buy Course</a>
                                    </div>";
                            } else {
                                echo "<div class='d-flex justify-content-center'>
                                        <button class='btn btn-secondary' disabled>Already Purchased</button>
                                    </div>";
                            }

                            echo '</div>'; // Penutup div untuk card-body
                            echo '</div>'; // Penutup div untuk card
                            echo '</div>'; // Penutup div untuk col-md-4

                            $course_count++;
                            if ($course_count % 3 == 0) {
                                echo '</div>'; // Penutup div untuk row
                                echo '</div>'; // Penutup div untuk carousel-item
                            }
                        }

                        // Jika ada sisa card yang tidak mengisi satu grup penuh (3 item), tutup div terakhir
                        if ($course_count % 3 != 0) {
                            echo '</div>'; // Penutup div untuk row
                            echo '</div>'; // Penutup div untuk carousel-item
                        }
                    }
                    ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#courseCarousel" data-bs-slide="prev"><
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#courseCarousel" data-bs-slide="next">>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container text-center">
            <p>&copy; 2024 Techion. All rights reserved.</p>
        </div>
    </footer>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
</body>

</html>