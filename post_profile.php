<?php
include "koneksimysql.php";
header('Content-Type: application/json');

// Ambil data dari POST
$email = mysqli_real_escape_string($conn, $_POST['email']);
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
$kota = mysqli_real_escape_string($conn, $_POST['kota']);
$provinsi = mysqli_real_escape_string($conn, $_POST['provinsi']);
$telp = mysqli_real_escape_string($conn, $_POST['telp']);
$kodepos = mysqli_real_escape_string($conn, $_POST['kodepos']);

$getresult = 0;
$message = "";

// Query untuk update data
$sql = "UPDATE tbl_pelanggan 
        SET nama = '" . $nama . "', 
            alamat = '" . $alamat . "', 
            kota = '" . $kota . "', 
            provinsi = '" . $provinsi . "', 
            telp = '" . $telp . "', 
            kodepos = '" . $kodepos . "' 
        WHERE email = '" . $email . "'";

// Eksekusi query
$hasil = mysqli_query($conn, $sql);

if ($hasil) {
    $getresult = 1;
    $message = "Simpan Berhasil";
} else {
    $getresult = 0;
    $message = "Simpan Gagal: " . mysqli_error($conn);
}

// Kembalikan respons JSON
echo json_encode(array('result' => $getresult, 'message' => $message));

// Tutup koneksi database
mysqli_close($conn);
