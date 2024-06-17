<?php
require("../../koneksi.php");
include("../../middleware/session.php");

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

$limit = 5; // Limit per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query untuk mengambil total data kursus
$total_result = $conn->query("SELECT COUNT(id) AS id FROM tbl_courses");
$total_row = $total_result->fetch_assoc();
$total_courses = $total_row['id'];
$total_pages = ceil($total_courses / $limit);

// Query untuk mengambil data kursus dari tabel tbl_courses secara descending
$sql = "SELECT * FROM tbl_courses ORDER BY created_at DESC LIMIT $start, $limit";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
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

        .page-link {
            color:darkgrey;
        }

        .page-item.active .page-link {
            background-color: #FF8A08;
            border-radius: 3px;
            border-color: #FF8A08;
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
                    <a href="#">Hello <?php echo $userName ?></a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="../mentor-panel.php" class="sidebar-link">
                        <i class="fa-solid fa-house me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="articles.php" class="sidebar-link">
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
                    <a href="../courses/courses.php" class="sidebar-link">
                        <i class="fa-solid fa-graduation-cap me-2"></i>
                        <span>Course</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../order/orderCourse.php" class="sidebar-link">
                        <i class="fa-solid fa-cart-shopping me-2"></i>
                        <span>Order</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="../discussions/messageStudents.php" class="sidebar-link">
                        <i class="fa-solid fa-message me-2"></i></i>
                        <span>Message Students</span>
                    </a>
                </li>
            </ul>
            <div class="sidebar-footer">
                <a href="../../logout.php" class="sidebar-link">
                    <i class="fa-solid fa-right-from-bracket me-2"></i>
                    <span>Logout</span>
                </a>
            </div>
        </aside>
        <!-- END SIDEBAR -->

        <!-- Main Content -->
        <div id="main-content">
            <h1 class="title p-1 fw-bolder">Courses</h1>
            <!-- BREADCRUMB -->
            <nav class="mb-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active ms-2" style="color: #FF8A08;">
                        <i class="fa-solid fa-graduation-cap me-2"></i>Courses
                    </li>
                </ol>
            </nav>

            <!-- BUTTON -->
            <div class="mb-3">
                <a href="addCourses.php" class="btn btn-success"><i class="fa-solid fa-plus me-2"></i>Add Course</a>
            </div>

            <!-- TABEL -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Label</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $start_number = ($page - 1) * $limit + 1;
                            $nomor = $start_number;
                        if ($result->num_rows > 0) : ?>
                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <tr>
                                    <th scope="row"><?php echo $nomor; ?></th>
                                    <td><?php echo $row["title"]; ?></td>
                                    <td><?php echo $row["description"]; ?></td>
                                    <td><?php echo $row["price"]; ?></td>
                                    <td><?php echo $row["label"]; ?></td>
                                    <td><?php echo $row["created_at"]; ?></td>
                                    <td>
                                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#courseDetailModal" onclick="viewCourse(<?php echo $row['id']; ?>)">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <a href="editCourse.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-warning btn-sm me-1" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="deleteCourse.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm me-1" title="Delete" onclick="return confirm('Are you sure you want to delete this course?');">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php $nomor++; ?>
                            <?php endwhile; ?>
                        <?php else : ?>

                            <tr>
                                <td colspan="6" class="text-center">No courses found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- TABLES PAGES -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if ($page <= 1) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link" href="<?php if ($page > 1) {
                                                        echo "?page=" . ($page - 1);
                                                    } else {
                                                        echo '#';
                                                    } ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) {
                                                    echo 'active';
                                                } ?>"><a class="page-link" href="courses.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                    <li class="page-item <?php if ($page >= $total_pages) {
                                                echo 'disabled';
                                            } ?>">
                        <a class="page-link" href="<?php if ($page < $total_pages) {
                                                        echo "?page=" . ($page + 1);
                                                    } else {
                                                        echo '#';
                                                    } ?>">Next</a>
                    </li>
                </ul>
            </nav>
            <!-- END TABLE PAGES -->

            <!-- POP UP MODAL -->
            <div class="modal fade" id="courseDetailModal" tabindex="-1" aria-labelledby="courseDetailModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="courseDetailModalLabel">Course Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Course details will be loaded here via AJAX -->
                            <div id="courseDetailContent"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- END Main Content -->
    </div>
    <script>
        // Include the viewCourse function here
        function viewCourse(courseId) {
            fetch('getCourseDetails.php?id=' + courseId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('courseDetailContent').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script src="../../js/sidebar.js"></script>
</body>

</html>