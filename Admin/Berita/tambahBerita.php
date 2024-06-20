<?php
require("../../koneksi.php");
include("../../divider/session.php");
checkLoginAdmin();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST["title"];
    $content = $_POST["content"];

    $sql = "INSERT INTO artikel (title, content) VALUES ('$title', '$content')";

    if ($conn->query($sql) === TRUE) {
        $alertType = "success";
        $alertMessage = "Article added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
        $alertType = "failed";
        $alertMessage = "Failed to add Article!";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Template</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../css/sidebar.css">

    <style>
        body {
            overflow-x: hidden;

        }

        .wrapper {
            display: flex;
            flex-wrap: nowrap;
        }

        #main-content {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>

<body>
    <div class="wrapper">

    <aside id="sidebar">
            <div class="d-flex">
                <button class="toggle-btn" type="button">
                    <i class="fa-solid fa-list" style="color: #ffffff;"></i>
                </button>
                <div class="sidebar-logo">
                    <a href="#">Halo <?php echo $userName ?></a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="../adminIndex.php" class="sidebar-link">
                        <i class="fa-solid fa-house me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../Berita/berita.php" class="sidebar-link">
                        <i class="fa-solid fa-newspaper me-2"></i>
                        <span>Berita</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../IsiKelas/IsiKelas.php" class="sidebar-link">
                        <i class="fa-solid fa-circle-play me-2"></i>
                        <span>isi Kelas</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../Kelas/kelas.php" class="sidebar-link">
                        <i class="fa-solid fa-graduation-cap me-2"></i>
                        <span>Kelas</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../pesan/transaksiKelas.php" class="sidebar-link">
                        <i class="fa-solid fa-cart-shopping me-2"></i>
                        <span>Pesanan</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="../logout.php" class="sidebar-link">
                    <i class="fa-solid fa-right-from-bracket me-2"></i>
                    <span>Log Out</span>
                </a>
            </div>
        </aside>

        <!-- MAIN CONTENT -->
        <div id="main-content">
            <h1 class="title p-1 fw-bolder">Add Article</h1>
            <nav class="mb-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active ms-2"><a href="articles.php" style="color: inherit; text-decoration: none;">
                        <i class="fa-solid fa-newspaper me-2"></i>Articles</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #FF8A08;">Add Articles</li>
                </ol>
            </nav>
            <?php if (isset($alertMessage)) : ?>
                <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
                    <?= $alertMessage ?>
                    <button type="button" class="btn-close fs-6" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <div class="container mt-5">
                <form method="POST" action="addArticles.php">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="content" class="form-label">Content</label>
                        <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Article</button>
                </form>
            </div>
        </div>
    </div>



    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script src="../../js/sidebar.js"></script>
</body>

</html>