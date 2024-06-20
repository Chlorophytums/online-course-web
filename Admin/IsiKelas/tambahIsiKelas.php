<?php
require("../../koneksi.php"); // Sesuaikan dengan lokasi file koneksi.php Anda
include("../../middleware/session.php");
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

function addVideo($title, $video_path, $description, $conn)
{
    try {
        // Insert data video ke tabel isi_kelas
        $stmt = $conn->prepare("INSERT INTO isi_kelas (title, video_path, description, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $title, $video_path, $description);
        
        // Execute statement
        $stmt->execute();

        return true;
    } catch (Exception $e) {
        echo "Failed to add video: " . $e->getMessage();
        return false;
    }
}

// Menangani form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $video_path = ""; // Simpan path video setelah upload di bawah

    // Handle file upload
    if (isset($_FILES['video'])) {
        $video_file = $_FILES['video'];
        $video_name = basename($video_file['name']);
        $video_target = '../../uploads/video_contents/' . $video_name;

        if (move_uploaded_file($video_file['tmp_name'], $video_target)) {
            $video_path = $video_target;
            // Panggil fungsi untuk menambahkan video ke database
            $isSuccess = addVideo($title, $video_path, $description, $conn);

            if ($isSuccess) {
                $alertType = "success";
                $alertMessage = "Video added successfully!";
            } else {
                $alertType = "failed";
                $alertMessage = "Failed to add video!";
            }
        } else {
            $alertType = "failed";
            $alertMessage = "Failed to upload video!";
        }
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Video</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <link rel="stylesheet" href="../../css/addCourses.css">
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

        <!-- Main Content -->
        <div id="main-content">
            <h1 class="title p-1 fw-bolder">Add Video Content</h1>
            <!-- BREADCRUMB -->
            <nav class="mb-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active ms-2"><a href="videos.php" style="color: inherit; text-decoration: none;">
                            <i class="fa-solid fa-circle-play me-2"></i>Video Content</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #FF8A08;">Add Video</li>
                </ol>
            </nav>

            <!-- FORM -->
            <?php if (isset($alertMessage)) : ?>
                <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
                    <?= $alertMessage ?>
                    <button type="button" class="btn-close fs-6" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <form action="addVideo.php" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="col-md-6">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>
                    <div class="col-md-6">
                        <label for="files" class="form-label">Video</label>
                        <div class="box ms-0">
                            <div class="input-box">
                                <h2 class="upload-area-title">Upload Video</h2>
                                <input type="file" id="upload-video" name="video" accept=".mp4" hidden>
                                <label for="upload-video" class="uploadLabel">
                                    <span><i class="fa-solid fa-arrow-up-from-bracket sm"></i></span>
                                    <p>Click to upload</p>
                                </label>
                            </div>
                            <div id="filewrapper-video">
                                <h3 class="uploaded">Uploaded Video</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mb-3">
                    
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary" name="submit">Add Video</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- END Main Content -->
    </div>
    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script src="../../js/sidebar.js"></script>
    <script src="../../js/addVideo.js"></script>
</body>

</html>
