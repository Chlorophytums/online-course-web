<?php
require("../../koneksi.php");
include("../../middleware/session.php");

$limit = 4; // Limit per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// query total data articles
$total_result = $conn->query("SELECT COUNT(id) AS id FROM tbl_articles");
$total_row = $total_result->fetch_assoc();
$total_articles = $total_row['id'];
$total_pages = ceil($total_articles / $limit);

// Query tbl_articles secara descending
$sql = "SELECT * FROM tbl_articles ORDER BY created_at DESC LIMIT $start, $limit";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Articles</title>
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
            color: darkgrey;
        }

        .page-item.active .page-link {
            background-color: #FF8A08;
            border-radius: 3px;
            border-color: #FF8A08;
        }

        .text-center {
            text-align: center;
        }

        .text-justify {
            text-align: justify;
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
                    <a href="#">Hello User</a>
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
                    <a href="#" class="sidebar-link">
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
                    <a href="courses.php" class="sidebar-link">
                        <i class="fa-solid fa-graduation-cap me-2"></i>
                        <span>Course</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="fa-solid fa-cart-shopping me-2"></i>
                        <span>Order</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="#" class="sidebar-link">
                        <i class="fa-solid fa-gears me-2"></i>
                        <span>Setting</span>
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
            <h1 class="title p-1 fw-bolder">Articles</h1>
            <!-- BREADCRUMB -->
            <nav class="mb-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active ms-2" style="color: #FF8A08;">
                        <i class="fa-solid fa-newspaper me-2"></i>Articles
                    </li>
                </ol>
            </nav>

            <!-- BUTTON -->
            <div class="mb-3">
                <a href="addArticles.php" class="btn btn-success"><i class="fa-solid fa-plus me-2"></i>Add Articles</a>
            </div>

            <!-- TABEL -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>Title</th>
                            <th>Content</th>
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
                                    <td class="text-justify">
                                        <?php echo substr($row["content"], 0, 100); ?>
                                        <?php if (strlen($row["content"]) > 100) : ?>
                                            <span id="dots_<?php echo $row['id']; ?>">...</span>
                                            <span id="more_<?php echo $row['id']; ?>" style="display: none;"><?php echo substr($row["content"], 100); ?></span>
                                            <a href="javascript:void(0);" onclick="toggleContent(<?php echo $row['id']; ?>)" id="read-more-link_<?php echo $row['id']; ?>">View more</a>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo $row["created_at"]; ?></td>
                                    <td>
                                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#articleDetailModal" onclick="viewArticle(<?php echo $row['id']; ?>)">
                                            <i class="fa fa-eye"></i>
                                        </button>
                                        <a href="editArticles.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-warning btn-sm me-1" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <a href="deleteArticles.php?id=<?php echo $row['id']; ?>" class="btn btn-outline-danger btn-sm me-1" title="Delete" onclick="return confirm('Are you sure you want to delete this Article?');">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php $nomor++; ?>
                            <?php endwhile; ?>
                        <?php else : ?>

                            <tr>
                                <td colspan="6" class="text-center">No Articles found</td>
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
                                                } ?>"><a class="page-link" href="articles.php?page=<?php echo $i; ?>"><?php echo $i; ?></a></li>
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
            <div class="modal fade" id="articleDetailModal" tabindex="-1" aria-labelledby="articleDetailModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="articleDetailModalLabel">Article Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body p-5">
                            <!-- Article details will be loaded here via AJAX -->
                            <div id="articleDetailContent"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- END Main Content -->
    </div>

    <script>
        // viewArticle function here
        function viewArticle(articleId) {
            fetch('getArticleDetails.php?id=' + articleId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('articleDetailContent').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
    <script>
    function toggleContent(articleId) {
        var dots = document.getElementById("dots_" + articleId);
        var moreText = document.getElementById("more_" + articleId);
        var btnText = document.getElementById("read-more-link_" + articleId);

        if (dots.style.display === "none") {
            dots.style.display = "inline";
            moreText.style.display = "none";
            btnText.innerHTML = "View more";
        } else {
            dots.style.display = "none";
            moreText.style.display = "inline";
            btnText.innerHTML = "Hide";
        }
    }
</script>
    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script src="../../js/sidebar.js"></script>
</body>

</html>