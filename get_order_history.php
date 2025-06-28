<?php
header("Content-Type: application/json");
include 'koneksimysql.php';

$response = ['success' => false, 'data' => [], 'message' => ''];

try {
    // Check if email parameter exists
    if (!isset($_GET['email'])) {
        throw new Exception('Email parameter is required');
    }

    $email = $_GET['email'];

    // Get orders
    $stmt = $conn->prepare("
        SELECT o.trans_id, o.tgl_order, o.total_bayar, o.status, 
               COUNT(od.kode_brg) as item_count
        FROM tbl_order o
        LEFT JOIN tbl_order_detail od ON o.trans_id = od.trans_id
        WHERE o.email = ?
        GROUP BY o.trans_id
        ORDER BY o.trans_id DESC
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = [
            'order_id' => $row['trans_id'],
            'date' => $row['tgl_order'],
            'total' => $row['total_bayar'],
            'status' => $row['status'],
            'item_count' => $row['item_count']
        ];
    }

    $response['success'] = true;
    $response['data'] = $orders;

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);
?>