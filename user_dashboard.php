<?php
$db = new PDO('sqlite:' . __DIR__ . '/data/orange_line_project.db');

session_start();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard - Orange Line</title>
    <link rel="stylesheet" href="css/user_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
</head>
<body>

<!-- ===== Hero Section with Overlay ===== -->
<section class="hero-section">
    <div class="overlay"></div>

    <!-- ===== Navbar ===== -->
    <nav class="navbar">
        <div class="logo-wrapper">
            <div class="logo-box">
                <img src="images/train1.jpg" alt="Logo" class="logo-image">
            </div>
            <div class="text-box">
                <div class="main-title">Orange Line</div>
                <div class="sub-title">Metro Transit System</div>
            </div>
        </div>

        <div class="welcome-message">
            <span>Welcome,</span> <strong><?php echo htmlspecialchars($username); ?></strong>
        </div>
    </nav>

    <!-- ===== Sidebar ===== -->
    <!-- ===== Sidebar ===== -->
        <aside class="sidebar">
            <h3><i class="fas fa-bars"></i> MENU</h3>
            <ul>
                <li><i class="fas fa-tachometer-alt"></i> Dashboard</li>
                <li><i class="fas fa-ticket-alt"></i> Tickets</li>
                <li><i class="fas fa-map-marker-alt"></i> Stations</li>
                <li><i class="fas fa-sign-out-alt"></i> Logout</li>
            </ul>
        </aside>


    <!-- ===== Dashboard Content ===== -->
    <div class="dashboard-content">

        <div class="card-section">
            <div class="dash-card">Booked Tickets</div>
            <div class="dash-card">Cancel Booking</div>
            <div class="dash-card">Upcoming Trips</div>
        </div>

        <div class="table-section">
    <h2>Available Trains</h2>
    <table>
        <thead>
            <tr>
                <th>Train ID</th>
                <th>Train Name</th>
                <th>Capacity</th>
                <th>Status</th>
                <th>Created By</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
        $sql = "SELECT * FROM trains WHERE status = 'active' ORDER BY created_at DESC";
        $stmt = $pdo->query($sql);
        $trains = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($trains && count($trains) > 0) {
            foreach ($trains as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['train_id']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['train_name']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['capacity']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['status']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_by']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['created_at']) . "</td>";
                    echo "<td><button class='book-btn'>Book</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>No active trains available.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

    </div>
</section>

</body>
</html>
