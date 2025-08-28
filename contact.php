
<?php
// PHP script to generate an HTML contact page with specific details.
// This is a self-contained file that combines PHP and HTML.

// Define contact information variables
$companyName = "Titanic Collection";
$phoneNumber = "0741421583";
$emailAddress = "titanich2024@gmail.com";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $companyName; ?> - Contact Us</title>
    <!-- Load Tailwind CSS from CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Optional: Add custom fonts and basic body styling */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* Light gray background */
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">

    <div class="bg-white p-8 sm:p-10 md:p-12 rounded-2xl shadow-xl max-w-lg w-full text-center">
        <!-- Company name header -->
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-800 mb-6">
            Contact <?php echo $companyName; ?>
        </h1>

        <!-- Contact information card -->
        <div class="space-y-6">
            <!-- Phone number section -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-700 mb-2">Phone Number</h2>
                <a href="tel:<?php echo $phoneNumber; ?>" class="text-blue-600 hover:text-blue-800 text-lg sm:text-xl font-medium transition-colors duration-200">
                    <?php echo $phoneNumber; ?>
                </a>
            </div>

            <!-- Email address section -->
            <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-700 mb-2">Email Address</h2>
                <a href="mailto:<?php echo $emailAddress; ?>" class="text-blue-600 hover:text-blue-800 text-lg sm:text-xl font-medium transition-colors duration-200">
                    <?php echo $emailAddress; ?>
                </a>
            </div>
        </div>

        <!-- Optional: Add a friendly message or call to action -->
        <p class="mt-8 text-gray-600 text-sm sm:text-base">
            Feel free to reach out to us with any questions or inquiries!
        </p>
    </div>

</body>
</html>
