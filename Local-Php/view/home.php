<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pat's Seat Reservation</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-900 to-black text-white min-h-screen font-['Inter',sans-serif]">
    <div class="flex flex-col md:flex-row items-center justify-between h-screen p-8">
        <!-- Left Section -->
        <div class="w-full md:w-1/2 space-y-6">
            <h1 class="text-5xl font-bold">Pat's Seat Reservation</h1>
            <p class="text-xl">Make your Gathering Special!</p>
            <div class="text-lg space-y-1">
                <p>+63967-082-2877</p>
                <p>San Antonio Riverside Victoria,</p>
                <p>Oriental Mindoro</p>
                <p>Patulot's Store</p>
            </div>
            <a href="auth/login.php" class="px-6 py-3 mt-4 inline-block bg-orange-600 text-black font-semibold rounded-md hover:bg-orange-500">
                Log In Now for Reservation
            </a>
        </div>

        <!-- Right Section -->
        <div class="w-full md:w-1/2 flex items-center justify-center">
            <img src="../images/pexels.jpg" alt="Seats" class="rounded-md shadow-lg">
        </div>
    </div>
</body>
</html>
