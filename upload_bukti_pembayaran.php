<?php
include 'koneksi.php';

$order_id = $_POST['order_id'];

if (isset($_FILES['bukti'])) {
    $file_tmp = $_FILES['bukti']['tmp_name'];
    $file_name = uniqid() . '_' . $_FILES['bukti']['name'];
    $upload_dir = "bukti_pembayaran/";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $upload_path = $upload_dir . $file_name;

    if (move_uploaded_file($file_tmp, $upload_path)) {
        $sql = "UPDATE tbl_order SET buktiBayar='$upload_path', status='dibayar' WHERE nomer_id='$order_id'";
        if (mysqli_query($koneksi, $sql)) {
            echo json_encode(["success" => true, "message" => "Upload bukti berhasil"]);
        } else {
            echo json_encode(["success" => false, "message" => "Gagal update database"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Gagal upload file"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "File tidak ditemukan"]);
}
?>
