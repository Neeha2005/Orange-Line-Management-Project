<?php
header('Content-Type: application/json');
require_once '../config.php';

$query = "SELECT * FROM activities ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($query);

$activities = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $activities[] = [
            'type' => $row['type'],
            'description' => $row['description'],
            'created_at' => $row['created_at']
        ];
    }
}

echo json_encode($activities);
$conn->close();
?>