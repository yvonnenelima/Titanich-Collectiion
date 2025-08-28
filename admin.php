<?php
// Start a session to handle user authentication and data persistence
session_start();

// Initialize session data if not exists (simulates a database)
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [
        ['id' => 1, 'name' => 'Rose Necklace', 'price' => '1200', 'stock' => 15, 'image' => 'https://placehold.co/300x300/A088A0/FFFFFF?text=Rose+Necklace', 'created_at' => '2025-08-09 10:00:00'],
        ['id' => 2, 'name' => 'Pearl Earrings', 'price' => '850', 'stock' => 8, 'image' => 'https://placehold.co/300x300/F0E6D8/000000?text=Pearl+Earrings', 'created_at' => '2025-08-09 11:00:00'],
        ['id' => 3, 'name' => 'Silver Bracelet', 'price' => '1500', 'stock' => 12, 'image' => 'https://placehold.co/300x300/C0C0C0/000000?text=Silver+Bracelet', 'created_at' => '2025-08-09 12:00:00'],
        ['id' => 4, 'name' => 'Diamond Ring', 'price' => '2500', 'stock' => 5, 'image' => 'https://placehold.co/300x300/B8860B/FFFFFF?text=Diamond+Ring', 'created_at' => '2025-08-09 13:00:00'],
        ['id' => 5, 'name' => 'Gold Watch', 'price' => '3200', 'stock' => 3, 'image' => 'https://placehold.co/300x300/FFD700/000000?text=Gold+Watch', 'created_at' => '2025-08-09 14:00:00']
    ];
}

if (!isset($_SESSION['orders'])) {
    $_SESSION['orders'] = [
        ['id' => 101, 'customer' => 'John Doe', 'product' => 'Rose Necklace', 'product_id' => 1, 'amount' => 1200, 'status' => 'Pending', 'date' => '2025-08-10 10:30:00', 'phone' => '0712345678', 'email' => 'john@email.com', 'address' => 'Nairobi, Kenya'],
        ['id' => 102, 'customer' => 'Jane Smith', 'product' => 'Pearl Earrings', 'product_id' => 2, 'amount' => 850, 'status' => 'Shipped', 'date' => '2025-08-10 09:15:00', 'phone' => '0723456789', 'email' => 'jane@email.com', 'address' => 'Mombasa, Kenya'],
        ['id' => 103, 'customer' => 'Peter Jones', 'product' => 'Silver Bracelet', 'product_id' => 3, 'amount' => 1500, 'status' => 'Pending', 'date' => '2025-08-10 11:45:00', 'phone' => '0734567890', 'email' => 'peter@email.com', 'address' => 'Kisumu, Kenya'],
        ['id' => 104, 'customer' => 'Mary Wilson', 'product' => 'Diamond Ring', 'product_id' => 4, 'amount' => 2500, 'status' => 'Processing', 'date' => '2025-08-10 08:20:00', 'phone' => '0745678901', 'email' => 'mary@email.com', 'address' => 'Nakuru, Kenya'],
        ['id' => 105, 'customer' => 'David Brown', 'product' => 'Gold Watch', 'product_id' => 5, 'amount' => 3200, 'status' => 'Delivered', 'date' => '2025-08-09 16:30:00', 'phone' => '0756789012', 'email' => 'david@email.com', 'address' => 'Eldoret, Kenya'],
        ['id' => 106, 'customer' => 'Sarah Davis', 'product' => 'Rose Necklace', 'product_id' => 1, 'amount' => 1200, 'status' => 'Pending', 'date' => '2025-08-10 12:10:00', 'phone' => '0767890123', 'email' => 'sarah@email.com', 'address' => 'Thika, Kenya']
    ];
}

// Initialize next IDs
if (!isset($_SESSION['next_product_id'])) {
    $_SESSION['next_product_id'] = 6;
}
if (!isset($_SESSION['next_order_id'])) {
    $_SESSION['next_order_id'] = 107;
}

