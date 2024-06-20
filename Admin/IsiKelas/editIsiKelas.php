<?php
require("../../koneksi.php"); // Sesuaikan dengan lokasi file koneksi.php Anda
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

// Fungsi untuk melakukan update data video
function updateVideo($id, $title, $description, $video_path, $conn)
{
    try {
        // Prepare statement untuk update data video di tabel isi_kelas
        $stmt = $conn->prepare("UPDATE isi_kelas SET title = ?, description = ?, video_path = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $description, $video_path, $id);
        
        // Lakukan binding parameter dan eksekusi statement
        if ($stmt->execute()) {
            return true;
        } else {
            throw new Exception("Failed to update video.");
        }
    } catch (Exception $e) {
        echo "Failed to update video: " . $e->getMessage();
        return false;
    }
}

// Menangani upload video baru jika ada
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    
    // Penanganan upload video baru
    if (isset($_FILES['video']) && $_FILES['video']['error'] === UPLOAD_ERR_OK) {
        $video_file = $_FILES['video'];
        $video_name = basename($video_file['name']);
        $video_target = '../../uploads/video_contents/' . $video_name;
        
        // Memindahkan file ke lokasi yang dituju
        if (move_uploaded_file($video_file['tmp_name'], $video_target)) {
            $video_path = $video_target;
        } else {
            echo "Failed to upload video.";
            exit;
        }
    } else {
        // Jika tidak ada upload video baru, gunakan path yang sudah ada
        $video_path = $_POST['current_video_path']; // Ini membutuhkan tambahan input hidden di form
    }
    
    // Memanggil fungsi updateVideo untuk mengupdate data video
    $isSuccess = updateVideo($id, $title, $description, $video_path, $conn);
    
    if ($isSuccess) {
        $alertType = "success";
        $alertMessage = "Video updated successfully!";
    } else {
        $alertType = "failed";
        $alertMessage = "Failed to update video!";
    }
}

// Ambil data video berdasarkan ID yang di-pass melalui URL
if (isset($_GET['id'])) {
    $video_id = $_GET['id'];
    
    // Ambil data video dari tabel isi_kelas
    $stmt = $conn->prepare("SELECT * FROM isi_kelas WHERE id = ?");
    $stmt->bind_param("i", $video_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $video = $result->fetch_assoc();
    } else {
        echo "Video not found.";
        exit;
    }
} else {
    echo "Video ID is required.";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Video</title>
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
        <!-- END SIDEBAR -->

        <!-- Main Content -->
        <div id="main-content">
            <h1 class="title p-1 fw-bolder">Edit Video</h1>
            <!-- BREADCRUMB -->
            <nav class="mb-4" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="../mentor-panel.php" style="color: #FF8A08;"><i class="fa-solid fa-circle-play me-2"></i>Video Content</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Video</li>
                </ol>
            </nav>

            <!-- ALERT -->
            <?php if (isset($alertMessage)) : ?>
                <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
                    <?= $alertMessage ?>
                    <button type="button" class="btn-close fs-6" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- EDIT FORM -->
            <form action="#" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $video['id'] ?>">
                <input type="hidden" name="current_video_path" value="<?= $video['video_path'] ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= $video['title'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required><?= $video['description'] ?></textarea>
                    </div>
                </div>

                <div class="row mb-3">
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
                                <?php if (!empty($video['video_path'])) : ?>
                                    <div class="showfilebox">
                                        <div class="left">
                                            <span class="filetype">video</span>
                                            <h3><?= basename($video['video_path']) ?></h3>
                                        </div>
                                        <div class="right">
                                            <span class="remove-video" data-video="<?= $video['video_path'] ?>">&#215;</span>
                                        </div>
                                    </div>
                                <?php else : ?>
                                    <h3 class="uploaded">No video uploaded</h3>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary" name="submit">Update Video</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- END Main Content -->
    </div>

    <!-- Scripts -->
    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script src="../../js/sidebar.js"></script>
    <script src="../../js/editVideo.js"></script>
</body>
</html>