<?php
include 'koneksi.php';

$json = $_POST['post_checkout'];
$data = json_decode($json, true);

$nomer_id = "ORD" . rand(1000,9999);
$nama = $data['nama'];
$alamat = $data['alamat'];
$kota = $data['kota'];
$provinsi = $data['provinsi'];
$kodepos = $data['kodepos'];
$telp = $data['telp'];
$metodebayar = $data['metodebayar'];
$lamakirim = $data['lamakirim'];
$subtotal = $data['subtotal'];
$ongkir = $data['ongkir'];
$total_bayar = $data['total_bayar'];
$tgl_order = date("Y-m-d");

$query = "INSERT INTO tbl_order (nomer_id, tgl_order, alamat_kirim, kota, provinsi, kodepos, telp_kirim, metodebayar, lamaKirim, subtotal, ongkir, total_bayar, status)
VALUES ('$nomer_id', '$tgl_order', '$alamat', '$kota', '$provinsi', '$kodepos', '$telp', '$metodebayar', '$lamakirim', '$subtotal', '$ongkir', '$total_bayar', 'belum dibayar')";

if (mysqli_query($koneksi, $query)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => mysqli_error($koneksi)]);
}
?>
