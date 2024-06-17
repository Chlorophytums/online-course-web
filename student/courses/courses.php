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
    // Jika tidak ada data user, handle sesuai kebutuhan
    $userName = "User";
}

$stmt->close();
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
                        <a class="nav-link" href="#">Video Content</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="../forum-discussion/discussion.php">Discussion</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="../courses/courses.php">Courses</a>
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


    <section id="courses" class="courses">
        <div class="container mt-5">
            <h1>Courses</h1>
            <div class="row">
                <?php
                // Query untuk mengambil data courses
                $query = "SELECT c.*, f.file_path
                            FROM tbl_courses c
                            JOIN tbl_course_files f ON c.id = f.course_id
                            WHERE f.file_type = 'image'";
                $result = mysqli_query($conn, $query);

                // Loop untuk menampilkan setiap course
                if (!$result) {
                    echo '<p>No Courses found.</p>';
                } else {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $course_id = $row['id'];
                        $title = $row['title'];
                        $description = $row['description'];
                        $price = $row['price'];
                        $label = $row['label'];
                        $image_url = $row['file_path'];

                        echo '<div class="col-md-4 mb-4">';
                        echo '<div class="card">';
                        echo "<img src='$image_url' class='card-img-top p-4' alt='Course Image' style='height: 200px; width: 200px;'>";
                        echo '<div class="card-body">';
                        echo "<h5 class='card-title fw-bolder'>$title</h5>";
                        echo "<p class='card-text text-justify'>$description</p>";
                        echo "<p class='card-text'><strong>Price: </strong> IDR. $price</p>";
                        echo "<a href='buy-course.php?id=$course_id' class='btn btn-primary'>Buy Course</a>";
                        echo '</div>'; // Penutup div untuk card-body
                        echo '</div>'; // Penutup div untuk card
                        echo '</div>'; // Penutup div untuk col-md-4
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
</body>

</html>