<?php
// Start a session to manage user login state.
session_start();

// Define a variable for the page title.
$pageTitle = "Admin Login - Titanic Collection";
$errorMessage = "";

// Check if the form has been submitted.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // In a real application, you would connect to a database and validate the user's credentials.
    // For this example, we'll use hardcoded credentials for demonstration purposes.
    $validUsername = "admin";
    $validPassword = "password123"; // In a real application, this should be a hashed password from a database.

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    // Sanitize and validate the input.
    $username = htmlspecialchars(trim($username));
    $password = htmlspecialchars(trim($password));

    // Check if the provided credentials match.
    if ($username === $validUsername && $password === $validPassword) {
        // Authentication successful. Store user data in the session.
        $_SESSION['user_id'] = 1; // Example user ID
        $_SESSION['username'] = $username;
        $_SESSION['role'] = 'admin';

        // Redirect the admin to the dashboard.
        header("Location: admin/admindashboard.php");
        exit();
    } else {
        // Authentication failed. Set an error message.
        $errorMessage = "Invalid username or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <!-- Include Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md p-8 space-y-6 bg-white rounded-xl shadow-lg">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-900">Admin Login</h2>
            <p class="mt-2 text-sm text-gray-600">Sign in to your Titanic Collection account</p>
        </div>

        <?php if (!empty($errorMessage)): ?>
            <div class="p-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php endif; ?>

        <form class="space-y-6" action="adminlogin.php" method="POST">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <div class="mt-1">
                    <input id="username" name="username" type="text" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="mt-1">
                    <input id="password" name="password" type="password" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>
            <div>
                <button type="submit"
                        class="w-full px-4 py-2 text-white bg-indigo-600 rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Sign in
                </button>
            </div>
        </form>
    </div>
</body>
</html>
