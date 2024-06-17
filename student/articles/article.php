<?php
require("../../koneksi.php");
include("../../middleware/session.php");
checkLoginStudent();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css">

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
                    <a class="nav-link" href="article.php">Article</a>
                </li>
                <li class="nav-item me-3">
                    <a class="nav-link" href="#">Video Content</a>
                </li>
                <li class="nav-item me-3">
                    <a class="nav-link" href="courses.php">Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../login.php"><i class="fa-solid fa-right-to-bracket"></i>
                    Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
    <div class="container mt-5">
        <h1 class="mb-4">Articles</h1>
        <div class="row">
            <?php
            $sql = "SELECT * FROM tbl_articles ORDER BY created_at DESC";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row["title"] . '</h5>';
                    echo '<p class="card-text">' . substr($row["content"], 0, 100) . '...</p>';
                    echo '<a href="article_detail.php?id=' . $row["id"] . '" class="btn btn-primary">Read More</a>';
                    echo '</div>';
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

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
</body>

</html>