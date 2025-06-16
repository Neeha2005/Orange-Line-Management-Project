<?php
header('Content-Type: application/json');
$db = new PDO('sqlite:' . __DIR__ . '/data/orange_line_project.db');


// GET request: fetch distance, fare, time
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $from = $_GET['from'];
    $to = $_GET['to'];
    $schedule_id = $_GET['schedule_id'];

    // Get station coords
    $stmt = $db->prepare("SELECT latitude, longitude FROM stations WHERE name = :name");
    $stmt->execute([':name' => $from]);
    $from_coords = $stmt->fetch(PDO::FETCH_ASSOC);

    $stmt->execute([':name' => $to]);
    $to_coords = $stmt->fetch(PDO::FETCH_ASSOC);

    // Distance
    $dx = $to_coords['longitude'] - $from_coords['longitude'];
    $dy = $to_coords['latitude'] - $from_coords['latitude'];
    $distance = round(sqrt($dx * $dx + $dy * $dy));

    // Fare lookup
    $fareQuery = $db->prepare("SELECT fare FROM MetroFares WHERE min_distance <= :dist AND max_distance >= :dist LIMIT 1");
    $fareQuery->execute([':dist' => $distance]);
    $fareRow = $fareQuery->fetch(PDO::FETCH_ASSOC);
    $fare = $fareRow ? $fareRow['fare'] : 0;

    // Get schedule departure time
    $timeStmt = $db->prepare("SELECT departure_time FROM schedules WHERE schedule_id = :sid");
    $timeStmt->execute([':sid' => $schedule_id]);
    $departure = $timeStmt->fetchColumn();

    echo json_encode([
        'distance' => $distance,
        'fare' => floatval($fare),
        'departure_time' => $departure
    ]);
    exit;
}

// POST request: insert ticket
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $user_id = $data['user_id'];
    $schedule_id = $data['schedule_id'];
    $travel_date = $data['travel_date'];

    // Get distance + fare again
    $stmt = $db->prepare("SELECT f.latitude AS lat1, f.longitude AS lon1, t.latitude AS lat2, t.longitude AS lon2
                          FROM schedules s
                          JOIN stations f ON s.from_station = f.station_id
                          JOIN stations t ON s.to_station = t.station_id
                          WHERE s.schedule_id = :sid");
    $stmt->execute([':sid' => $schedule_id]);
    $coords = $stmt->fetch(PDO::FETCH_ASSOC);

    $dx = $coords['lon2'] - $coords['lon1'];
    $dy = $coords['lat2'] - $coords['lat1'];
    $distance = round(sqrt($dx * $dx + $dy * $dy));

    $fareQuery = $db->prepare("SELECT fare FROM MetroFares WHERE min_distance <= :dist AND max_distance >= :dist LIMIT 1");
    $fareQuery->execute([':dist' => $distance]);
    $fareRow = $fareQuery->fetch(PDO::FETCH_ASSOC);
    $fare = $fareRow ? $fareRow['fare'] : 0;

    // Assign seat number (just random for now)
    $seat = "A" . rand(1, 100);

    $insert = $db->prepare("INSERT INTO tickets (user_id, schedule_id, seat_number, travel_date, price)
                            VALUES (:user, :schedule, :seat, :date, :price)");
    $insert->execute([
        ':user' => $user_id,
        ':schedule' => $schedule_id,
        ':seat' => $seat,
        ':date' => $travel_date,
        ':price' => $fare
    ]);

    echo json_encode(['message' => 'Ticket booked successfully!']);
}
