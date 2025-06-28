<?php
include 'koneksi.php';

$user_id = $_GET['user_id']; // dari SharedPreferences Android, isinya no telepon user

$query = "SELECT * FROM tbl_order WHERE telp_kirim='$user_id' ORDER BY tgl_order DESC";
$result = mysqli_query($koneksi, $query);

$data = array();
while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

echo json_encode($data);
?>
