<?php
include "koneksimysql.php";
header("Content-Type: application/json");

// Ambil data dari input JSON
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Validasi data wajib
if (!isset($data['email']) || !isset($data['items']) || !isset($data['shipping_info'])) {
    echo json_encode(['result' => 0, 'message' => 'Data tidak lengkap']);
    exit;
}

// Escape semua input untuk mencegah SQL injection
$email = mysqli_real_escape_string($conn, $data['email']);
$subtotal = (float)$data['subtotal'];
$ongkir = (float)$data['shipping_cost'];
$total_bayar = (float)$data['total'];
$alamat_kirim = mysqli_real_escape_string($conn, $data['shipping_info']['address']);
$telp_kirim = mysqli_real_escape_string($conn, $data['shipping_info']['phone']);
$kota = mysqli_real_escape_string($conn, $data['shipping_info']['city']);
$provinsi = mysqli_real_escape_string($conn, $data['shipping_info']['province']);
$lamakirim = mysqli_real_escape_string($conn, $data['shipping_info']['estimated_delivery']);
$kodepos = mysqli_real_escape_string($conn, $data['shipping_info']['postal_code']);
$metodebayar = $data['payment_method'] == 'COD' ? 1 : 2;
$status = 0; // Status awal: pending

// Mulai transaksi
mysqli_begin_transaction($conn);

try {
    // Dapatkan ID transaksi baru
    $sql = "SELECT IFNULL(MAX(nomer_id)+1, 1) AS new_id FROM tbl_order";
    $hasil = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($hasil);
    $id = $row['new_id'];

    // Simpan data order
    $sql = "INSERT INTO tbl_order(
                nomer_id, email, tgl_order, subtotal, ongkir, total_bayar,
                alamat_kirim, telp_kirim, kota, provinsi, lamakirim, 
                kodepos, metodebayar, status
            ) VALUES (
                '$id', '$email', NOW(), '$subtotal', '$ongkir', '$total_bayar',
                '$alamat_kirim', '$telp_kirim', '$kota', '$provinsi', '$lamakirim',
                '$kodepos', '$metodebayar', '$status'
            )";
    
    $hasil = mysqli_query($conn, $sql);
    
    if (!$hasil) {
        throw new Exception("Gagal menyimpan order: " . mysqli_error($conn));
    }

    // Simpan detail order
    foreach ($data['items'] as $item) {
        $kode_brg = mysqli_real_escape_string($conn, $item['product_code']);
        $harga = (float)$item['price'];
        $qty = (int)$item['quantity'];
        $total = $harga * $qty;

        $sql = "INSERT INTO tbl_order_detail(
                    trans_id, kode_brg, harga_total, qty
                ) VALUES (
                    '$id', '$kode_brg', '$total', '$qty'
                )";
        
        $hasil = mysqli_query($conn, $sql);
        
        if (!$hasil) {
            throw new Exception("Gagal menyimpan detail order: " . mysqli_error($conn));
        }
    }

    // Commit transaksi jika semua berhasil
    mysqli_commit($conn);
    
    $response = [
        'result' => 1,
        'message' => 'Order berhasil disimpan',
        'order_id' => $id
    ];
} catch (Exception $e) {
    // Rollback jika ada error
    mysqli_rollback($conn);
    
    $response = [
        'result' => 0,
        'message' => $e->getMessage()
    ];
}

echo json_encode($response);
?>