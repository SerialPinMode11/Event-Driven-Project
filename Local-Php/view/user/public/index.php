<?php
include '../../auth/db_connection.php';

$message = ""; // Variable to store feedback messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user inputs
    $name = mysqli_real_escape_string($conn, $_POST['reservee']);
    $seat = intval($_POST['totalSeats']);
    $date = mysqli_real_escape_string($conn, $_POST['pickupDate']);
    $mode = mysqli_real_escape_string($conn, $_POST['transportation']);

    if (empty($name) || empty($seat) || empty($date) || empty($mode)) {
        $message = "All fields are required.";
    } 
    else {
        // Check if the connection was successful
        if ($conn->connect_error) {
            $message = "Connection failed: " . $conn->connect_error;
        } else {
            // Check if the name already exists
           
                // Insert new reservation
                $stmt = $conn->prepare("INSERT INTO public_reservations (name, seat, date, modeoftransportation) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("siss", $name, $seat, $date, $mode);
                
                if ($stmt->execute()) {
                    $message = "Reservation submitted successfully!";
                    header("Location: payment.php");
                } else {
                    $message = "Error: " . $stmt->error;
                }
            }
            $stmt->close();
        
        }
        
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Holder - Public</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
    </style>
</head>

<body class="bg-gradient-to-br from-gray-900 to-black text-white min-h-screen font-['Inter',sans-serif]">
    <div class="container mx-auto px-4 min-h-screen flex flex-col">
        <!-- Navbar -->
        <header class="flex justify-between items-center py-6">
            <h1 class="text-3xl font-bold bg-gradient-to-r from-orange-500 to-red-600 bg-clip-text text-transparent">Event-Holder</h1>
            <nav>
                <ul class="flex space-x-6 text-sm font-medium">
                    <li><a href="../index.php" class="text-white/80 hover:text-white transition-colors">Home</a></li>
                    <li><a href="#" class="rounded-full bg-white/10 px-4 py-2 text-white/80 hover:text-white transition-colors">Public</a></li>
                    <li><a href="#" class="text-white/80 hover:text-white transition-colors">Profile</a></li>
                </ul>
            </nav>
        </header>

        <!-- Main Content -->
        <div class="flex flex-col lg:flex-row justify-between items-center my-10 flex-grow">
            <!-- Left Section: Form -->
            <div class="w-full lg:w-1/2 p-8 border border-white/10 bg-white/5 rounded-2xl shadow-xl backdrop-blur-sm">
                <h2 class="text-3xl font-bold mb-8 text-center lg:text-left">Public Reservation Form</h2>
                <?php if (!empty($message)): ?>
                    <div class="mb-4 p-4 bg-orange-500 text-white rounded-lg">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" class="space-y-6">
                    <div>
                        <label for="reservee" class="block text-sm font-medium text-white/80 mb-2">Name of Reservee</label>
                        <input
                            type="text"
                            id="reservee"
                            name="reservee"
                            class="w-full border border-white/10 bg-white/5 text-white placeholder:text-white/40 rounded-lg p-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            placeholder="Enter your name"
                            required>
                    </div>
                    <div>
                        <label for="totalSeats" class="block text-sm font-medium text-white/80 mb-2">Total Number of Seats</label>
                        <input
                            type="number"
                            id="totalSeats"
                            name="totalSeats"
                            class="w-full border border-white/10 bg-white/5 text-white placeholder:text-white/40 rounded-lg p-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            placeholder="Enter the total number of seats"
                            required>
                    </div>
                    <div>
                        <label for="pickupDate" class="block text-sm font-medium text-white/80 mb-2">Date of Pick Up</label>
                        <input
                            type="date"
                            id="pickupDate"
                            name="pickupDate"
                            class="w-full border border-white/10 bg-white/5 text-white placeholder:text-white/40 rounded-lg p-3 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition"
                            required>
                    </div>
                    <div class="space-y-3">
                        <label class="block text-sm font-medium text-white/80 mb-2">Mode of Transportation</label>
                        <div class="flex flex-col space-y-2">
                            <label class="inline-flex items-center">
                                <input
                                    type="radio"
                                    name="transportation"
                                    value="Customer's Transportation"
                                    class="form-radio text-orange-500 focus:ring-orange-500 focus:ring-offset-gray-900"
                                    required>
                                <span class="ml-2 text-white">Customer's Transportation</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input
                                    type="radio"
                                    name="transportation"
                                    value="Pat's Transportation"
                                    class="form-radio text-orange-500 focus:ring-orange-500 focus:ring-offset-gray-900"
                                    required>
                                <span class="ml-2 text-white">Pat's Transportation</span>
                            </label>
                        </div>
                    </div>
                    <button
                        type="submit"
                        class="w-full px-6 py-3 mt-6 bg-gradient-to-r from-orange-500 to-red-600 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:ring-offset-gray-900 transition-all duration-300 transform hover:scale-105">
                        Submit Request
                    </button>
                </form>
            </div>

            <!-- Right Section: Image -->
            <div class="w-full lg:w-1/2 mt-10 lg:mt-0 flex justify-center lg:justify-end">
                <div class="relative w-full max-w-md lg:max-w-lg xl:max-w-xl">
                    <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-50 rounded-2xl"></div>
                    <img src="../../../images/redpex.jpg" alt="Event Seats" class="rounded-2xl shadow-2xl w-full h-auto object-cover">
                </div>
            </div>
        </div>
    </div>
</body>

</html>

