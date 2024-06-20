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

// Fungsi untuk melakukan update data kursus
function updateCourse($id, $title, $price, $description, $label, $image, $videos, $conn)
{
    $conn->begin_transaction();

    try {
        // Update data kursus di tabel kelas
        $stmt = $conn->prepare("UPDATE kelas SET title = ?, description = ?, price = ?, label = ? WHERE id = ?");
        $stmt->bind_param("ssdsi", $title, $description, $price, $label, $id);
        $stmt->execute();

        // Hapus semua media yang terkait dengan kursus dari tabel file_kelas
        $stmt_delete = $conn->prepare("DELETE FROM file_kelas WHERE course_id = ?");
        $stmt_delete->bind_param("i", $id);
        $stmt_delete->execute();

        // Siapkan statement untuk menyimpan media (video) ke dalam tabel file_kelas
        $stmt_media = $conn->prepare("INSERT INTO file_kelas (course_id, file_path, file_type) VALUES (?, ?, ?)");

        if (!empty($image['tmp_name'])) {
            $image_name = basename($image['name']);
            $image_url = '../../uploads/images/' . $image_name;
            move_uploaded_file($image['tmp_name'], $image_url);
            $media_type = 'image';
            $stmt_media->bind_param("iss", $id, $image_url, $media_type);
            $stmt_media->execute();
        }

        // Proses upload dan menyimpan video
        if (!empty($videos['tmp_name'])) {
            foreach ($videos['tmp_name'] as $key => $video_tmp_name) {
                if (!empty($video_tmp_name)) {
                    $video_name = basename($videos['name'][$key]);
                    $video_url = '../../uploads/videos/' . $video_name;
                    move_uploaded_file($video_tmp_name, $video_url);
                    $media_type = 'video';
                    $stmt_media->bind_param("iss", $id, $video_url, $media_type);
                    $stmt_media->execute();
                }
            }
        }

        $conn->commit();
        return true;
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollback();
        echo "Failed to update course: " . $e->getMessage();
        return false;
    }
}

// Menangani form submit untuk mengupdate data kursus
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $label = $_POST['label'];
    $image = isset($_FILES['image']) ? $_FILES['image'] : array();
    $videos = isset($_FILES['videos']) ? $_FILES['videos'] : array();

    $isSuccess = updateCourse($id, $title, $price, $description, $label, $image, $videos, $conn);

    if ($isSuccess) {
        $alertType = "success";
        $alertMessage = "Course updated successfully!";
    } else {
        $alertType = "failed";
        $alertMessage = "Failed to update course!";
    }
}

// Ambil data kursus berdasarkan ID yang di-pass melalui URL
if (isset($_GET['id'])) {
    $course_id = $_GET['id'];

    // Ambil data kursus dari tabel kelas
    $stmt = $conn->prepare("SELECT * FROM kelas WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $course = $stmt->get_result()->fetch_assoc();

    function getCourseImage($course_id, $conn)
    {
        $stmt = $conn->prepare("SELECT file_path FROM file_kelas WHERE course_id = ? AND file_type = 'image'");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['file_path'];
        } else {
            return null; // Return null jika tidak ada gambar yang ditemukan
        }
    }

    function getCourseVideos($course_id, $conn)
    {
        $videos = array();
        $stmt = $conn->prepare("SELECT file_path FROM file_kelas WHERE course_id = ? AND file_type = 'video'");
        $stmt->bind_param("i", $course_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $videos[] = $row['file_path'];
        }

        return $videos;
    }
    $course_videos = getCourseVideos($course_id, $conn);
    $course_image = getCourseImage($course_id, $conn);
} else {
    echo "Course ID is required.";
    exit;
}

// $conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Course</title>
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

        .preview-item {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            background-color: #ededed;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 12px;
            margin-bottom: 10px;
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
            <h1 class="title p-1 fw-bolder">Edit Course</h1>
            <!-- BREADCRUMB -->
            <nav class="mb-4" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="courses.php" style="color: #FF8A08;"><i class="fa-solid fa-graduation-cap me-2"></i>Courses</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit Course</li>
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
                <input type="hidden" name="id" value="<?= $course['id'] ?>">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= $course['title'] ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" value="<?= $course['price'] ?>" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="13" required><?= $course['description'] ?></textarea>
                    </div>

                    <div class="col-md-6">
                        <label for="files" class="form-label">Videos</label>
                        <div class="box ms-0">
                            <div class="input-box">
                                <h2 class="upload-area-title">Upload Videos</h2>
                                <input type="file" id="upload-videos" name="videos[]" accept=".mp4" multiple hidden>
                                <label for="upload-videos" class="uploadLabel">
                                    <span><i class="fa-solid fa-arrow-up-from-bracket sm"></i></span>
                                    <p>Click to upload</p>
                                </label>
                            </div>
                            <div id="filewrapper-videos">
                                <h3 class="uploaded">Uploaded Videos</h3>
                                <?php foreach ($course_videos as $video_url) : ?>
                                    <div class="showfilebox">
                                        <div class="left">
                                            <span class="filetype">video</span>
                                            <h3><?= basename($video_url) ?></h3>
                                        </div>
                                        <div class="right">
                                            <span class="remove-video" data-video="<?= $video_url ?>">&#215;</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="label" class="form-label">Label</label>
                            <select class="form-select" id="label" name="label" required>
                                <option value="paid" <?= $course['label'] == 'paid' ? 'selected' : '' ?>>Paid</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                        <label for="files" class="form-label mt-2">Logo Course</label>
                        <div class="box ms-0">
                            <div class="input-box">
                                <h2 class="upload-area-title">Upload Image</h2>
                                <input type="file" id="upload-image" name="image" accept=".jpg, .jpeg, .png" hidden>
                                <label for="upload-image" class="uploadLabel">
                                    <span><i class="fa-solid fa-arrow-up-from-bracket sm"></i></span>
                                    <p>Click to upload</p>
                                </label>
                            </div>
                            <div id="filewrapper-image">
                                <?php
                                // Ambil path gambar dari fungsi getCourseImage
                                $course_image_path = getCourseImage($course_id, $conn);
                                $conn->close();
                                // Tampilkan preview gambar jika ada
                                if ($course_image_path) {
                                    echo "<div class='showfilebox'>";
                                    echo "<img src='{$course_image_path}' class='preview-item'>";
                                    echo "</div>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    </div>
                    
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary" name="submit">Update Course</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- END Main Content -->
    </div>


    <!-- Scripts -->
    <script>
        window.addEventListener("load", () => {
            const inputImage = document.getElementById("upload-image");
            const filewrapperImage = document.getElementById("filewrapper-image");

            inputImage.addEventListener("change", (e) => {
                filewrapperImage.innerHTML = ''; // Clear previous previews
                const file = e.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        const previewElement = document.createElement('img');
                        previewElement.classList.add('preview-item');
                        previewElement.src = e.target.result;
                        previewElement.title = file.name;
                        previewElement.alt = file.name;

                        filewrapperImage.appendChild(previewElement);
                    }
                    reader.readAsDataURL(file);
                }
            });
        });
    </script>
    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script src="../../js/sidebar.js"></script>
    <script src="../../js/editCourses.js"></script>
</body>