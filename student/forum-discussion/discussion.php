<?php
require("../../koneksi.php");
include("../../divider/session.php");
checkLoginUser();

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['user_id'];
    $message = $_POST["message"];

    // Insert message directly with 'approved' status
    $query = "INSERT INTO komunitas (user_id, message, status, created_at) VALUES (?, ?, 'approved', NOW())";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("is", $userId, $message);

    if ($stmt->execute()) {
        $alertType = "success";
        $alertMessage = "Message posted successfully!";
    } else {
        $alertType = "failed";
        $alertMessage = "Failed to post message.";
    }

    $stmt->close();
}

// Fetch all messages
$query = "SELECT fm.id, fm.user_id, fm.message, fm.created_at, u.name 
          FROM komunitas fm
          JOIN pengguna u ON fm.user_id = u.id
          ORDER BY fm.created_at DESC";
$result = $conn->query($query);
$messages = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion Forum</title>
    <link rel="stylesheet" href="../../bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../../css/sidebar.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
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

        .card {
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
<nav class="navbar navbar-expand-lg main-color">
        <div class="container">
            <a class="navbar-brand" href="#">Craviro</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item me-3">
                        <a class="nav-link active" aria-current="page" href="homepage.php">Home</a>
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
                            <li><a class="dropdown-item" href="myCourse.php">My course</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown me-3">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $userName ?>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <li><a class="dropdown-item" href="../logout.php"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- MAIN CONTENT -->
    <div class="container mt-4">
        <!-- Post Form -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Create a Post</h5>
                <form method="POST" action="">
                    <div class="mb-3">
                        <textarea class="form-control" id="postContent" name="message" rows="3" placeholder="What's happening?" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Post</button>
                </form>
                <?php if(isset($alertMessage)): ?>
                    <div class="alert alert-<?php echo $alertType; ?> mt-3">
                        <?php echo $alertMessage; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Posts -->
        <?php foreach ($messages as $message) : ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($message['name']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($message['message']); ?></p>
                    <p class="card-text"><small class="text-muted">Posted on <?php echo date('F j, Y, g:i a', strtotime($message['created_at'])); ?></small></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>

</html>
