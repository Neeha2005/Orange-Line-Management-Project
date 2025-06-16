<?php
header('Content-Type: application/json');
require_once '../config.php';

$response = [
    'total_trains' => 0,
    'active_trains' => 0,
    'daily_passengers' => 0,
    'passenger_change' => 0,
    'pending_inquiries' => 0
];

// Get total trains
$query = "SELECT COUNT(*) as count FROM trains";
$result = $conn->query($query);
if ($result) {
    $response['total_trains'] = $result->fetch_assoc()['count'];
}

// Get active trains
$query = "SELECT COUNT(*) as count FROM trains WHERE status = 'active'";
$result = $conn->query($query);
if ($result) {
    $response['active_trains'] = $result->fetch_assoc()['count'];
}

// Get today's passengers
$query = "SELECT count FROM passengers WHERE date = CURDATE()";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    $response['daily_passengers'] = $result->fetch_assoc()['count'];
}

// Get yesterday's passengers for comparison
$query = "SELECT count FROM passengers WHERE date = DATE_SUB(CURDATE(), INTERVAL 1 DAY)";
$result = $conn->query($query);
if ($result && $result->num_rows > 0) {
    $yesterday = $result->fetch_assoc()['count'];
    if ($yesterday > 0) {
        $change = (($response['daily_passengers'] - $yesterday) / $yesterday) * 100;
        $response['passenger_change'] = round($change, 0);
    }
}

// Get pending inquiries
$query = "SELECT COUNT(*) as count FROM inquiries WHERE status = 'pending'";
$result = $conn->query($query);
if ($result) {
    $response['pending_inquiries'] = $result->fetch_assoc()['count'];
}

echo json_encode($response);
$conn->close();
?>