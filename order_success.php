<?php
// order_success.php
session_start();
$order_id = isset($_GET['order_id']) ? htmlspecialchars($_GET['order_id']) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
        <div class="mb-6">
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-check text-green-600 text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Order Successful!</h1>
            <p class="text-gray-600">Thank you for your purchase</p>
        </div>
        
        <?php if ($order_id): ?>
            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                <p class="text-sm text-gray-600">Your Order ID</p>
                <p class="text-lg font-bold text-gray-900"><?php echo $order_id; ?></p>
            </div>
        <?php endif; ?>
        
        <div class="text-left text-sm text-gray-600 mb-6">
            <p class="mb-2">✓ Order received and being processed</p>
            <p class="mb-2">✓ You will receive a confirmation call/SMS</p>
            <p class="mb-2">✓ Delivery within 1-3 business days</p>
        </div>
        
        <div class="space-y-3">
            <a href="index.php" class="block w-full bg-blue-600 text-white py-3 px-6 rounded-md hover:bg-blue-700 transition-colors">
                Continue Shopping
            </a>
            <a href="tel:0717717985" class="block w-full bg-gray-200 text-gray-800 py-3 px-6 rounded-md hover:bg-gray-300 transition-colors">
                <i class="fas fa-phone mr-2"></i>Call Us: 0717717985
            </a>
        </div>
    </div>
</body>
</html>

<?php