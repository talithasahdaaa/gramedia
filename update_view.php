<?php
header('Content-Type: application/json');
include 'koneksimysql.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['kode']) || empty($_POST['kode'])) {
        http_response_code(400);
        echo json_encode(["success" => false, "message" => "Parameter 'kode' tidak ditemukan"]);
        exit();
    }

    $kode = $_POST['kode'];

    $query = "UPDATE tbl_product SET view_count = view_count + 1 WHERE kode = ?";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Gagal mempersiapkan statement"]);
        exit();
    }

    $stmt->bind_param("s", $kode);

    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(["success" => true, "message" => "View updated"]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Failed to update"]);
    }

    $stmt->close();
    $conn->close();
} else {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
}
