<?php
// store_qr_code.php
include '../../auth/db_connection.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['reservationCode']) && isset($data['qrCodeData'])) {
    $reservationCode = $data['reservationCode'];
    $qrCodeData = $data['qrCodeData'];

    // Store the QR code data in the database
    $sql = "UPDATE reservations SET qr_code = ? WHERE reservation_code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $qrCodeData, $reservationCode);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to store QR code']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
}

$conn->close();
?>