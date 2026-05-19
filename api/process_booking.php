<?php
require 'db.php';

// Accept JSON input
$data = json_decode(file_get_contents('php://input'), true);

if ($data) {
    $patient_name = $conn->real_escape_string($data['patient_name']);
    $phone = $conn->real_escape_string($data['phone']);
    $service_name = $conn->real_escape_string($data['service_name']);
    $booking_time = $conn->real_escape_string($data['booking_time']);
    $price = floatval($data['price']);

    $sql = "INSERT INTO bookings (patient_name, phone, service_name, booking_time, price) VALUES ('$patient_name', '$phone', '$service_name', '$booking_time', '$price')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'تم تأكيد الحجز بنجاح!']);
    } else {
        echo json_encode(['success' => false, 'message' => 'حدث خطأ أثناء الحجز: ' . $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'بيانات غير صالحة.']);
}

$conn->close();
?>
