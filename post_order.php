<?php
include "koneksimysql.php";
header("Content-type: application/json");

$order = $_POST['order'];
$order_detail = $_POST['order_detail'];

$hasilorder = json_decode($order);
$hasilorder_detail = json_decode($order_detail);

$sql = "SELECT IFNULL(MAX(trans_id), 1) AS trans_id FROM tbl_order";
$hasil = mysqli_query($conn, $sql);
$data = mysqli_fetch_array($hasil);

$id = $data['trans_id'];
$Sid = $id + 1;

$email = $hasilorder->email;
$tgl_order = date("Y-m-d", time());
$subtotal = $hasilorder->subtotal;
$ongkir = $hasilorder->ongkir;
$total_bayar = $hasilorder->total_bayar;
$alamat_kirim = $hasilorder->alamat_kirim;
$telp_kirim = $hasilorder->telp_kirim;
$kota = $hasilorder->kota;
$provinsi = $hasilorder->provinsi;
$lamakirim = $hasilorder->lamakirim;
$kodepos = $hasilorder->kodepos;
$metodebayar = $hasilorder->metodebayar;
$buktibayar = $hasilorder->buktibayar;
$status = $hasilorder->status;

$sql = "INSERT INTO tbl_order (trans_id, email, tgl_order, subtotal, ongkir, total_bayar, alamat_kirim, telp_kirim, kota, provinsi, lamakirim, kodepos, metodebayar, buktibayar, status)
        VALUES ($Sid, '$email', '$tgl_order', '$subtotal', '$ongkir', '$total_bayar', '$alamat_kirim', '$telp_kirim', '$kota', '$provinsi', '$lamakirim', '$kodepos', '$metodebayar', '$buktibayar', '$status')";
$hasil = mysqli_query($conn, $sql);

if ($hasil) {
    $sql = "";
    $kode = 0;
    $pesan = "";

    foreach ($hasilorder_detail as $h) {
        $sql = "INSERT INTO tbl_order_detail (trans_id, kode_brg, harga_jual, qty, bayar)
                VALUES ($Sid, '$h->kode', '$h->harga', '$h->qty', '" . ($h->harga * $h->qty) . "')";
        $hasil = mysqli_query($conn, $sql);

        if ($hasil) {
            $kode = 1;
            $orderid = $Sid;
            $pesan = "Proses Berhasil";
        } else {
            $kode = 0;
            $pesan = "Proses Gagal = " . mysqli_error($conn);
        }
    }
} else {
    $kode = 0;
    $pesan = "Proses Gagal = " . mysqli_error($conn);
}

$message['kode'] = $kode;
$message['orderid'] = $orderid;
$message['pesan'] = $pesan;

echo json_encode($message);
