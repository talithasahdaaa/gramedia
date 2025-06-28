<?php
header('Content-Type: application/json');
include "koneksimysql.php";

// $conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Koneksi gagal: " . $conn->connect_error]);
    exit();
}

$sql = "SELECT kode, merk, kategori, hargajual, stok, foto, deskripsi, view_count FROM tbl_product ORDER BY hargajual asc";
$result = $conn->query($sql);

$products = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

echo json_encode($products);

$conn->close();
