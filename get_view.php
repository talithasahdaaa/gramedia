<?php
header('Content-Type: application/json');
include 'koneksimysql.php';

$kode = $_GET['kode'] ?? '';

if ($kode) {
    $query = "SELECT view_count FROM tbl_product WHERE kode = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $kode);
    $stmt->execute();
    $stmt->bind_result($view_count);
    if ($stmt->fetch()) {
        echo $view_count;
    } else {
        echo "0";
    }
    $stmt->close();
} else {
    echo "0";
}
$conn->close();
