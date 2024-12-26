<?php
include '../../auth/db_connection.php';

$message = ""; // Variable to store feedback messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user inputs
    $name = mysqli_real_escape_string($conn, $_POST['gathering_name']);
    $seat = intval($_POST['total_seats']);
    $row = intval($_POST['seats_per_row']);
    $column = intval($_POST['seats_per_column']);

    if (empty($name) || empty($seat) || empty($row) || empty($column)) {
        $message = "All fields are required.";
    } 
    else {
        // Check if the connection was successful
        if ($conn->connect_error) {
            $message = "Connection failed: " . $conn->connect_error;
        } else {
            // Insert new reservation
            $stmt = $conn->prepare("INSERT INTO private_seat (event_name, total_seat, row_seat, column_seat) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("siii", $name, $seat, $row, $column);
            
            if ($stmt->execute()) {
                $message = "Seat Reserve Successfully!";
                // Redirect to seatview.php with event details
                header("Location: seatview.php?event_name=" . urlencode($name) . "&total_seats=$seat&rows=$row&columns=$column");
                exit();
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Holder - Private</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-900 to-black text-white min-h-screen flex flex-col items-center justify-center">
    <!-- Navigation -->
    <div class="absolute top-4 left-4 text-white">
        <h1 class="text-2xl font-bold">Event-Holder</h1>
        <nav class="mt-2">
            <a href="../index.php" class="text-sm text-gray-300 hover:underline">Home</a>
            <span class="text-gray-400 mx-2">/</span>
            <a href="#" class="text-sm text-gray-300 hover:underline">Private</a>
        </nav>
    </div>
    <!-- Profile Section -->
    <div class="absolute top-4 right-4">
        <a href="#" class="text-white text-sm hover:underline">Profile</a>
    </div>
    <!-- Main Content -->
    <div class="flex flex-col md:flex-row items-center space-x-6 max-w-4xl">
        <!-- Left Section -->
        <div class="w-full md:w-2/3 p-8 border-2 border-white/10 rounded-lg">
            <h2 class="text-3xl font-bold mb-6">Name of the Gathering</h2>
            <?php if (!empty($message)): ?>
                <div class="mb-4 p-4 bg-orange-500 text-white rounded-lg">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="space-y-4">
                <!-- Form fields remain the same -->
                <div>
                    <label for="gathering_name" class="block text-lg">Name of the Gathering:</label>
                    <input type="text" id="gathering_name" name="gathering_name" class="w-full px-4 py-2 mt-2 bg-white text-black rounded-md border focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                </div>
                <div>
                    <label for="total_seats" class="block text-lg">Total number of Seats:</label>
                    <input type="number" id="total_seats" name="total_seats" class="w-full px-4 py-2 mt-2 bg-white text-black rounded-md border focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                </div>
                <div>
                    <label for="seats_per_row" class="block text-lg">Number of Seats per Row:</label>
                    <input type="number" id="seats_per_row" name="seats_per_row" class="w-full px-4 py-2 mt-2 bg-white text-black rounded-md border focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                </div>
                <div>
                    <label for="seats_per_column" class="block text-lg">Number of Seats per Column:</label>
                    <input type="number" id="seats_per_column" name="seats_per_column" class="w-full px-4 py-2 mt-2 bg-white text-black rounded-md border focus:outline-none focus:ring-2 focus:ring-orange-500" required>
                </div>
                <button type="submit" class="w-full px-4 py-2 mt-4 bg-orange-500 text-white font-semibold rounded-md hover:bg-orange-600">
                    Submit Request
                </button>
            </form>
        </div>
        <!-- Right Section -->
        <div class="w-full md:w-1/3">
            <img src="../../../images/redpex.jpg" alt="Seats" class="rounded-lg shadow-lg">
            <a href="seatview.php" class="block w-full px-4 py-2 mt-4 bg-orange-500 text-white font-semibold rounded-md hover:bg-orange-600 text-center">
                View Seats
            </a>
        </div>
    </div>
</body>
</html>

