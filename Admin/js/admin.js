document.addEventListener('DOMContentLoaded', function() {
    // Fetch dashboard data when page loads
    fetchDashboardData();
    
    // Set up auto-refresh every 5 minutes
    setInterval(fetchDashboardData, 300000);
});

function fetchDashboardData() {
    // Fetch summary data
    fetch('api/get_summary.php')
        .then(response => response.json())
        .then(data => {
            document.querySelector('.stat-card:nth-child(1) p').textContent = data.total_trains;
            document.querySelector('.stat-card:nth-child(2) p').textContent = data.daily_passengers.toLocaleString();
            document.querySelector('.stat-card:nth-child(2) span').textContent = `+${data.passenger_change}% from yesterday`;
            document.querySelector('.stat-card:nth-child(3) p').textContent = data.pending_inquiries;
        })
        .catch(error => console.error('Error fetching summary:', error));
    
    // Fetch recent activities
    fetch('api/get_activities.php')
        .then(response => response.json())
        .then(data => {
            const activitiesList = document.querySelector('.recent-activities ul');
            activitiesList.innerHTML = '';
            
            data.forEach(activity => {
                const timeAgo = formatTimeAgo(activity.created_at);
                const listItem = document.createElement('li');
                listItem.innerHTML = `
                    <strong>${activity.type.replace('_', ' ')}</strong>
                    <p>${activity.description}</p>
                    <small>${timeAgo} ago</small>
                `;
                activitiesList.appendChild(listItem);
            });
        })
        .catch(error => console.error('Error fetching activities:', error));
}

function formatTimeAgo(timestamp) {
    const now = new Date();
    const activityDate = new Date(timestamp);
    const diffInSeconds = Math.floor((now - activityDate) / 1000);
    
    if (diffInSeconds < 60) return `${diffInSeconds} seconds`;
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} minutes`;
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} hours`;
    return `${Math.floor(diffInSeconds / 86400)} days`;
}