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

$limit = 5; // Limit per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;
$total_result = $conn->query("SELECT COUNT(id) AS id FROM isi_kelas");
$total_row = $total_result->fetch_assoc();
$total_courses = $total_row['id'];
$total_pages = ceil($total_courses / $limit);

// Ambil data video content dari database
$sql = "SELECT id, title, description, created_at FROM isi_kelas ORDER BY created_at DESC LIMIT ?, ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $start, $limit);
$stmt->execute();
$result = $stmt->get_result();

$videoContents = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $videoContents[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Content</title>
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
            <h1 class="title p-1 fw-bolder">Isi Kelas</h1>

            <!-- TABLE -->
            <div class="text-start mb-3">
                <a href="tambahIsiKelas.php" class="btn btn-success"><i class="fa-solid fa-plus me-2"></i>Tambah Modul</a>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th scope="col">No</th>
                            <th scope="col">Judul</th>
                            <th scope="col">Deskripsi</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $start_number = ($page - 1) * $limit + 1;
                        foreach ($videoContents as $index => $video) : ?>
                            <tr>
                                <th scope="row"><?php echo $start_number + $index; ?></th>
                                <td><?php echo $video['title']; ?></td>
                                <td><?php echo $video['description']; ?></td>
                                <td><?php echo $video['created_at']; ?></td>
                                <td>
                                    <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#videoModal" onclick="viewVideo(<?php echo $video['id']; ?>)">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <a href="editVideo.php?id=<?php echo $video['id']; ?>" class="btn btn-outline-warning btn-sm me-1" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <a href="hapusIsiKelas.php?id=<?php echo $video['id']; ?>" class="btn btn-outline-danger btn-sm me-1" title="Delete" onclick="return confirm('Are you sure you want to delete this course?');">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <nav aria-label="Page navigation example">
                    <ul class="pagination justify-content-center">
                        <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                            <a class="page-link" href="<?php if ($page > 1) echo "?page=" . ($page - 1);
                                                        else echo '#'; ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                            <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                            <a class="page-link" href="<?php if ($page < $total_pages) echo "?page=" . ($page + 1);
                                                        else echo '#'; ?>">Next</a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Modal for Video -->

            <!-- Modal for Video -->
            <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="videoModalLabel">Video Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="videoDetails">
                                <!-- Video details will be populated here dynamically -->
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <!-- END Main Content -->
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script src="../../js/sidebar.js"></script>
    <script>
        function viewVideo(videoId) {
            fetch('detailIsiKelas.php?id=' + videoId)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('videoDetails').innerHTML = data;
                })
                .catch(error => console.error('Error:', error));
        }
    </script>

</body>

</html>