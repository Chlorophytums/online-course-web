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
function addCourse($title, $price, $description, $label, $image, $videos, $conn)
{
    $conn->begin_transaction();

    try {
        // Insert data kursus ke tabel kelas
        $stmt = $conn->prepare("INSERT INTO kelas (title, description, price, label, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssds", $title, $description, $price, $label);
        $stmt->execute();
        $course_id = $stmt->insert_id;

        // Prepare statement untuk menyimpan media (gambar dan video) ke tabel file_kelas
        $stmt_media = $conn->prepare("INSERT INTO file_kelas (course_id, file_path, file_type) VALUES (?, ?, ?)");

        // Proses upload dan menyimpan gambar
        if (!empty($image['tmp_name'])) {
            $image_name = basename($image['name']);
            $image_url = '../../uploads/images/' . $image_name;
            move_uploaded_file($image['tmp_name'], $image_url);
            $media_type = 'image';
            $stmt_media->bind_param("iss", $course_id, $image_url, $media_type);
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
                    $stmt_media->bind_param("iss", $course_id, $video_url, $media_type);
                    $stmt_media->execute();
                }
            }
        }

        $conn->commit();
        return true;
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollback();
        echo "Failed to add course: " . $e->getMessage();
        return false;
    }
}

// Menangani form submit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $label = $_POST['label'];
    $image = isset($_FILES['image']) ? $_FILES['image'] : array();
    $videos = isset($_FILES['videos']) ? $_FILES['videos'] : array();

    $isSuccess = addCourse($title, $price, $description, $label, $image, $videos, $conn);

    if ($isSuccess) {
        $alertType = "success";
        $alertMessage = "Course added successfully!";
    } else {
        $alertType = "failed";
        $alertMessage = "Failed to add course!";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Courses</title>
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
            max-width: 100%;
            max-height: 200px;
            object-fit: cover;
            background-color: #ededed;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            padding: 12px;
            margin-bottom: 10px;
        }
        .course-details {
            padding: 20px; /* Padding untuk memberi ruang antara gambar dan detail kursus */
            text-align: left; /* Menengahkan teks di bawah gambar */
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

        <!-- Main Content -->
        <div id="main-content">
            <h1 class="title p-1 fw-bolder">Add Courses</h1>
            <!-- BREADCRUMB -->
            <nav class="mb-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active ms-2"><a href="courses.php" style="color: inherit; text-decoration: none;">
                            <i class="fa-solid fa-graduation-cap me-2"></i>Courses</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page" style="color: #FF8A08;">Add Courses</li>
                </ol>
            </nav>

            <!-- FORM -->
            <?php if (isset($alertMessage)) : ?>
                <div class="alert alert-<?= $alertType ?> alert-dismissible fade show" role="alert">
                    <?= $alertMessage ?>
                    <button type="button" class="btn-close fs-6" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <form action="addCourses.php" method="POST" enctype="multipart/form-data">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="col-md-6">
                        <label for="price" class="form-label">Price</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="13" required></textarea>
                    </div>
                    <!-- VIDEOS -->
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
                            </div>
                        </div>
                    </div>
                        
                    <div class="col-md-6">
                        <label for="label" class="form-label">Label</label>
                        <select class="form-select" id="label" name="label" required>
                            <option value="paid">Paid</option>
                        </select>
                    </div>
                    <!-- IMAGE -->
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
                            <div id="filewrapper-image"></div>
                        </div>
                    </div>
                </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary" name="submit">Add Course</button>
                    </div>
            </form>
        </div>
        <!-- END Main Content -->
    </div>
    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script src="../../js/sidebar.js"></script>
    <script src="../../js/addCourses.js"></script>
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
</body>

</html>