<?php
require("../../koneksi.php");
include("../../middleware/session.php");

// Batasi akses hanya untuk admin
checkLoginMentor();

$limit = 10; // Limit per page
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query untuk mengambil total data transaksi
$total_result = $conn->query("SELECT COUNT(id) AS id FROM tbl_transaksi");
$total_row = $total_result->fetch_assoc();
$total_transactions = $total_row['id'];
$total_pages = ceil($total_transactions / $limit);

// Query untuk mengambil data transaksi dari tabel tbl_transaksi secara descending
$sql = "SELECT t.*, c.title, u.name 
        FROM tbl_transaksi t
        JOIN tbl_courses c ON t.course_id = c.id
        JOIN tbl_users u ON t.user_id = u.id
        ORDER BY t.created_at DESC LIMIT $start, $limit";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Transactions</title>
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

        .text-warning {
            font-weight: 600;
            
        }

        .text-success {
            font-weight: 600;
        }

        .text-danger {
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
                    <a href="admin_transactions.php" class="sidebar-link">
                        <i class="fa-solid fa-cart-shopping me-2"></i>
                        <span>Transactions</span>
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
            <h1 class="title p-1 fw-bolder">Transactions</h1>
            <!-- BREADCRUMB -->
            <nav class="mb-4" style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item active ms-2" style="color: #FF8A08;">
                        <i class="fa-solid fa-cart-shopping me-2"></i>Transactions
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
                            <th>Course</th>
                            <th>Payment Proof</th>
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
                                <tr>
                                    <th scope="row"><?php echo $nomor; ?></th>
                                    <td><?php echo $row["name"]; ?></td>
                                    <td><?php echo $row["title"]; ?></td>
                                    <td>
                                        <img src="<?php echo $row["payment_proof"]; ?>" alt="Payment Proof" style="width: 100px; height: 100px;">
                                    </td>
                                    <td class="<?php echo getStatusClass($row["status"]); ?>"><?php echo $row["status"]; ?></td>
                                    <td><?php echo $row["created_at"]; ?></td>
                                    <td>
                                        <?php if ($row['status'] !== 'approved') : ?>
                                            <button class="btn btn-outline-primary btn-sm me-1 view-details" data-id="<?php echo $row['id']; ?>" data-user="<?php echo $row['name']; ?>" data-course="<?php echo $row['title']; ?>" data-status="<?php echo $row['status']; ?>" data-proof="<?php echo $row['payment_proof']; ?>">
                                                View Details
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $nomor++; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center">No transactions found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <!-- END TABLE -->
                <?php
                // Function to determine status class
                function getStatusClass($status)
                {
                    switch ($status) {
                        case 'pending':
                            return 'text-warning';
                        case 'approved':
                            return 'text-success';
                        case 'rejected':
                            return 'text-danger';
                        default:
                            return '';
                    }
                }
                ?>
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
                            <a class="page-link" href="admin_transactions.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="<?php if ($page < $total_pages) echo "?page=" . ($page + 1);
                                                    else echo '#'; ?>">Next</a>
                    </li>
                </ul>
            </nav>
            <!-- END TABLE PAGES -->
            <!-- Modal -->
            <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="modal-user"></div>
                            <div id="modal-course"></div>
                            <div id="modal-status"></div>
                            <div id="modal-proof" style="height: 450px; width: 450px;"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-danger" onclick="updateStatus('rejected')">Reject</button>
                            <button type="button" class="btn btn-success" onclick="updateStatus('approved')">Approve</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- END Main Content -->
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const viewDetailButtons = document.querySelectorAll('.view-details');

            viewDetailButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const transactionId = this.getAttribute('data-id');
                    const userName = this.getAttribute('data-user');
                    const courseTitle = this.getAttribute('data-course');
                    const status = this.getAttribute('data-status');
                    const paymentProof = this.getAttribute('data-proof');

                    document.getElementById('modal-user').innerHTML = `<p><strong>User:</strong> ${userName}</p>`;
                    document.getElementById('modal-course').innerHTML = `<p><strong>Course:</strong> ${courseTitle}</p>`;
                    document.getElementById('modal-status').innerHTML = `<p><strong>Status:</strong> ${status}</p>`;
                    document.getElementById('modal-proof').innerHTML = `<img src="${paymentProof}" alt="Payment Proof" style="max-width: 100%; height: auto;">`;

                    // Show or hide approve/reject buttons based on status
                    const approveButton = document.querySelector('.btn-success');
                    const rejectButton = document.querySelector('.btn-danger');

                    if (status === 'approved') {
                        approveButton.style.display = 'none';
                        rejectButton.style.display = 'none';
                    } else {
                        approveButton.style.display = 'inline-block';
                        rejectButton.style.display = 'inline-block';
                    }

                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('transactionModal'));
                    modal.show();
                });
            });

            function updateStatus(status) {
                const transactionId = document.getElementById('modal-transaction-id').value;

                fetch('updateTransactionStatus.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: transactionId,
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Failed to update status');
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