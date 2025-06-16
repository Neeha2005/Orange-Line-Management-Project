// Sample data - in a real app, this would come from your database
const tickets = [
    {
        ticket_id: "TKT-001",
        train_number: "OR-101",
        from_station: "Central Station",
        to_station: "North Terminal",
        departure_time: "09:30 AM",
        travel_date: "2024-01-15",
        passenger: "John Doe",
        seat_number: "A-12",
        price: 15.00,
        status: "confirmed"
    },
    {
        ticket_id: "TKT-002",
        train_number: "OR-205",
        from_station: "South Plaza",
        to_station: "East Junction",
        departure_time: "02:15 PM",
        travel_date: "2024-01-18",
        passenger: "John Doe",
        seat_number: "B-08",
        price: 12.50,
        status: "confirmed"
    }
];

// Function to format date as "YYYY-MM-DD" to "Month Day, Year"
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString(undefined, options);
}

// Function to render tickets
function renderTickets() {
    const container = document.getElementById('tickets-container');
    container.innerHTML = '';
    
    tickets.forEach(ticket => {
        const ticketElement = document.createElement('div');
        ticketElement.className = 'ticket';
        
        ticketElement.innerHTML = `
            <div class="ticket-header">${ticket.train_number}</div>
            <div class="ticket-id">Ticket ID: ${ticket.ticket_id}</div>
            
            <table>
                <thead>
                    <tr>
                        <th>Route</th>
                        <th>Date & Time</th>
                        <th>Passenger</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>${ticket.from_station} → ${ticket.to_station}</td>
                        <td>${formatDate(ticket.travel_date)}<br>${ticket.departure_time}</td>
                        <td>${ticket.passenger}<br>Seat: ${ticket.seat_number}</td>
                        <td>$${ticket.price.toFixed(2)}</td>
                    </tr>
                </tbody>
            </table>
            
            <div class="status ${ticket.status}">${ticket.status.toUpperCase()}</div>
            
            <div class="actions no-print">
                <button onclick="cancelTicket('${ticket.ticket_id}')" ${ticket.status !== 'confirmed' ? 'disabled' : ''}>Cancel Ticket</button>
                <button onclick="printTicket('${ticket.ticket_id}')">Print Ticket</button>
            </div>
            
            <hr class="no-print">
        `;
        
        container.appendChild(ticketElement);
    });
}

// Function to handle ticket cancellation
function cancelTicket(ticketId) {
    if (confirm("Are you sure you want to cancel this ticket?")) {
        // In a real app, you would make an API call to update the database
        const ticket = tickets.find(t => t.ticket_id === ticketId);
        if (ticket) {
            ticket.status = 'cancelled';
            renderTickets();
            alert("Ticket has been cancelled.");
        }
    }
}

// Function to print a specific ticket
function printTicket(ticketId) {
    const printWindow = window.open('', '', 'width=800,height=600');
    const ticket = tickets.find(t => t.ticket_id === ticketId);
    
    if (ticket) {
        printWindow.document.write(`
            <html>
                <head>
                    <title>Ticket ${ticket.ticket_id}</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 20px; }
                        .ticket { border: 1px solid #ccc; padding: 20px; max-width: 600px; margin: 0 auto; }
                        .ticket-header { font-size: 1.5em; font-weight: bold; margin-bottom: 15px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                        th, td { padding: 8px; text-align: left; border-bottom: 1px solid #ddd; }
                        .footer { margin-top: 20px; font-size: 0.8em; text-align: center; }
                    </style>
                </head>
                <body>
                    <div class="ticket">
                        <div class="ticket-header">${ticket.train_number}</div>
                        <div>Ticket ID: ${ticket.ticket_id}</div>
                        
                        <table>
                            <tr>
                                <th>From</th>
                                <td>${ticket.from_station}</td>
                            </tr>
                            <tr>
                                <th>To</th>
                                <td>${ticket.to_station}</td>
                            </tr>
                            <tr>
                                <th>Date</th>
                                <td>${formatDate(ticket.travel_date)}</td>
                            </tr>
                            <tr>
                                <th>Time</th>
                                <td>${ticket.departure_time}</td>
                            </tr>
                            <tr>
                                <th>Passenger</th>
                                <td>${ticket.passenger}</td>
                            </tr>
                            <tr>
                                <th>Seat</th>
                                <td>${ticket.seat_number}</td>
                            </tr>
                            <tr>
                                <th>Price</th>
                                <td>$${ticket.price.toFixed(2)}</td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>${ticket.status.toUpperCase()}</td>
                            </tr>
                        </table>
                        
                        <div class="footer">
                            Thank you for choosing our service!<br>
                            Please present this ticket when boarding.
                        </div>
                    </div>
                    
                    <script>
                        window.onload = function() {
                            setTimeout(function() {
                                window.print();
                                window.close();
                            }, 200);
                        };
                    </script>
                </body>
            </html>
        `);
        printWindow.document.close();
    }
}

// Initialize the page
document.addEventListener('DOMContentLoaded', renderTickets);