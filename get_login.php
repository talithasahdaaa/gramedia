<?php
include "koneksimysql.php";
header('content-type: application/json');

$email = $_POST['email'];
$password = $_POST['password'];
$datauser = array();
$getstatus = 0;

// Debugging: Tampilkan input
error_log("Email: " . $email);
error_log("Password: " . $password);

$sql = "SELECT nama, email, status, password FROM tbl_pelanggan WHERE email ='" . $email . "'";
$hasil = mysqli_query($conn, $sql);

if (!$hasil) {
    error_log("Error dalam query: " . mysqli_error($conn));
} else {
    $data = mysqli_fetch_object($hasil);

    if ($data && isset($data->password) && md5($password) === $data->password) {
        // Login berhasil
        $getstatus = 1;

        // Update status menjadi 1
        $updateSql = "UPDATE tbl_pelanggan SET status = 1 WHERE email = '" . $email . "'";
        $updateResult = mysqli_query($conn, $updateSql);

        if (!$updateResult) {
            error_log("Gagal update status: " . mysqli_error($conn));
        }

        // Kirim data pengguna beserta status yang sudah di-update
        $datauser = array('nama' => $data->nama, 'email' => $data->email, 'status' => 1);
    } else {
        error_log("Data tidak ditemukan atau password salah.");
    }
}

echo json_encode(array('result' => $getstatus, 'data' => $datauser));
