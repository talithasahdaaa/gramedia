<?php
include 'koneksimysql.php';

if (isset($_GET['id'])) {
    $id_produk = $_GET['id'];

    $sql = "UPDATE tbl_product SET views = views + 1 WHERE id = '$id_produk'";
    if (mysqli_query($koneksi, $sql)) {
        echo json_encode(["status" => "success", "message" => "View berhasil ditambahkan"]);
    } else {
        echo json_encode(["status" => "error", "message" => mysqli_error($koneksi)]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID produk tidak ditemukan"]);
}
?>
