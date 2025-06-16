<?php
$db = new PDO('sqlite:' . __DIR__ . '/data/orange_line_project.db');

try {
    $stmt = $pdo->query("SELECT station_name, address, latitude, longitude FROM stations");
    $stations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Query failed: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Stations - Orange Line</title>
    <link rel="stylesheet" href="css/stations.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>
    <div class="background-image"></div>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="left-section">
            <div class="logo-box">
                <img src="css/images/logo (2).png" alt="Logo" class="logo-image" />
            </div>
            <div class="text-box">
                <div class="main-title">Lahore Orange Line</div>
                <div class="sub-title">Metro Train Management System</div>
            </div>
        </div>

        <div class="menu-center">
            <a href="index.html" class="nav-link-button"><i class="fas fa-home"></i></a>
            <a href="routes.php" class="nav-link-button">Routes</a>
            <a href="stations.php" class="nav-link-button active">Stations</a>
            <a href="schedule.php" class="nav-link-button">Schedule</a>
            <a href="fares.php" class="nav-link-button">Fares</a>
        </div>

        <div class="nav-buttons">
            <button class="login-btn" id="loginButton">Login</button>
            <button class="register-btn" id="registerButton">Register</button>
        </div>
    </nav>

    <h1 class="page-title">Metro Stations</h1>

    <!-- Station Cards -->
    <div class="station-list">
        <?php foreach ($stations as $station): ?>
            <div class="station-card">
                <h2><?= htmlspecialchars($station['station_name']) ?></h2>
                <p><strong>Address:</strong> <?= htmlspecialchars($station['address']) ?></p>
                <button 
                    class="view-map-btn" 
                    onclick="openMapModal('<?= $station['latitude'] ?>', '<?= $station['longitude'] ?>', '<?= htmlspecialchars($station['station_name']) ?>')">
                    <i class="fas fa-map-marker-alt"></i> View on Map
                </button>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Map Modal -->
    <div id="mapModal" class="map-modal hidden">
        <div class="map-modal-content">
            <span class="close-btn" onclick="closeMapModal()">&times;</span>
            <h3 id="mapTitle"></h3>
            <iframe 
                id="mapFrame" 
                width="100%" 
                height="400" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>

    <script src="js/stations.js"></script>
</body>
</html>
