<?php
// Include the database connection
include 'db_connection.php';

$message = ""; // Variable to store feedback messages

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize user inputs
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password']; // Don't sanitize password before hashing

    if (empty($username) || empty($password)) {
        $message = "Both fields are required.";
    } else {
        // Check if the connection was successful
        if ($conn->connect_error) {
            $message = "Connection failed: " . $conn->connect_error;
        } else {
            // Check if the username already exists
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Username already exists
                $message = "Username already taken. Please choose another.";
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert the new user into the database
                $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
                $stmt->bind_param("ss", $username, $hashed_password);
                if ($stmt->execute()) {
                    $message = "Registration successful! You can now log in.";
                } else {
                    $message = "Error during registration. Please try again.";
                }
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
    <title>Register Form</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-black text-white h-screen flex items-center justify-center">
    <?php if (!empty($message)): ?>
        <!-- Display feedback messages -->
        <div class="absolute top-4 left-4 bg-red-500 text-white p-4 rounded-md">
            <?= $message ?>
        </div>
    <?php endif; ?>
    <div class="flex flex-col md:flex-row items-center space-x-6 max-w-4xl">
        <!-- Left Section -->
        <div class="w-full md:w-1/2">
            <img src="../../images/pexels.jpg" alt="Seats" class="rounded-lg shadow-lg">
        </div>

        <!-- Right Section -->
        <div class="w-full md:w-1/2 p-8 border-2 border-purple-500 rounded-lg">
            <h2 class="text-3xl font-bold mb-6">Register Form</h2>
            <form action="" method="POST" class="space-y-4">
                <div>
                    <label for="username" class="block text-lg">Username:</label>
                    <input type="text" id="username" name="username" class="w-full px-4 py-2 mt-2 bg-white text-black rounded-md border focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                </div>
                <div>
                    <label for="password" class="block text-lg">Password:</label>
                    <input type="password" id="password" name="password" class="w-full px-4 py-2 mt-2 bg-white text-black rounded-md border focus:outline-none focus:ring-2 focus:ring-purple-500" required>
                </div>
                <button type="submit" class="w-full px-4 py-2 mt-4 bg-purple-500 text-white font-semibold rounded-md hover:bg-purple-600">
                    Register
                </button>
            </form>
            <div class="mt-4 text-center">
                <p>Already have an account?</p>
                <a href="login.php" class="text-purple-500 hover:text-purple-600">Login here</a>
            </div>
        </div>
    </div>
</body>
</html>

