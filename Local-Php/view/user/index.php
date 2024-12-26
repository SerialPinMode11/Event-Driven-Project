<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event-Holder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .gradient-text {
            background: linear-gradient(to right, #f97316, #dc2626);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-900 to-black text-white min-h-screen font-['Inter',sans-serif]">
    <div class="flex min-h-screen">

        <!-- Right Section -->
        <div class="relative w-1/3">
            <div class="absolute inset-0 bg-gradient-to-t from-black to-transparent"></div>
            <img
                src="../../images/redpex.jpg"
                alt="Theater seats"
                class="h-full w-full object-cover" />
        </div>

        <!-- Left Section -->
        <div class="flex w-2/3 flex-col p-8">
            <header class="flex items-center justify-between">
                <h1 class="text-4xl font-bold tracking-tight gradient-text">
                    Event-Holder
                </h1>
                <nav class="flex items-center gap-6">
                    <a href="#" class="rounded-full bg-white/10 px-4 py-2  text-sm font-medium text-white/80 transition-colors hover:text-white">
                        Home
                    </a>
                    <a href="#" class="text-sm font-medium text-white transition-colors hover:bg-white/20">
                        Profile
                    </a>
                    <a href="../auth/login.php" class="text-sm font-medium text-white transition-colors hover:bg-white/20">
                        Log Out
                    </a>
                </nav>
            </header>

            <main class="mt-20 flex flex-1 flex-col items-center justify-center">
                <div class="w-full max-w-2xl space-y-8 rounded-xl border border-white/10 bg-white/5 p-8 backdrop-blur">
                    <div class="space-y-2 text-center">
                        <h2 class="text-3xl font-bold tracking-tight text-white">
                            Ready for your Gathering?
                        </h2>
                        <p class="text-white/60">
                            Book your seats now for an unforgettable event experience
                        </p>
                    </div>

                    <div class="flex flex-col items-center gap-6">
                        <button onclick="window.location.href = 'private/pindex.php'" class="h-12 bg-orange-600 px-8 font-semibold text-white hover:bg-orange-700 rounded">
                            Reserve Seat Panel
                        </button>

                        <div class="w-full space-y-2">
                            <label for="reserve" class="block text-sm font-medium text-white/80">
                                Are you here to reserve?
                            </label>
                            <select
                                id="reserve"
                                class="w-full border-white/10 bg-white/5 text-black placeholder:text-white/40 rounded p-2"
                                onchange="handleReserveChange(this.value)">
                                <option value="" disabled selected class="text-white/40">-- Select an option --</option>
                                <option value="yes" class="text-black">Yes</option>
                                <option value="no" class="text-black">No</option>
                            </select>
                        </div>
                    </div>
                </div>
            </main>
        </div>


    </div>
</body>

<script>
        function handleReserveChange(value) {
            if (value === "yes") {
                window.location.href = 'private/pindex.php'; // Redirect to private page
            } else if (value === "no") {
                window.location.href = 'public/index.php'; // Redirect to public page
            }
        }
    </script>

</html>