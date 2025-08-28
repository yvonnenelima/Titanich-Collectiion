<?php
// order_error.php
$error = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : 'An error occurred while processing your order.';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Error</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
        <div class="mb-6">
            <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Order Error</h1>
            <p class="text-gray-600"><?php echo $error; ?></p>
        </div>
        
        <div class="space-y-3">
            <button onclick="history.back()" class="block w-full bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 transition-colors">
                Try Again
            </button>
            <a href="tel:0717717985" class="block w-full bg-gray-200 text-gray-800 py-3 px-6 rounded-md hover:bg-gray-300 transition-colors">
                <i class="fas fa-phone mr-2"></i>Call for Help: 0741421583
            </a>
        </div>
    </div>
</body>
</html>