$success_message = '';
$error_message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // Add new product
    if (isset($_POST['add_product'])) {
        $product_name = trim($_POST['product_name']);
        $product_price = floatval($_POST['product_price']);
        $product_stock = intval($_POST['product_stock'] ?? 10);
        $product_image = '';
        
        // Validate inputs
        if (empty($product_name) || $product_price <= 0) {
            $error_message = "Please enter valid product name and price.";
        } else {
            // Handle image upload or URL
            // This is the key section. The PHP backend is designed to accept a URL,
            // not a pasted image file.
            if (!empty($_POST['product_image_url'])) {
                // We check if the provided string is a valid URL
                $product_image = filter_var($_POST['product_image_url'], FILTER_VALIDATE_URL) 
                    ? $_POST['product_image_url'] 
                    : 'https://placehold.co/300x300/A088A0/FFFFFF?text=' . urlencode($product_name);
            } else {
                // If no URL is provided, we use a placeholder image
                $product_image = 'https://placehold.co/300x300/A088A0/FFFFFF?text=' . urlencode($product_name);
            }
            
            // Add product to session
            $new_product = [
                'id' => $_SESSION['next_product_id']++,
                'name' => $product_name,
                'price' => $product_price,
                'stock' => $product_stock,
                'image' => $product_image,
                'created_at' => date('Y-m-d H:i:s')
            ];
            
            $_SESSION['products'][] = $new_product;
            $success_message = "Product '$product_name' added successfully! It will appear on the homepage.";
        }
    }
    
    // Remove product
    if (isset($_POST['remove_product'])) {
        $product_id = intval($_POST['product_id']);
        $product_name = '';
        
        foreach ($_SESSION['products'] as $key => $product) {
            if ($product['id'] == $product_id) {
                $product_name = $product['name'];
                unset($_SESSION['products'][$key]);
                break;
            }
        }
        
        // Reindex array
        $_SESSION['products'] = array_values($_SESSION['products']);
        $success_message = $product_name ? "Product '$product_name' removed successfully!" : "Product not found.";
    }
    
    // Update product stock
    if (isset($_POST['update_stock'])) {
        $product_id = intval($_POST['product_id']);
        $new_stock = intval($_POST['new_stock']);
        
        foreach ($_SESSION['products'] as $key => $product) {
            if ($product['id'] == $product_id) {
                $_SESSION['products'][$key]['stock'] = $new_stock;
                $success_message = "Stock updated for '{$product['name']}'!";
                break;
            }
        }
    }
    
    // Update order status
    if (isset($_POST['update_order_status'])) {
        $order_id = intval($_POST['order_id']);
        $new_status = trim($_POST['new_status']);
        
        $valid_statuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
        
        if (in_array($new_status, $valid_statuses)) {
            foreach ($_SESSION['orders'] as $key => $order) {
                if ($order['id'] == $order_id) {
                    $_SESSION['orders'][$key]['status'] = $new_status;
                    $_SESSION['orders'][$key]['updated_at'] = date('Y-m-d H:i:s');
                    $success_message = "Order #{$order_id} status updated to '$new_status'!";
                    break;
                }
            }
        } else {
            $error_message = "Invalid order status.";
        }
    }
    
    // Add new order (for testing purposes)
    if (isset($_POST['add_test_order'])) {
        $customer_name = trim($_POST['customer_name']);
        $customer_phone = trim($_POST['customer_phone']);
        $customer_email = trim($_POST['customer_email']);
        $customer_address = trim($_POST['customer_address']);
        $product_id = intval($_POST['product_id']);
        
        // Find product
        $product = null;
        foreach ($_SESSION['products'] as $p) {
            if ($p['id'] == $product_id) {
                $product = $p;
                break;
            }
        }
        
        if ($product && !empty($customer_name) && !empty($customer_phone)) {
            $new_order = [
                'id' => $_SESSION['next_order_id']++,
                'customer' => $customer_name,
                'product' => $product['name'],
                'product_id' => $product_id,
                'amount' => $product['price'],
                'status' => 'Pending',
                'date' => date('Y-m-d H:i:s'),
                'phone' => $customer_phone,
                'email' => $customer_email,
                'address' => $customer_address
            ];
            
            $_SESSION['orders'][] = $new_order;
            $success_message = "Test order created successfully for $customer_name!";
        } else {
            $error_message = "Please fill in all required fields and select a valid product.";
        }
    }
    
    // Cancel order
    if (isset($_POST['cancel_order'])) {
        $order_id = intval($_POST['order_id']);
        
        foreach ($_SESSION['orders'] as $key => $order) {
            if ($order['id'] == $order_id) {
                $_SESSION['orders'][$key]['status'] = 'Cancelled';
                $_SESSION['orders'][$key]['updated_at'] = date('Y-m-d H:i:s');
                $success_message = "Order #{$order_id} has been cancelled.";
                break;
            }
        }
    }
}

