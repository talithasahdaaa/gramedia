<?php
header("Content-Type: application/json");
include 'koneksimysql.php';

$response = ['success' => false, 'message' => ''];

try {
    // Check if order_id parameter exists
    if (!isset($_GET['order_id'])) {
        throw new Exception('Order ID parameter is required');
    }

    $orderId = (int) $_GET['order_id'];

    // Get order details
    $stmt = $conn->prepare("
        SELECT o.*, u.nama as customer_name, u.telp as customer_phone
        FROM tbl_order o
        JOIN tbl_pelanggan u ON o.email = u.email
        WHERE o.trans_id = ?
    ");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $orderResult = $stmt->get_result();

    if ($orderResult->num_rows === 0) {
        throw new Exception('Order not found');
    }

    $order = $orderResult->fetch_assoc();

    // Get order items
    $stmt = $conn->prepare("
        SELECT od.*, p.merk as product_name, p.foto as product_image
        FROM tbl_order_detail od
        JOIN tbl_product p ON od.kode_brg = p.kode
        WHERE od.trans_id = ?
    ");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $itemsResult = $stmt->get_result();

    $items = [];
    while ($row = $itemsResult->fetch_assoc()) {
        $items[] = [
            'product_id' => $row['kode_brg'],
            'product_name' => $row['product_name'],
            'product_image' => $row['product_image'],
            'price' => (float) $row['harga_jual'],
            'quantity' => (int) $row['qty']
        ];
    }

    $response['success'] = true;
    $response['data'] = [
        'order_id' => $order['trans_id'],
        'order_date' => $order['tgl_order'],
        'status' => (int) $order['status'],
        'payment_method' => $order['metodebayar'] == 1 ? 'Transfer Bank' : 'COD',
        'payment_proof' => $order['buktibayar'],
        'payment_date' => $order['tgl_order'], // You might want to add payment date field
        'customer_name' => $order['customer_name'],
        'customer_phone' => $order['customer_phone'],
        'shipping_address' => $order['alamat_kirim'],
        'shipping_city' => $order['kota'],
        'shipping_postal_code' => $order['kodepos'],
        'subtotal' => (float) $order['subtotal'],
        'shipping_cost' => (float) $order['ongkir'],
        'total_payment' => (float) $order['total_bayar'],
        'items' => $items
    ];

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);
?>