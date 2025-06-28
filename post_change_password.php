<?php
include "koneksimysql.php";
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_GET['email'];
    $old_password = md5($_GET['old_password']);
    $new_password = md5($_GET['new_password']);

    // Escape dan hash
    $email = mysqli_real_escape_string($conn, $email);
    $old_password = mysqli_real_escape_string($conn, $old_password);
    $new_password = mysqli_real_escape_string($conn, $new_password);

    // Cek password lama
    $sql = "SELECT * FROM tbl_pelanggan WHERE email = '$email' AND PASSWORD = '$old_password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        // Update password baru
        $update = "UPDATE tbl_pelanggan SET PASSWORD = '$new_password' WHERE email = '$email'";
        if (mysqli_query($conn, $update)) {
            echo json_encode(["status" => "success", "message" => "Password updated successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to update password."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Old password is incorrect."]);
    }

    mysqli_close($conn);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}
?>