// Get current data
$products = $_SESSION['products'];
$orders = $_SESSION['orders'];

// Define variables for dynamic content
$pageTitle = "Titanic Collection - Admin Dashboard";
$welcomeMessage = "Welcome to Titanic Collection Admin!";
$page = $_GET['page'] ?? 'dashboard';

// Calculate statistics
$totalOrders = count($orders);
$pendingOrders = array_filter($orders, function($order) { return $order['status'] === 'Pending'; });
$pendingCount = count($pendingOrders);
$processingOrders = array_filter($orders, function($order) { return $order['status'] === 'Processing'; });
$processingCount = count($processingOrders);
$shippedOrders = array_filter($orders, function($order) { return $order['status'] === 'Shipped'; });
$shippedCount = count($shippedOrders);
$deliveredOrders = array_filter($orders, function($order) { return $order['status'] === 'Delivered'; });
$deliveredCount = count($deliveredOrders);
$cancelledOrders = array_filter($orders, function($order) { return $order['status'] === 'Cancelled'; });
$cancelledCount = count($cancelledOrders);
$totalRevenue = array_sum(array_column(array_filter($orders, function($o) { return $o['status'] !== 'Cancelled'; }), 'amount'));
$totalProducts = count($products);
$lowStockProducts = array_filter($products, function($product) { return $product['stock'] <= 5; });
$lowStockCount = count($lowStockProducts);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <!-- Include Tailwind CSS via CDN for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        
        /* Custom styles for image paste functionality */
        .image-paste-area {
            border: 2px dashed #d1d5db;
            transition: all 0.3s ease;
        }
        
        .image-paste-area:hover,
        .image-paste-area.dragover {
            border-color: #6366f1;
            background-color: #f0f9ff;
        }
        
        .preview-image {
            max-width: 200px;
            max-height: 200px;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        /* Real-time updates indicator */
        .live-indicator {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: .5; }
        }
        
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: none;
            border-radius: 8px;
            width: 80%;
            max-width: 600px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }
        
        .close:hover,
        .close:focus {
            color: black;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
        }
        .status-pending { background-color: #fffbeb; color: #b45309; }
        .status-processing { background-color: #eff6ff; color: #1e40af; }
        .status-shipped { background-color: #f5f3ff; color: #5b21b6; }
        .status-delivered { background-color: #ecfdf5; color: #065f46; }
        .status-cancelled { background-color: #fef2f2; color: #991b1b; }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <!-- Header / Navigation Bar -->
    <header class="bg-white shadow-md">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="text-2xl font-bold text-gray-800">Titanic Collection</div>
                <span class="text-sm bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Admin Panel</span>
                <div class="flex items-center space-x-1 text-xs text-green-600">
                    <div class="w-2 h-2 bg-green-500 rounded-full live-indicator"></div>
                    <span>Live</span>
                </div>
            </div>
            <nav>
                <ul class="flex space-x-6">
                    <li><a href="?page=dashboard" class="text-gray-600 hover:text-gray-900 font-medium <?php echo $page === 'dashboard' ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : ''; ?>">Dashboard</a></li>
                    <li><a href="?page=products" class="text-gray-600 hover:text-gray-900 font-medium <?php echo $page === 'products' ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : ''; ?>">Products</a></li>
                    <li><a href="?page=orders" class="text-gray-600 hover:text-gray-900 font-medium <?php echo $page === 'orders' ? 'text-blue-600 border-b-2 border-blue-600 pb-1' : ''; ?>">Orders</a></li>
                    <li><a href="index.php" class="text-green-500 hover:text-green-700 font-medium">View Website</a></li>
                    <li><a href="logout.php" class="text-red-500 hover:text-red-700 font-medium">Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Success/Error Messages -->
    <?php if (!empty($success_message)): ?>
        <div class="max-w-7xl mx-auto px-6 pt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($success_message); ?></span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <svg class="fill-current h-6 w-6 text-green-500 cursor-pointer" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!empty($error_message)): ?>
        <div class="max-w-7xl mx-auto px-6 pt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
                <span class="absolute top-0 bottom-0 right-0 px-4 py-3" onclick="this.parentElement.style.display='none'">
                    <svg class="fill-current h-6 w-6 text-red-500 cursor-pointer" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                </span>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl mx-auto px-6 py-8 w-full">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <?php if ($page === 'dashboard'): ?>
                <!-- Dashboard Page Content -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-800"><?php echo htmlspecialchars($welcomeMessage); ?></h1>
                    <div class="flex space-x-3">
                        <button onclick="location.reload()" class="flex items-center space-x-2 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                            <i class="fa-solid fa-arrows-rotate"></i>
                            <span>Refresh</span>
                        </button>
                        <a href="index.php" target="_blank" class="flex items-center space-x-2 px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                            <i class="fa-solid fa-eye"></i>
                            <span>View Website</span>
                        </a>
                    </div>
                </div>
                
                <p class="text-gray-600 mb-8">Real-time overview of your Titanic Collection store performance and orders. Products added here automatically appear on the website homepage. Last updated: <span class="font-semibold"><?php echo date('M d, Y H:i:s'); ?></span></p>

                <!-- Real-time Statistics Widgets -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-6 rounded-lg shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium opacity-90">Total Orders</h4>
                                <p class="text-3xl font-bold"><?php echo $totalOrders; ?></p>
                                <p class="text-xs opacity-75"><?php echo $pendingCount; ?> pending</p>
                            </div>
                            <div class="text-blue-200">
                                <i class="fa-solid fa-cart-shopping fa-3x"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-6 rounded-lg shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium opacity-90">Total Products</h4>
                                <p class="text-3xl font-bold"><?php echo $totalProducts; ?></p>
                                <p class="text-xs opacity-75"><?php echo $lowStockCount; ?> low stock</p>
                            </div>
                            <div class="text-green-200">
                                <i class="fa-solid fa-box fa-3x"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 p-6 rounded-lg shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium opacity-90">Revenue</h4>
                                <p class="text-3xl font-bold">KSh <?php echo number_format($totalRevenue); ?></p>
                                <p class="text-xs opacity-75">All time</p>
                            </div>
                            <div class="text-yellow-200">
                                <i class="fa-solid fa-coins fa-3x"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-6 rounded-lg shadow-lg text-white">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-sm font-medium opacity-90">Avg Order</h4>
                                <p class="text-3xl font-bold">KSh <?php echo $totalOrders > 0 ? number_format($totalRevenue / $totalOrders) : '0'; ?></p>
                                <p class="text-xs opacity-75">Per order</p>
                            </div>
                            <div class="text-purple-200">
                                <i class="fa-solid fa-chart-line fa-3x"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Status Overview -->
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                        <div class="text-2xl font-bold text-yellow-600"><?php echo $pendingCount; ?></div>
                        <div class="text-sm text-yellow-700">Pending</div>
                    </div>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                        <div class="text-2xl font-bold text-blue-600"><?php echo $processingCount; ?></div>
                        <div class="text-sm text-blue-700">Processing</div>
                    </div>
                    <div class="bg-purple-50 border-l-4 border-purple-400 p-4 rounded">
                        <div class="text-2xl font-bold text-purple-600"><?php echo $shippedCount; ?></div>
                        <div class="text-sm text-purple-700">Shipped</div>
                    </div>
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded">
                        <div class="text-2xl font-bold text-green-600"><?php echo $deliveredCount; ?></div>
                        <div class="text-sm text-green-700">Delivered</div>
                    </div>
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded">
                        <div class="text-2xl font-bold text-red-600"><?php echo $cancelledCount; ?></div>
                        <div class="text-sm text-red-700">Cancelled</div>
                    </div>
                </div>

                <!-- Recent Orders and Low Stock Products -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Recent Orders Overview -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-2xl font-semibold">Recent Orders</h2>
                            <a href="?page=orders" class="text-blue-600 hover:text-blue-800 font-medium">View All Orders →</a>
                        </div>
                        <div class="space-y-3">
                            <?php 
                            $recentOrders = array_slice(array_reverse($orders), 0, 5);
                            foreach ($recentOrders as $order): 
                            ?>
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm">
                                    <div>
                                        <div class="font-medium text-gray-900">#<?php echo $order['id']; ?> - <?php echo htmlspecialchars($order['customer']); ?></div>
                                        <div class="text-sm text-gray-500"><?php echo htmlspecialchars($order['product']); ?> - KSh <?php echo number_format($order['amount']); ?></div>
                                    </div>
                                    <span class="px-2 py-1 text-xs rounded-full status-badge
                                        <?php 
                                            echo 'status-' . strtolower($order['status']);
                                        ?>">
                                        <?php echo htmlspecialchars($order['status']); ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                            <?php if (empty($recentOrders)): ?>
                                <p class="text-gray-500 text-center py-4">No orders yet.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Low Stock Products -->
                    <div class="bg-gray-50 p-6 rounded-lg shadow-inner">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-2xl font-semibold">Low Stock Alert</h2>
                            <a href="?page=products" class="text-blue-600 hover:text-blue-800 font-medium">Manage Products →</a>
                        </div>
                        <div class="space-y-3">
                            <?php foreach ($lowStockProducts as $product): ?>
                                <div class="flex items-center justify-between p-3 bg-white rounded-lg shadow-sm border-l-4 border-red-400">
                                    <div class="flex items-center space-x-3">
                                        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="w-10 h-10 rounded object-cover" onerror="this.onerror=null;this.src='https://placehold.co/100x100/E5E7EB/4B5563?text=Image+Error';">
                                        <div>
                                            <div class="font-medium text-gray-900"><?php echo htmlspecialchars($product['name']); ?></div>
                                            <div class="text-sm text-gray-500">KSh <?php echo number_format($product['price']); ?></div>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-red-600 font-bold"><?php echo $product['stock']; ?> left</div>
                                        <div class="text-xs text-gray-500">Low stock</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <?php if (empty($lowStockProducts)): ?>
                                <div class="text-center py-4">
                                    <svg class="mx-auto h-12 w-12 text-green-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <p class="text-green-600 font-medium">All products well stocked!</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            <?php elseif ($page === 'products'): ?>
                <!-- Product Management Page Content -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Product Management</h1>
                    <div class="text-sm text-gray-500">
                        Total: <span class="font-semibold"><?php echo $totalProducts; ?></span> products | 
                        Low stock: <span class="font-semibold text-red-600"><?php echo $lowStockCount; ?></span>
                    </div>
                </div>

                <!-- Add Product Form with Image Paste Support -->
                <div class="bg-gray-50 p-6 rounded-lg shadow-inner mb-8">
                    <h2 class="text-2xl font-semibold mb-4">Add New Product</h2>
                    <form action="?page=products" method="POST" enctype="multipart/form-data" class="space-y-6">
                        <input type="hidden" name="add_product" value="1">
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="product_name" class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
                                <input type="text" id="product_name" name="product_name" required 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Enter product name">
                            </div>
                            
                            <div>
                                <label for="product_price" class="block text-sm font-medium text-gray-700 mb-2">Product Price (KSh) *</label>
                                <input type="number" id="product_price" name="product_price" required min="0" step="0.01"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="0.00">
                            </div>
                            
                            <div>
                                <label for="product_stock" class="block text-sm font-medium text-gray-700 mb-2">Initial Stock</label>
                                <input type="number" id="product_stock" name="product_stock" min="0" value="10"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label for="product_image_url" class="block text-sm font-medium text-gray-700 mb-2">Product Image URL (or paste image)</label>
                            <div id="image-paste-area" class="image-paste-area flex flex-col items-center justify-center p-6 bg-white rounded-lg">
                                <input type="text" id="product_image_url" name="product_image_url" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    placeholder="Paste an image URL or a local image path">
                            </div>
                            <div id="image-preview" class="mt-4 flex justify-center"></div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                Add Product
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Existing Products List -->
                <div class="bg-white p-6 rounded-lg shadow-inner">
                    <h2 class="text-2xl font-semibold mb-4">Existing Products</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($products as $product): ?>
                            <div class="flex flex-col border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="w-full h-48 object-cover" 
                                     onerror="this.onerror=null;this.src='https://placehold.co/300x300/E5E7EB/4B5563?text=Image+Error';">
                                <div class="p-4 flex flex-col flex-grow">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-lg font-bold text-gray-800"><?php echo htmlspecialchars($product['name']); ?></h3>
                                        <div class="text-sm font-semibold text-gray-600">KSh <?php echo number_format($product['price']); ?></div>
                                    </div>
                                    <div class="text-sm text-gray-500 mb-4">Stock: <span class="font-bold <?php echo $product['stock'] <= 5 ? 'text-red-500' : 'text-green-600'; ?>"><?php echo $product['stock']; ?></span></div>
                                    
                                    <div class="mt-auto flex space-x-2">
                                        <!-- Update Stock Form -->
                                        <form action="?page=products" method="POST" class="flex-grow flex items-center space-x-2">
                                            <input type="hidden" name="update_stock" value="1">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <input type="number" name="new_stock" value="<?php echo $product['stock']; ?>" min="0" 
                                                   class="w-20 px-2 py-1 text-sm border border-gray-300 rounded-lg focus:ring-1 focus:ring-blue-500">
                                            <button type="submit" class="p-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition-colors" title="Update Stock">
                                                <i class="fa-solid fa-sync"></i>
                                            </button>
                                        </form>
                                        <!-- Remove Product Form -->
                                        <form action="?page=products" method="POST">
                                            <input type="hidden" name="remove_product" value="1">
                                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                            <button type="submit" class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors" title="Remove Product" onclick="return confirm('Are you sure you want to remove this product?');">
                                                <i class="fa-solid fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <?php if (empty($products)): ?>
                            <div class="text-gray-500 text-center col-span-3 py-10">No products have been added yet.</div>
                        <?php endif; ?>
                    </div>
                </div>

            <?php elseif ($page === 'orders'): ?>
                <!-- Order Management Page Content -->
                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-3xl font-bold text-gray-800">Order Management</h1>
                    <div class="text-sm text-gray-500">
                        Total: <span class="font-semibold"><?php echo $totalOrders; ?></span> orders | 
                        Pending: <span class="font-semibold text-yellow-600"><?php echo $pendingCount; ?></span>
                    </div>
                </div>

                <!-- Orders List -->
                <div class="overflow-x-auto bg-gray-50 rounded-lg shadow-inner">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php 
                            $reversedOrders = array_reverse($orders);
                            foreach ($reversedOrders as $order): 
                            ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?php echo $order['id']; ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($order['customer']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($order['product']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">KSh <?php echo number_format($order['amount']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge <?php echo 'status-' . strtolower($order['status']); ?>">
                                            <?php echo htmlspecialchars($order['status']); ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?php echo htmlspecialchars($order['date']); ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex space-x-2">
                                            <button onclick="showDetailsModal(<?php echo htmlspecialchars(json_encode($order)); ?>)" class="text-blue-600 hover:text-blue-900 transition-colors" title="View Details"><i class="fa-solid fa-eye"></i></button>
                                            <button onclick="showUpdateStatusModal(<?php echo htmlspecialchars($order['id']); ?>)" class="text-yellow-600 hover:text-yellow-900 transition-colors" title="Update Status"><i class="fa-solid fa-pen-to-square"></i></button>
                                            <?php if ($order['status'] !== 'Cancelled'): ?>
                                                <form action="?page=orders" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                                                    <input type="hidden" name="cancel_order" value="1">
                                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                                    <button type="submit" class="text-red-600 hover:text-red-900 transition-colors" title="Cancel Order"><i class="fa-solid fa-ban"></i></button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($orders)): ?>
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">No orders have been placed yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

            <?php endif; ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 text-gray-600 text-center p-4 mt-8">
        © <?php echo date('Y'); ?> Titanic Collection. All rights reserved.
    </footer>

    <!-- Modals -->
    <div id="detailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('detailsModal')">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Order Details</h2>
            <div id="orderDetailsContent" class="space-y-2"></div>
        </div>
    </div>
    
    <div id="updateStatusModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('updateStatusModal')">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Update Order Status</h2>
            <form action="?page=orders" method="POST" class="space-y-4">
                <input type="hidden" name="update_order_status" value="1">
                <input type="hidden" id="statusOrderId" name="order_id">
                <div>
                    <label for="new_status" class="block text-sm font-medium text-gray-700">New Status</label>
                    <select id="new_status" name="new_status" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        <option value="Pending">Pending</option>
                        <option value="Processing">Processing</option>
                        <option value="Shipped">Shipped</option>
                        <option value="Delivered">Delivered</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">Update Status</button>
            </form>
        </div>
    </div>

    <!-- JavaScript for Modals and Image Preview -->
    <script>
        // Modal logic
        function showDetailsModal(order) {
            const content = document.getElementById('orderDetailsContent');
            content.innerHTML = `
                <p><strong>Order ID:</strong> #${order.id}</p>
                <p><strong>Customer:</strong> ${order.customer}</p>
                <p><strong>Product:</strong> ${order.product}</p>
                <p><strong>Amount:</strong> KSh ${order.amount.toLocaleString()}</p>
                <p><strong>Status:</strong> <span class="status-badge status-${order.status.toLowerCase()}">${order.status}</span></p>
                <p><strong>Date:</strong> ${order.date}</p>
                <p><strong>Phone:</strong> ${order.phone}</p>
                <p><strong>Email:</strong> ${order.email}</p>
                <p><strong>Address:</strong> ${order.address}</p>
            `;
            document.getElementById('detailsModal').style.display = 'block';
        }

        function showUpdateStatusModal(orderId) {
            document.getElementById('statusOrderId').value = orderId;
            document.getElementById('updateStatusModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }

        // Image preview logic for the product form
        document.addEventListener('DOMContentLoaded', () => {
            const imageUrlInput = document.getElementById('product_image_url');
            const imagePreviewDiv = document.getElementById('image-preview');

            function updateImagePreview(url) {
                imagePreviewDiv.innerHTML = '';
                if (url) {
                    const img = document.createElement('img');
                    img.src = url;
                    img.alt = 'Image Preview';
                    img.className = 'preview-image';
                    // Fallback in case the URL is broken
                    img.onerror = () => {
                        img.src = 'https://placehold.co/200x200/E5E7EB/4B5563?text=Invalid+URL';
                        img.alt = 'Invalid image URL';
                    };
                    imagePreviewDiv.appendChild(img);
                }
            }

            imageUrlInput.addEventListener('input', (event) => {
                const url = event.target.value;
                updateImagePreview(url);
            });
        });
    </script>
</body>
</html>
