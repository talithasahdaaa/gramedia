<?php
include 'koneksimysql.php';
header('content-type: application/json');

// Query untuk mengambil data produk
$sql = "SELECT * FROM tbl_product";
$result = $conn->query($sql);

$products = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Mengembalikan data dalam format JSON
echo json_encode($products);

$conn->close();
