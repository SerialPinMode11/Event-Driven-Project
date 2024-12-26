<?php
include '../../auth/db_connection.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seat_id = $_POST['seat_id'];
    $event_id = $_POST['event_id'];
    $seat_label = $_POST['seat_label'];
    $name = $_POST['name'];
    $arrival_time = $_POST['arrival_time'];

    // Check if the seat is already reserved
    $check_sql = "SELECT * FROM reservations WHERE event_id = ? AND seat_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $event_id, $seat_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(['success' => false, 'message' => 'This seat is already reserved.']);
    } else {
        // Insert the reservation
        $insert_sql = "INSERT INTO reservations (event_id, seat_id, seat_label, name, arrival_time) VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("iisss", $event_id, $seat_id, $seat_label, $name, $arrival_time);

        if ($insert_stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to reserve the seat.']);
        }
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
}

$conn->close();
?>

