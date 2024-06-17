<?php
require("../../koneksi.php");
include("../../middleware/session.php");

// Batasi akses hanya untuk admin
checkLoginMentor();

$limit = 10; // Limit per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query untuk mengambil total message
$total_result = $conn->query("SELECT COUNT(id) AS id FROM tbl_forum_messages");
$total_row = $total_result->fetch_assoc();
$total_messages = $total_row['id'];
$total_pages = ceil($total_messages / $limit);

// Query untuk mengambil data pesan yang pending dari tabel tbl_forum_messages secara descending
$sql = "SELECT fm.*, u.name 
        FROM tbl_forum_messages fm
        JOIN tbl_users u ON fm.user_id = u.id
        ORDER BY fm.created_at DESC LIMIT $start, $limit";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Messages</title>
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

        .status-pending {
            color: #ffc107 !important;
            font-weight: 600;
        }

        .status-approved {
            color: #198754 !important;
            font-weight: 600;
        }

        .status-rejected {
            color: #dc3545 !important;
            font-weight: 600;
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
                    <a href="#">Hello Admin</a>
                </div>
            </div>
            <ul class="sidebar-nav">
                <li class="sidebar-item">
                    <a href="admin_dashboard.php" class="sidebar-link">
                        <i class="fa-solid fa-house me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="admin_articles.php" class="sidebar-link">
                        <i class="fa-solid fa-newspaper me-2"></i>
                        <span>Article</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="admin_videos.php" class="sidebar-link">
                        <i class="fa-solid fa-circle-play me-2"></i>
                        <span>Video Content</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="admin_courses.php" class="sidebar-link">
                        <i class="fa-solid fa-graduation-cap me-2"></i>
                        <span>Course</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="admin_messages.php" class="sidebar-link">
                        <i class="fa-solid fa-envelope me-2"></i>
                        <span>Messages</span>
                    </a>
                </li>
                <li class="sidebar-item">
                    <a href="admin_settings.php" class="sidebar-link">
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
            <h1 class="title p-1 fw-bolder">Messages</h1>
            <!-- BREADCRUMB -->
            <nav class="mb-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active ms-2" style="color: #FF8A08;">
                        <i class="fa-solid fa-envelope me-2"></i>Messages
                    </li>
                </ol>
            </nav>

            <!-- TABEL -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Message</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $start_number = ($page - 1) * $limit + 1;
                        $nomor = $start_number;
                        if ($result->num_rows > 0) : ?>
                            <?php while ($row = $result->fetch_assoc()) : ?>
                                <tr id="message-<?php echo $row['id']; ?>">
                                    <th scope="row"><?php echo $nomor; ?></th>
                                    <td><?php echo $row["name"]; ?></td>
                                    <td><?php echo $row["message"]; ?></td>
                                    <td class="status-cell status-<?php echo ($row["status"]); ?>" data-status="<?php echo $row["status"]; ?>"><?php echo ($row["status"]); ?></td>
                                    <td><?php echo $row["created_at"]; ?></td>
                                    <td>
                                        <button class="btn btn-outline-success btn-sm me-1 approve-message" data-id="<?php echo $row['id']; ?>">Approve</button>
                                        <button class="btn btn-outline-danger btn-sm reject-message" data-id="<?php echo $row['id']; ?>">Reject</button>
                                    </td>
                                </tr>
                                <?php $nomor++; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="6" class="text-center">No pending messages</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- END TABLE -->
            </div>
            <!-- TABLE PAGES -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                        <a class="page-link" href="<?php if ($page > 1) echo "?page=" . ($page - 1);
                                                    else echo '#'; ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                            <a class="page-link" href="admin_messages.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="<?php if ($page < $total_pages) echo "?page=" . ($page + 1);
                                                    else echo '#'; ?>">Next</a>
                    </li>
                </ul>
            </nav>
            <!-- END TABLE PAGES -->
        </div>
        <!-- END Main Content -->
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const approveButtons = document.querySelectorAll('.approve-message');
            const rejectButtons = document.querySelectorAll('.reject-message');

            approveButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const messageId = this.getAttribute('data-id');
                    updateMessageStatus(messageId, 'approved');
                });
            });

            rejectButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const messageId = this.getAttribute('data-id');
                    updateMessageStatus(messageId, 'rejected');
                });
            });

            function updateMessageStatus(id, status) {
                fetch('updateMessageStatus.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: id,
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const messageRow = document.querySelector(`#message-${id}`);
                            const statusCell = messageRow.querySelector('.status-cell');
                            statusCell.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                            statusCell.className = 'status-cell status-' + status.toLowerCase();
                            statusCell.dataset.status = status;

                            // Optionally update button states
                            messageRow.querySelectorAll('button').forEach(button => {
                                button.disabled = true;
                            });
                        } else {
                            alert('Failed to update message status');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            }
        });
    </script>
    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script src="../../js/sidebar.js"></script>
</body>

</html>