<?php
header("Content-Type: application/json");
include 'koneksimysql.php';

$response = ['success' => false, 'message' => ''];

try {
    // Check if this is a POST request
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Invalid request method');
    }

    // Check if file was uploaded
    if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('No payment proof uploaded or upload error');
    }

    // Get order ID
    $orderId = isset($_POST['order_id']) ? (int) $_POST['order_id'] : 0;
    if ($orderId <= 0) {
        throw new Exception('Invalid order ID');
    }

    // Process file upload
    $uploadDir = 'img/proofs/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $fileName = 'proof_' . $orderId . '_' . time() . '.jpg';
    $filePath = $uploadDir . $fileName;

    if (!move_uploaded_file($_FILES['payment_proof']['tmp_name'], $filePath)) {
        throw new Exception('Failed to save payment proof');
    }

    // Update database
    $stmt = $conn->prepare("UPDATE tbl_order SET buktibayar = ?, status = 1 WHERE trans_id = ?");
    $stmt->bind_param("si", $fileName, $orderId);

    if (!$stmt->execute()) {
        throw new Exception('Failed to update order: ' . $stmt->error);
    }

    $response['success'] = true;
    $response['message'] = 'Payment confirmed successfully';

} catch (Exception $e) {
    $response['message'] = $e->getMessage();
    http_response_code(400);
}

echo json_encode($response);
?>