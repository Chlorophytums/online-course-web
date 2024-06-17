<?php
require("../../koneksi.php");
include("../../middleware/session.php");

// Batasi akses hanya untuk admin
checkLoginMentor();

// Mengambil data dari request POST
$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'];
$status = $data['status'];

// Update status pesan di database
$sql = "UPDATE tbl_forum_messages SET status = ? WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $status, $id);

$response = array();
if ($stmt->execute()) {
    $response['success'] = true;
} else {
    $response['success'] = false;
}

$stmt->close();
$conn->close();

echo json_encode($response);
