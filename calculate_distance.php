function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // Kilometers. Use 3959 for miles

    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);

    $a = sin($dLat/2) * sin($dLat/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon/2) * sin($dLon/2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earthRadius * $c;

    return round($distance, 2); // Return in KM
}

// Example: Get stations
$fromStation = "Central Station";
$toStation = "North Terminal";

$conn = new SQLite3('orangeLineTrain.db');

$query = $conn->prepare("SELECT latitude, longitude FROM stations WHERE name = :name");
$query->bindValue(':name', $fromStation);
$result1 = $query->execute()->fetchArray(SQLITE3_ASSOC);

$query->bindValue(':name', $toStation);
$result2 = $query->execute()->fetchArray(SQLITE3_ASSOC);

if ($result1 && $result2) {
    $distance = calculateDistance(
        $result1['latitude'], $result1['longitude'],
        $result2['latitude'], $result2['longitude']
    );
    echo "Distance between $fromStation and $toStation is: $distance KM";
} else {
    echo "Station data not found.";
}
