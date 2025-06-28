<?php
header('Content-Type: application/json');
include 'koneksimysql.php';

$kode = $_GET['kode'] ?? '';

if ($kode) {
    // Mengambil harga jual terendah berdasarkan kode produk
    $query = "SELECT MIN(hargajual) as harga FROM tbl_product WHERE kode = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $kode);
    $stmt->execute();
    $stmt->bind_result($harga);

    if ($stmt->fetch() && $harga !== null) {
        echo json_encode(['harga' => $harga]);
    } else {
        echo json_encode(['status' => 'produk tidak ada']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'kode tidak diberikan']);
}

$conn->close();
