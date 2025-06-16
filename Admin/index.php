<?php
require_once 'includes/header.php';
require_once 'includes/sidebar.php';
?>

<div class="main-content">
    <h1>Overview</h1>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>Total Trains</h3>
            <p>Loading...</p>
            <span>Active trains</span>
        </div>
        
        <div class="stat-card">
            <h3>Daily Passengers</h3>
            <p>Loading...</p>
            <span>Loading...</span>
        </div>
        
        <div class="stat-card">
            <h3>Pending Inquiries</h3>
            <p>Loading...</p>
            <span>Requires attention</span>
        </div>
    </div>

    <div class="recent-activities">
        <h2>Recent Activities</h2>
        <ul>
            <li>Loading activities...</li>
        </ul>
    </div>
</div>

<?php
require_once 'includes/footer.php';
?>