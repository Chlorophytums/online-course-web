<?php
require("../../koneksi.php");
include("../../divider/session.php");

// Batasi akses hanya untuk admin
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
    $userName = "User";
}

$limit = 10; // Limit per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Query untuk mengambil total data transaksi
$total_result = $conn->query("SELECT COUNT(id) AS id FROM transaksi");
$total_row = $total_result->fetch_assoc();
$total_transactions = $total_row['id'];
$total_pages = ceil($total_transactions / $limit);

// Query untuk mengambil data transaksi dari tabel transaksi secara descending
$sql = "SELECT t.*, k.title, u.name 
        FROM transaksi t
        JOIN kelas k ON t.course_id = k.id
        JOIN pengguna u ON t.user_id = u.id
        ORDER BY t.created_at DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $start, $limit);
$stmt->execute();
$result = $stmt->get_result();
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
            color: #FF0000;
            font-weight: 600;
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
                    <a href="#">Halo <?php echo htmlspecialchars($userName); ?></a>
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
                        <span>Isi Kelas</span>
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
            <h1 class="title p-1 fw-bolder">Transaksi</h1>
            <!-- TABEL -->
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Kelas</th>
                            <th>Bukti Transaksi</th>
                            <th>Status</th>
                            <th>Dibuat</th>
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
                                    <td><?php echo htmlspecialchars($row["name"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["title"]); ?></td>
                                    <td>
                                        <img src="<?php echo htmlspecialchars($row["payment_proof"]); ?>" alt="Payment Proof" style="width: 100px; height: 100px;">
                                    </td>
                                    <td class="<?php echo getStatusClass($row["status"]); ?>"><?php echo htmlspecialchars($row["status"]); ?></td>
                                    <td><?php echo htmlspecialchars($row["created_at"]); ?></td>
                                    <td>
                                        <?php if ($row['status'] !== 'approved') : ?>
                                            <button class="btn btn-outline-primary btn-sm me-1 view-details" data-id="<?php echo $row['id']; ?>" data-user="<?php echo htmlspecialchars($row['name']); ?>" data-course="<?php echo htmlspecialchars($row['title']); ?>" data-status="<?php echo htmlspecialchars($row['status']); ?>" data-proof="<?php echo htmlspecialchars($row['payment_proof']); ?>">
                                                Lihat Detail
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php $nomor++; ?>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="7" class="text-center">Tidak Ada Transaksi</td>
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
                        <a class="page-link" href="<?php if ($page > 1) echo "?page=" . ($page - 1); else echo '#'; ?>">Sebelumnya</a>
                    </li>
                    <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                        <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                            <a class="page-link" href="transaksiKelas.php?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?php if ($page >= $total_pages) echo 'disabled'; ?>">
                        <a class="page-link" href="<?php if ($page < $total_pages) echo "?page=" . ($page + 1); else echo '#'; ?>">Selanjutnya</a>
                    </li>
                </ul>
            </nav>
            <!-- END TABLE PAGES -->
            <!-- Modal -->
            <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="transactionModalLabel">Detail Transaksi</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="modal-user"></div>
                            <div id="modal-course"></div>
                            <div id="modal-status"></div>
                            <div id="modal-proof"></div>
                            <input type="hidden" id="modal-transaction-id"> <!-- Hidden input for transaction ID -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" onclick="updateStatus('rejected')">Ditolak</button>
                            <button type="button" class="btn btn-success" onclick="updateStatus('approved')">Verifikasi</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Modal -->
        </div>
        <!-- END MAIN CONTENT -->
    </div>

    <script src="../../bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../fontawesome/js/all.min.js"></script>
    <script src="../../js/sidebar.js"></script>
    <script>
        function updateStatus(status) {
            const transactionId = document.getElementById('modal-transaction-id').value;

            fetch('updateStatusTransaksi.php', {
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
                    document.getElementById('modal-transaction-id').value = transactionId; // Set transaction ID

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
        });
    </script>
</body>

</html>
