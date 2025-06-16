<?php
header('Content-Type: application/json');

try {
    $db = new PDO('sqlite:data/orange_line_project.db');

    $query = "
        SELECT s.train_id, f.name AS from_station, t.name AS to_station,
               s.departure_time, s.arrival_time, s.frequency, s.status
        FROM schedules s
        JOIN stations f ON s.from_station = f.station_id
        JOIN stations t ON s.to_station = t.station_id
        WHERE s.status = 'active'
    ";

    $params = [];
    if (!empty($_GET['from'])) {
        $query .= " AND f.name LIKE :from";
        $params[':from'] = $_GET['from'] . '%';
    }
    if (!empty($_GET['to'])) {
        $query .= " AND t.name LIKE :to";
        $params[':to'] = $_GET['to'] . '%';
    }

    // Optional: add frequency-based date filtering here if needed

    $stmt = $db->prepare($query);
    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val);
    }

    $stmt->execute();
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($schedules);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
