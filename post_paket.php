<?php
include "koneksimysql.php";
header('Content-Type: application/json');

// Ambil data dari POST
$no_paket = $_POST['no_paket'];
$kota_tujuan = $_POST['kota_tujuan'];
$provinsi_tujuan = $_POST['provinsi_tujuan'];
$biaya = $_POST['biaya'];
$berat = $_POST['berat'];
$kategori = $_POST['kategori'];
$total_bayar = $_POST['total_bayar'];

$status = ['kode' => 0, 'pesan' => ''];

// Validasi data input
if (empty($no_paket) || empty($kota_tujuan) || empty($provinsi_tujuan) || empty($biaya) || empty($berat) || empty($kategori) || empty($total_bayar)) {
    $status['pesan'] = "Semua data wajib diisi.";
    echo json_encode($status);
    exit();
}

// Gunakan prepared statement untuk mencegah SQL Injection
$stmt = $conn->prepare("INSERT INTO tb_paket (no_paket, kota_tujuan, provinsi_tujuan, biaya, berat, kategori, total_bayar) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssdiid", $no_paket, $kota_tujuan, $provinsi_tujuan, $biaya, $berat, $kategori, $total_bayar);

if ($stmt->execute()) {
    $status['kode'] = 1;
    $status['pesan'] = "Simpan data berhasil";
} else {
    $status['kode'] = 0;
    $status['pesan'] = "Simpan data gagal, kesalahan: " . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($status);
