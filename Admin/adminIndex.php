<?php
require("../koneksi.php");
include(".././divider/session.php");
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
    $userName = "User";
}

// Mengambil jumlah courses
$query = "SELECT COUNT(*) AS total_courses FROM kelas";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$totalCourses = $row['total_courses'];

// Mengambil jumlah articles
$query = "SELECT COUNT(*) AS total_articles FROM artikel";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$totalArticles = $row['total_articles'];

// Mengambil jumlah orders
$query = "SELECT COUNT(*) AS total_orders FROM transaksi";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$totalOrders = $row['total_orders'];

// Mengambil jumlah users
$query = "SELECT COUNT(*) AS total_users FROM pengguna";
$result = $conn->query($query);
$row = $result->fetch_assoc();
$totalUsers = $row['total_users'];

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentor Panel</title>
    <link rel="stylesheet" href=".././bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href=".././fontawesome/css/all.min.css">
    <link rel="stylesheet" href=".././css/sidebar.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js"></script>
    <style>
        #main-content {
            flex: 1;
            padding: 20px;
        }
    </style>

</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
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
        <!-- END SIDEBAR -->

        <!-- Main Content -->
        <div id="main-content">
            <h1 class="title p-1 fw-bolder">Dashboard</h1>
            <main class="content px-3 py-4">
                <div class="container-fluid">
                    <div class="mb-3">
                        <div class="row">
                            <div class="col-12 col-md-3 ">
                                <div class="card border-0">
                                    <div class="card-body py-4">
                                        <h5 class="mb-2 fw-bold">
                                            Semua Kelas
                                        </h5>
                                        <p class="mb-2 fw-bold">
                                            <?php echo $totalCourses ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3 ">
                                <div class="card  border-0">
                                    <div class="card-body py-4">
                                        <h5 class="mb-2 fw-bold">
                                            Semua Berita
                                        </h5>
                                        <p class="mb-2 fw-bold">
                                            <?php echo $totalArticles ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3 ">
                                <div class="card border-0">
                                    <div class="card-body py-4">
                                        <h5 class="mb-2 fw-bold">
                                            Semua Pesanan
                                        </h5>
                                        <p class="mb-2 fw-bold">
                                            <?php echo $totalOrders ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 col-md-3 ">
                                <div class="card border-0">
                                    <div class="card-body py-4">
                                        <h5 class="mb-2 fw-bold">
                                            Semua Pengguna
                                        </h5>
                                        <p class="mb-2 fw-bold">
                                            <?php echo $totalUsers ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Main Content -->
        </div>
        <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
        <script src=".././bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src=".././fontawesome/js/all.min.js"></script>
        <script src=".././js/sidebar.js"></script>
</body>

</html>