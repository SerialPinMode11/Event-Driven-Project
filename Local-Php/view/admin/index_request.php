<?php
include '../auth/db_connection.php';

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

// Fetch all reservations from the database
$reservations = [];
$reservation_sql = "SELECT * FROM reservations ORDER BY id DESC";
$reservation_result = $conn->query($reservation_sql);

if ($reservation_result && $reservation_result->num_rows > 0) {
    while ($row = $reservation_result->fetch_assoc()) {
        $reservations[] = $row;
    }
} else {
    $message .= " No reservations found in the database.";
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
    <style>
        .seat {
            width: 40px;
            height: 40px;
            background-color: #22c55e;
            color: white;
            font-weight: bold;
            text-align: center;
            line-height: 40px;
            margin: 2px;
            border-radius: 4px;
            cursor: pointer;
        }
        .seat-row {
            display: flex;
            justify-content: center;
        }
        .side-nav {
            width: 300px;
            height: 100vh;
            overflow-y: auto;
            padding: 20px;
            background-color: #1f2937;
        }
        .main-content {
            flex: 1;
            padding: 20px;
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen flex">
    <div class="side-nav">
        <h2 class="text-2xl font-bold mb-4">Reservations</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr>
                        <th class="px-2 py-1 text-left">Event ID</th>
                        <th class="px-2 py-1 text-left">Seat ID</th>
                        <th class="px-2 py-1 text-left">Name</th>
                        <th class="px-2 py-1 text-left">Arrival Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reservations as $reservation): ?>
                        <tr>
                            <td class="px-2 py-1"><?php echo htmlspecialchars($reservation['event_id']); ?></td>
                            <td class="px-2 py-1"><?php echo htmlspecialchars($reservation['seat_id']); ?></td>
                            <td class="px-2 py-1"><?php echo htmlspecialchars($reservation['name']); ?></td>
                            <td class="px-2 py-1"><?php echo htmlspecialchars($reservation['arrival_time']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="main-content">
        <h1 class="text-3xl font-bold mb-6">Event Seating Arrangements</h1>

        <?php if (!empty($message)): ?>
            <div class="mb-4 p-4 bg-red-500 text-white rounded-lg">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <div class="w-full max-w-4xl">
            <table class="w-full bg-gray-800 rounded-lg overflow-hidden">
                <thead>
                    <tr class="bg-gray-700">
                        <th class="px-4 py-2 text-left">Event Code</th>
                        <th class="px-4 py-2 text-left">Gathering Name</th>
                        <th class="px-4 py-2 text-left">Total Seats</th>
                        <th class="px-4 py-2 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr class="border-t border-gray-700">
                        <td class="px-4 py-2"><?php echo htmlspecialchars($event['id']); ?></td>
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

        <div id="seatContainer" class="mt-8 hidden">
            <h2 id="eventName" class="text-2xl font-bold mb-4"></h2>
            <div id="seatLayout"></div>
        </div>

        <a href="manual\index.html" class="mt-6 inline-block px-4 py-2 bg-orange-500 text-white font-semibold rounded-md hover:bg-orange-600">
            MANUAL CUSTOMER RESERVATION
        </a>
        <a href="../auth/login.php" class="mt-6 inline-block px-4 py-2 bg-orange-500 text-white font-semibold rounded-md hover:bg-orange-600">
            Log Out
        </a>
    </div>

    <script>
        function showSeats(event) {
            const seatContainer = document.getElementById('seatContainer');
            const eventNameElement = document.getElementById('eventName');
            const seatLayout = document.getElementById('seatLayout');

            eventNameElement.textContent = `Seat Layout for "${event.event_name}"`;
            seatLayout.innerHTML = '';

            const totalSeats = parseInt(event.total_seat);
            const rows = parseInt(event.row_seat);
            const columns = parseInt(event.column_seat);

            let seatCount = 0;
            for (let i = 0; i < rows; i++) {
                const rowDiv = document.createElement('div');
                rowDiv.className = 'seat-row';
                for (let j = 0; j < columns; j++) {
                    if (seatCount < totalSeats) {
                        const seatDiv = document.createElement('div');
                        seatDiv.className = 'seat';
                        seatDiv.textContent = `${String.fromCharCode(65 + i)}${j + 1}`;
                        rowDiv.appendChild(seatDiv);
                        seatCount++;
                    }
                }
                seatLayout.appendChild(rowDiv);
            }

            seatContainer.classList.remove('hidden');
        }
    </script>
</body>
</html>