<?php
require("../../koneksi.php");
include("../../divider/session.php");
checkLoginUser();

// Kode untuk mengambil nama dari database
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

$stmt->close();

// Mengambil ID course dari URL
$course_id = isset($_GET['id']) ? $_GET['id'] : '';

if (!$course_id) {
    echo "Course ID not provided!";
    exit;
}

// Query untuk mengambil data kursus berdasarkan ID
$query = "SELECT c.*, f.file_path
            FROM kelas c
            JOIN file_kelas f ON c.id = f.course_id
            WHERE c.id = ? AND f.file_type = 'image'";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $title = $row['title'];
    $description = $row['description'];
    $price = $row['price'];
    $label = $row['label'];
    $image_url = $row['file_path'];
} else {
    echo "Course not found!";
    exit;
}

// query untuk upload bukti tf
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id = $_POST['course_id'];
    $payment_proof = $_FILES['payment_proof'];
    $user_id = $_SESSION['user_id'];

    // Direktori tempat menyimpan bukti pembayaran
    $target_dir = "../../uploads/payment_proofs/";
    $target_file = $target_dir . basename($payment_proof["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Memeriksa apakah file adalah gambar atau bukan
    $check = getimagesize($payment_proof["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Memeriksa apakah file sudah ada
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Memeriksa ukuran file
    if ($payment_proof["size"] > 5000000) { // 5MB max
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Memeriksa jenis file
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Memeriksa apakah $uploadOk bernilai 0 karena error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($payment_proof["tmp_name"], $target_file)) {
            // Menyimpan informasi pembayaran ke database
            $query = "INSERT INTO tbl_transaksi (user_id, course_id, payment_proof, status, created_at) VALUES (?, ?, ?, 'pending', NOW())";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("iis", $user_id, $course_id, $target_file);
            if ($stmt->execute()) {
                // Redirect ke halaman My Course
                header("Location: myCourse.php");
                exit;
            } else {
                echo "Sorry, there was an error saving your payment proof.";
            }
            $stmt->close();
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Detail</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Poppins', sans-serif !important;
        }

        .main-color {
            background-image: linear-gradient(to bottom, rgb(255, 138, 8), rgb(255, 101, 0));
        }

        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
            color: white !important;
        }

        .nav-link {
            font-weight: 500 !important;
            color: white !important;
            transition: .3s !important;
        }

        .nav-link:hover {
            scale: .9;
        }

        .text-justify {
            text-align: justify;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg main-color">
        <div class="container">
            <a class="navbar-brand" href="#">Techion</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item me-3">
                        <a class="nav-link active" aria-current="page" href="../homepage.php">Home</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="../articles/article.php">Article</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="../video-content/video-content.php">Video Content</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="../forum-discussion/discussion.php">Discussion</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../courses/courses.php">Courses</a>
                    </li>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false"></a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="../courses/myCourse.php">My course</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $userName ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="../../logout.php"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section id="course-detail" class="course-detail">
        <div class="container mt-4">
            <h1 class="mb-2 p-2">Course Detail</h1>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="row">
                            <div class="col-md-4">
                                <img src="<?php echo $image_url; ?>" class="img-fluid p-4" alt="Course Image" style="height: 400px; width: 400px;">
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold mb-3 mt-2"><?php echo $title; ?></h5>
                                    <p class="card-text text-justify"><?php echo $description; ?></p>
                                    <p class="card-text"><strong>Price: </strong> IDR. <?php echo $price; ?></p>
                                    <button class="btn btn-primary" onclick="showUploadModal(<?php echo $course_id; ?>)">Buy Course</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Modal untuk upload bukti pembayaran -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">Upload Bukti Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 class="fw-bolder text-center">BCA : 123456789</h5>
                    <h6 class="text-center fw-light">or scan QR code below</h6>
                </div>
                <div class="modal-body d-flex justify-content-center align-items-center">
                    <img src="../../img/qr.png" alt="qrcode" style="height: 250px; width: 250px;">
                </div>
                <div class="modal-body">
                    <form id="uploadForm" action="#" method="POST" enctype="multipart/form-data">
                        <input type="hidden" id="courseIdInput" name="course_id">
                        <div class="mb-3">
                            <label for="paymentProof" class="form-label">Bukti Pembayaran</label>
                            <input type="file" class="form-control" id="paymentProof" name="payment_proof" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script>
        function showUploadModal(courseId) {
            var modal = new bootstrap.Modal(document.getElementById('uploadModal'));
            document.getElementById('courseIdInput').value = courseId;
            modal.show();
        }
    </script>
</body>

</html>