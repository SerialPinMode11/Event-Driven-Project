<?php
include '../../auth/db_connection.php';

$message = "";
$events = [];

// Fetch all events from the database
$sql = "SELECT * FROM private_seat ORDER BY id DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $events[] = $row;
    }
} else {
    $message = "No events found in the database.";
}

if ($conn->connect_error) {
    $message = "Connection failed: " . $conn->connect_error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Seating Arrangements</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        .seat {
            width: 40px;
            height: 40px;
            margin: 2px;
            position: relative;
            cursor: pointer;
        }
        .seat-container {
            background-color: #4a5568;
            border-radius: 5px;
            overflow: hidden;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .seat-label {
            font-size: 12px;
            font-weight: bold;
            color: #ffffff;
        }
        .aisle {
            width: 20px;
        }
        .row-label {
            width: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #ffffff;
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex flex-col items-center justify-center p-8">
    <!-- Navigation -->
    <div class="absolute top-4 left-4 text-white">
        <h1 class="text-2xl font-bold">Event-Holder</h1>
        <nav class="mt-2">
            <a href="../index.php" class="text-sm text-gray-300 hover:underline">Home</a>
            <span class="text-gray-400 mx-2">/</span>
            <a href="#" class="text-sm text-gray-300 hover:underline">Private</a>
            <span class="text-gray-400 mx-2">/</span>
            <a href="#" class="text-sm text-gray-300 hover:underline">View & Reserve</a>
        </nav>
    </div>

    <h1 class="text-3xl font-bold mb-6">Event Seating Arrangements</h1>

    <?php if (!empty($message)): ?>
        <div class="mb-4 p-4 bg-red-500 text-white rounded-lg">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <div class="w-full max-w-4xl mb-8">
        <table class="w-full bg-gray-800 rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-700">
                    <th class="px-4 py-2 text-left">Gathering Name</th>
                    <th class="px-4 py-2 text-left">Total Seats</th>
                    <th class="px-4 py-2 text-left">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                    <tr class="border-t border-gray-700">
                        <td class="px-4 py-2"><?php echo htmlspecialchars($event['event_name']); ?></td>
                        <td class="px-4 py-2"><?php echo $event['total_seat']; ?></td>
                        <td class="px-4 py-2">
                            <button
                                class="bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded"
                                onclick="showSeats(<?php echo htmlspecialchars(json_encode($event)); ?>)"
                            >
                                Show Seats
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div id="seatContainer" class="mt-8 hidden w-full max-w-6xl">
        <h2 id="eventName" class="text-2xl font-bold mb-4"></h2>
        <div id="seatLayout" class="flex flex-col items-center"></div>
    </div>

    <a href="pindex.php" class="mt-6 px-4 py-2 bg-orange-500 text-white font-semibold rounded-md hover:bg-orange-600">
        Back to Event Creation
    </a>

    <script>
        let currentEvent = null;

        function showSeats(event) {
            currentEvent = event;
            const seatContainer = document.getElementById('seatContainer');
            const eventNameElement = document.getElementById('eventName');
            const seatLayout = document.getElementById('seatLayout');

            eventNameElement.textContent = `Seat Layout for "${event.event_name}"`;
            seatLayout.innerHTML = '';

            const totalSeats = parseInt(event.total_seat);
            const seatsPerRow = Math.ceil(Math.sqrt(totalSeats));
            const numRows = Math.ceil(totalSeats / seatsPerRow);

            for (let row = 0; row < numRows; row++) {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'flex items-center mb-2';

                // Add row label
                const rowLabel = document.createElement('div');
                rowLabel.className = 'row-label';
                rowLabel.textContent = String.fromCharCode(65 + row);
                rowDiv.appendChild(rowLabel);

                for (let col = 0; col < seatsPerRow; col++) {
                    const seatNumber = row * seatsPerRow + col + 1;
                    if (seatNumber > totalSeats) break;

                    if (col === Math.floor(seatsPerRow / 2)) {
                        const aisle = document.createElement('div');
                        aisle.className = 'aisle';
                        rowDiv.appendChild(aisle);
                    }

                    const seatDiv = document.createElement('div');
                    seatDiv.className = 'seat';
                    seatDiv.innerHTML = `
                        <div class="seat-container">
                            <span class="seat-label">${String.fromCharCode(65 + row)}${col + 1}</span>
                        </div>
                    `;
                    seatDiv.addEventListener('click', function() {
                        const seatLabel = this.querySelector('.seat-label').textContent;
                        reserveSeat(seatLabel);
                    });
                    rowDiv.appendChild(seatDiv);
                }

                seatLayout.appendChild(rowDiv);
            }

            seatContainer.classList.remove('hidden');
        }

        function reserveSeat(seatLabel) {
            const [row, col] = [seatLabel.charAt(0), seatLabel.slice(1)];
            const seatId = (row.charCodeAt(0) - 65) * parseInt(currentEvent.column_seat) + parseInt(col);

            const reservationForm = `
                <form id="reservationForm" class="bg-gray-800 p-6 rounded-lg">
                    <h3 class="text-xl font-bold mb-4">Reserve Seat ${seatLabel}</h3>
                    <input type="hidden" name="seat_id" value="${seatId}">
                    <input type="hidden" name="event_id" value="${currentEvent.id}">
                    <input type="hidden" name="seat_label" value="${seatLabel}">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-300">Name</label>
                        <input type="text" id="name" name="name" required class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                    </div>
                    <div class="mb-4">
                        <label for="arrival_time" class="block text-sm font-medium text-gray-300">Arrival Time</label>
                        <input type="time" id="arrival_time" name="arrival_time" required class="mt-1 block w-full rounded-md bg-gray-700 border-gray-600 text-white">
                    </div>
                    <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white font-bold py-2 px-4 rounded">
                        Reserve Seat
                    </button>
                </form>
            `;

            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center';
            modal.innerHTML = reservationForm;
            document.body.appendChild(modal);

            modal.querySelector('#reservationForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                const formData = new FormData(this);

                try {
                    const response = await fetch('reserve_seat.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();

                    if (data.success) {
                        const qrData = `Seat: ${seatLabel}\nEvent: ${currentEvent.event_name}\nSeat ID: ${seatId}`;
                        const qrCodeDataUrl = await generateQRCode(qrData);
                        
                        alert(`Seat reserved successfully!\nSeat: ${seatLabel}\nEvent: ${currentEvent.event_name}`);
                        downloadQRCode(qrCodeDataUrl, `QR_Code_${seatLabel}.png`);

                        document.body.removeChild(modal);
                        // Update the seat appearance to show it's reserved
                        const seatElement = document.querySelector(`.seat .seat-container:has(.seat-label:contains('${seatLabel}'))`);
                        if (seatElement) {
                            seatElement.style.backgroundColor = '#48bb78';
                            seatElement.closest('.seat').style.pointerEvents = 'none';
                        }
                    } else {
                        alert('Reservation failed: ' + data.message);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while processing your reservation. Please try again.');
                }
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    document.body.removeChild(modal);
                }
            });
        }

        async function generateQRCode(data) {
            return new Promise((resolve, reject) => {
                const qrContainer = document.createElement('div');
                new QRCode(qrContainer, {
                    text: data,
                    width: 128,
                    height: 128,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });

                const qrImage = qrContainer.querySelector('img');
                if (qrImage) {
                    qrImage.onload = () => resolve(qrImage.src);
                    qrImage.onerror = reject;
                } else {
                    reject(new Error('Failed to generate QR code'));
                }
            });
        }

        function downloadQRCode(dataUrl, fileName) {
            const link = document.createElement('a');
            link.href = dataUrl;
            link.download = fileName;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
</body>
</html>

