<?php
header('Content-Type: application/json');
include 'koneksimysql.php';

// Ambil semua data produk dan urutkan berdasarkan view_count dari yang tertinggi
$query = "SELECT * FROM tbl_product ORDER BY view_count DESC LIMIT 6";
$result = $conn->query($query);

$products = array();
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode($products);

$conn->close();
