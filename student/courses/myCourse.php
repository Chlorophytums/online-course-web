<?php
require("../../koneksi.php");
include("../../middleware/session.php");
checkLoginStudent();

// Kode untuk mengambil nama dari database
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
    $userName = "User";
}

$stmt->close();

$user_id = $_SESSION['user_id'];

// Query untuk mengambil data kursus dengan status pending
$query_pending = "SELECT DISTINCT c.*, t.payment_proof, t.status, f.file_path 
                    FROM tbl_courses c
                    JOIN tbl_transaksi t ON c.id = t.course_id
                    JOIN tbl_course_files f ON c.id = f.course_id
                    WHERE t.user_id = ? AND t.status = 'pending' AND f.file_type = 'image'";
$stmt_pending = $conn->prepare($query_pending);
$stmt_pending->bind_param("i", $user_id);
$stmt_pending->execute();
$result_pending = $stmt_pending->get_result();

// Query untuk mengambil data kursus dengan status approved
$query_approved = "SELECT DISTINCT c.*, t.payment_proof, t.status, f.file_path 
                    FROM tbl_courses c
                    JOIN tbl_transaksi t ON c.id = t.course_id
                    JOIN tbl_course_files f ON c.id = f.course_id
                    WHERE t.user_id = ? AND t.status = 'approved' AND f.file_type = 'image'";
$stmt_approved = $conn->prepare($query_approved);
$stmt_approved->bind_param("i", $user_id);
$stmt_approved->execute();
$result_approved = $stmt_approved->get_result();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses</title>
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
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="../articles/article.php">Article</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="#">Video Content</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Course</a>
                    </li>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="#">My course</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $userName ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="#"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="my-courses" class="my-courses">
        <div class="container mt-5">
            <h1>My Courses</h1>
            <h2>Pending Courses</h2>
            <div class="row">
                <?php
                if ($result_pending->num_rows > 0) {
                    while ($row = $result_pending->fetch_assoc()) {
                        $title = $row['title'];
                        $description = $row['description'];
                        $price = $row['price'];
                        $label = $row['label'];
                        $image_url = isset($row['file_path']) ? $row['file_path'] : 'placeholder.jpg'; // Ubah placeholder.jpg dengan gambar placeholder Anda

                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card">';
                        echo "<img src='$image_url' class='card-img-top p-4' alt='Course Image' style='height: 200px; width: 200px;'>";
                        echo '<div class="card-body">';
                        echo "<h5 class='card-title fw-bolder'>$title</h5>";
                        echo "<p class='card-text text-justify'>$description</p>";
                        echo "<p class='card-text'><strong>Price: </strong> IDR. $price</p>";
                        echo "<p class='card-text'><strong>Status: </strong> Pending</p>";
                        echo '</div>'; // Penutup div untuk card-body
                        echo '</div>'; // Penutup div untuk card
                        echo '</div>'; // Penutup div untuk col-md-4
                    }
                } else {
                    echo '<p>No pending courses found.</p>';
                }
                ?>
            </div>
            <h2>Approved Courses</h2>
            <div class="row">
                <?php
                if ($result_approved->num_rows > 0) {
                    while ($row = $result_approved->fetch_assoc()) {
                        $title = $row['title'];
                        $description = $row['description'];
                        $price = $row['price'];
                        $label = $row['label'];
                        $image_url = isset($row['file_path']) ? $row['file_path'] : 'placeholder.jpg';
                        $course_id = $row['id'];

                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card">';
                        echo "<img src='$image_url' class='card-img-top p-4' alt='Course Image' style='height: 200px; width: 200px;'>";
                        echo '<div class="card-body">';
                        echo "<h5 class='card-title fw-bolder'>$title</h5>";
                        echo "<p class='card-text text-justify'>$description</p>";
                        echo "<p class='card-text'><strong>Price: </strong> IDR. $price</p>";
                        echo "<a href='course_detail.php?id=$course_id' class='btn btn-primary'>Start Learn</a>";
                        echo '</div>'; // Penutup div untuk card-body
                        echo '</div>'; // Penutup div untuk card
                        echo '</div>'; // Penutup div untuk col-md-4
                    }
                } else {
                    echo '<p>No approved courses found.</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
</body>

</html>
