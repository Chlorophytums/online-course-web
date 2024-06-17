<?php
require("../koneksi.php");
include(".././middleware/session.php");
checkLoginMentor();

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
    <title>Mentor Panel</title>
    <link rel="stylesheet" href=".././bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href=".././fontawesome/css/all.min.css">
    <link rel="stylesheet" href=".././css/sidebar.css">
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
                    <a href="#">Hello <?php echo $userName ?></a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="mentor-panel.php" class="sidebar-link">
                        <i class="fa-solid fa-house me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="articles/articles.php" class="sidebar-link">
                        <i class="fa-solid fa-newspaper me-2"></i>
                        <span>Article</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="fa-solid fa-circle-play me-2"></i>
                        <span>Video Content</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="courses/courses.php" class="sidebar-link">
                        <i class="fa-solid fa-graduation-cap me-2"></i>
                        <span>Course</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="order/orderCourse.php" class="sidebar-link">
                        <i class="fa-solid fa-cart-shopping me-2"></i>
                        <span>Order</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="discussions/messageStudents.php" class="sidebar-link">
                        <i class="fa-solid fa-message me-2"></i></i>
                        <span>Message Students</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="../logout.php" class="sidebar-link">
                    <i class="fa-solid fa-right-from-bracket me-2"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        <!-- END SIDEBAR -->

        <!-- Main Content -->
        <div id="main-content">
        </div>
        <!-- END Main Content -->
    </div>
    <script src=".././bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src=".././fontawesome/js/all.min.js"></script>
    <script src=".././js/sidebar.js"></script>
</body>
</html>