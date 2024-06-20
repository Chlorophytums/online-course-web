<?php
require("../../koneksi.php"); // Sesuaikan path koneksi.php dengan kebutuhan Anda
include("../../divider/session.php");
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

// Ambil ID artikel dari parameter URL
if (isset($_GET['id'])) {
    $article_id = $_GET['id'];

    // Query untuk mengambil detail artikel berdasarkan ID
    $query = "SELECT * FROM artikel WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Ambil data artikel
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $content = $row['content'];
        $created_at = $row['created_at'];

        // Format tanggal dari created_at
        $formatted_date = date_format(date_create($created_at), 'd M Y');

        // Tampilkan detail artikel
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Detail - <?php echo $title; ?></title>
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

        .article-detail-header {
            font-weight: 600;
            text-align: center;
            margin-top: 30px;
            margin-bottom: 10px;
        }

        .article-detail-meta {
            font-size: 12px;
            color: #6c757d;
            text-align: center;
            margin-bottom: 30px;
        }

        .article-detail-content {
            margin-top: 20px;
            text-align: justify;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 40px 0;
            margin-top: 20px;
        }

        .footer p {
            margin: 0;
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

    <div class="container mt-2">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <h2 class="article-detail-header"><?php echo $title; ?></h2>
                <p class="article-detail-meta">Published on <?php echo $formatted_date; ?></p>
                <hr>
                <div class="article-detail-content">
                    <p><?php echo $content; ?></p>
                </div>
                <hr>
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
<?php
    } else {
        echo '<div class="container mt-5"><div class="alert alert-danger" role="alert">Article not found.</div></div>';
    }
} else {
    echo '<div class="container mt-5"><div class="alert alert-danger" role="alert">Invalid request. Please select an article.</div></div>';
}
?>
