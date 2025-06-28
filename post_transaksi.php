<?php
include "koneksimysql.php";
header('Content-Type: application/json');

// Ambil data dari POST
$nama_penjual = $_POST['nama_penjual'];
$telp = $_POST['telp'];
$kategori_sampah = $_POST['kategori_sampah'];
$harga = $_POST['harga'];
$berat = $_POST['berat'];
$total_harga = $_POST['total_harga'];

$status = ['kode' => 0, 'pesan' => ''];

// Validasi data input
if (empty($nama_penjual) || empty($telp) || empty($kategori_sampah) || empty($harga) || empty($berat) || empty($total_harga)) {
    $status['pesan'] = "Semua data wajib diisi.";
    echo json_encode($status);
    exit();
}

// Gunakan prepared statement untuk mencegah SQL Injection
$stmt = $conn->prepare("INSERT INTO tb_transaksi (nama_penjual, telp, kategori_sampah, harga, berat, total_harga) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssdid", $nama_penjual, $telp, $kategori_sampah, $harga, $berat, $total_harga);

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
