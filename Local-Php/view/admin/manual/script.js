document.getElementById('event-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const eventName = document.getElementById('event-name').value;
    const totalSeats = parseInt(document.getElementById('total-seats').value);
    const rows = parseInt(document.getElementById('rows').value);
    const columns = parseInt(document.getElementById('columns').value);
    generateSeats(totalSeats, rows, columns);
});

document.getElementById('reserve-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const name = document.getElementById('customer-name').value;
    const email = document.getElementById('customer-email').value;
    const seatNumber = document.getElementById('seat-number').value;
    reserveSeat(name, email, seatNumber);
});

function generateSeats(totalSeats, rows, columns) {
    const seatContainer = document.getElementById('seat-container');
    seatContainer.innerHTML = '';
    seats = [];

    for (let i = 0; i < rows; i++) {
        const row = document.createElement('div');
        row.className = 'seat-row';
        for (let j = 0; j < columns; j++) {
            if (seats.length < totalSeats) {
                const seat = document.createElement('div');
                seat.className = 'seat available';
                seat.textContent = `${String.fromCharCode(65 + i)}${j + 1}`;
                seat.draggable = true;
                seat.addEventListener('dragstart', dragStart);
                seat.addEventListener('dragover', dragOver);
                seat.addEventListener('drop', drop);
                seat.addEventListener('click', selectSeat);
                row.appendChild(seat);
                seats.push(seat);
            }
        }
        seatContainer.appendChild(row);
        seatContainer.appendChild(document.createElement('br')); // Add line break after each row
    }

    updateEventMetrics();
}

function selectSeat(e) {
    if (e.target.classList.contains('available')) {
        document.getElementById('seat-number').value = e.target.textContent;
        document.getElementById('reservation-form').style.display = 'block';
    } else if (e.target.classList.contains('reserved')) {
        alert('This seat is already reserved.');
    }
}

function reserveSeat(name, email, seatNumber) {
    const seat = seats.find(s => s.textContent === seatNumber);
    if (seat && seat.classList.contains('available')) {
        seat.classList.remove('available');
        seat.classList.add('reserved');
        seat.title = `Reserved by ${name}`;
        generateQRCode(name, email, seatNumber);
        document.getElementById('reservation-form').style.display = 'none';
        document.getElementById('reserve-form').reset();
        updateEventMetrics();
        
        // Check if there are any available seats left
        const availableSeats = seats.filter(s => s.classList.contains('available'));
        if (availableSeats.length > 0) {
            // If there are available seats, show the reservation form again
            document.getElementById('reservation-form').style.display = 'block';
            document.getElementById('seat-number').value = '';
        } else {
            // If no seats are available, hide the reservation form
            document.getElementById('reservation-form').style.display = 'none';
            alert('All seats have been reserved!');
        }
    }
}

function generateQRCode(name, email, seatNumber) {
    const qrContainer = document.getElementById('qr-container');
    qrContainer.innerHTML = '';
    const qrData = `Name: ${name}\nEmail: ${email}\nSeat: ${seatNumber}`;
    
    // Generate QR code
    const qr = new QRCode(qrContainer, {
        text: qrData,
        width: 128,
        height: 128
    });
    
    // Create download link
    const downloadLink = document.createElement('a');
    downloadLink.href = qrContainer.querySelector('img').src;
    downloadLink.download = `ticket_${name}_${seatNumber}.png`;
    downloadLink.innerHTML = 'Download QR Code';
    downloadLink.style.display = 'block';
    downloadLink.style.marginTop = '10px';
    qrContainer.appendChild(downloadLink);
}

function dragStart(e) {
    if (e.target.classList.contains('available')) {
        draggedSeat = e.target;
        e.dataTransfer.setData('text/plain', e.target.textContent);
    }
}

function dragOver(e) {
    e.preventDefault();
}

function drop(e) {
    e.preventDefault();
    if (draggedSeat && e.target.classList.contains('available')) {
        const tempContent = e.target.textContent;
        e.target.textContent = draggedSeat.textContent;
        draggedSeat.textContent = tempContent;
    }
    draggedSeat = null;
}

function updateEventMetrics() {
    const totalSeats = seats.length;
    const reservedSeats = seats.filter(seat => seat.classList.contains('reserved')).length;
    const availableSeats = totalSeats - reservedSeats;
    const occupancyRate = (reservedSeats / totalSeats * 100).toFixed(2);

    const metricsContainer = document.createElement('div');
    metricsContainer.innerHTML = `
        <h3>Event Metrics</h3>
        <p>Total Seats: ${totalSeats}</p>
        <p>Reserved Seats: ${reservedSeats}</p>
        <p>Available Seats: ${availableSeats}</p>
        <p>Occupancy Rate: ${occupancyRate}%</p>
    `;

    const existingMetrics = document.querySelector('#event-config > div');
    if (existingMetrics) {
        existingMetrics.remove();
    }
    document.getElementById('event-config').appendChild(metricsContainer);
}


let seats = [];
let draggedSeat = null;


// batch 2

document.addEventListener("DOMContentLoaded", () => {
    const eventForm = document.getElementById("event-form");
    const reserveForm = document.getElementById("reserve-form");
    const reservationDetails = document.getElementById("reservation-details");

    eventForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const eventName = document.getElementById("event-name").value;

        // Clear previous rows
        reservationDetails.innerHTML = "";

        // Add event row as a reference
        const eventRow = document.createElement("tr");
        eventRow.innerHTML = `
            <td colspan="4">Event: ${eventName} | Total Seats Updated</td>
        `;
        reservationDetails.appendChild(eventRow);
    });

    reserveForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const eventName = document.getElementById("event-name").value;
        const customerName = document.getElementById("customer-name").value;
        const seatNumber = document.getElementById("seat-number").value;

        // Add reservation row to the table
        const newRow = document.createElement("tr");
        newRow.innerHTML = `
            <td>${eventName}</td>
            <td>${customerName}</td>
            <td>${seatNumber}</td>
            <td>
                <button onclick="cancelReservation(this)">Cancel</button>
            </td>
        `;
        reservationDetails.appendChild(newRow);
    });
});

function cancelReservation(button) {
    const row = button.parentElement.parentElement;
    const seatNumber = row.children[2].textContent;

    // Find the seat in the seating plan and update status
    const seat = Array.from(document.querySelectorAll(".seat")).find(
        (seat) => seat.textContent === seatNumber
    );

    seat.classList.remove("reserved");
    seat.classList.add("available");

    // Remove the row from the table
    row.remove();
}

// Logout functionality
document.getElementById('logout-btn').addEventListener('click', function() {
    if (confirm('Are you sure you want to log out?')) {
        window.location.href = '../../user/index.php';
        // Perform logout action here (e.g., clear session, redirect to login page)
        alert('You have been logged out.');
        // For demonstration purposes, we'll just reload the page
        window.location.reload();
    }
});

