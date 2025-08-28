<?php
session_start();
include 'config.php';

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Initialize currency if not set
if (!isset($_SESSION['currency'])) {
    $_SESSION['currency'] = 'KSH'; // Default to Kenyan Shillings
}

// Currency conversion rates (127 KSH = 1 USD)
$exchange_rates = [
    'KSH' => 1,      // Base currency
    'USD' => 1/127   // 1 KSH = 1/127 USD
];

// Handle currency change
if (isset($_POST['change_currency'])) {
    $_SESSION['currency'] = $_POST['currency'];
}

// Handle add to cart
if (isset($_POST['add_to_cart'])) {
    $product_id = uniqid(); // In production, use actual product ID from database
    $name = $_POST['product_name'];
    $price = floatval($_POST['product_price']);
    $image = $_POST['product_image'];
    $size = $_POST['selected_size'];
    $quantity = intval($_POST['quantity']);
    
    // Check if product with same name and size already exists in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $name && $item['size'] === $size) {
            $item['quantity'] += $quantity;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'name' => $name,
            'price' => $price,
            'image' => $image,
            'size' => $size,
            'quantity' => $quantity
        ];
    }
    
    echo "<script>alert('Product added to cart successfully!');</script>";
}

// Get product details from URL parameters or database
$productName = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : '';
$productImage = isset($_GET['image']) ? htmlspecialchars($_GET['image']) : '';
$productPriceKSH = 0;
$productStock = 0;
$isAdminProduct = false;

// First, try to get product from admin products table
if ($productName && isset($conn) && $conn) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM products WHERE name = ? LIMIT 1");
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "s", $productName);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($product = mysqli_fetch_assoc($result)) {
            $productPriceKSH = floatval($product['price']);
            $productStock = intval($product['stock']);
            $productImage = $product['image'];
            $isAdminProduct = true;
        }
        mysqli_stmt_close($stmt);
    }
}

// If not found in admin products, use default products array
if ($productPriceKSH == 0) {
    $default_products = [
        'New Balance 530' => ['price' => 4500, 'image' => 'images/Nb.jpeg'],
        'Naked Wolfe' => ['price' => 4000, 'image' => 'images/Nw.jpeg'],
        'Jordan Retro 3' => ['price' => 3500, 'image' => 'images/Jr.jpeg'],
        'Airmax 95 Futura' => ['price' => 3000, 'image' => 'images/Am.jpeg'],
        'Wetlook' => ['price' => 6000, 'image' => 'images/Wetlook.jpg'],
        'Salvatore Ferragamo' => ['price' => 5500, 'image' => 'images/Salvatore Ferragamo.jpg'],
        'Adidas Samba' => ['price' => 4500, 'image' => 'images/Adidas Samba.jpg'],
        'Chelsea boots' => ['price' => 7000, 'image' => 'images/Chelsea boots.jpg'],
        'Jordan 4' => ['price' => 8500, 'image' => 'images/Jordan 4.jpg'],
        'Tommy' => ['price' => 4000, 'image' => 'images/Tommy.jpg'],
        'SB Dunk' => ['price' => 7500, 'image' => 'images/SB dunk.jpg'],
        'Versace Mules' => ['price' => 9000, 'image' => 'images/Versace Mules.jpg'],
    ];
    
    if (isset($default_products[$productName])) {
        $productPriceKSH = $default_products[$productName]['price'];
        $productImage = $default_products[$productName]['image'];
        $productStock = rand(5, 50); // Random stock for demo
        $isAdminProduct = false;
    } else {
        // Fallback if product not found
        $productName = 'Premium Shoe';
        $productPriceKSH = 7800;
        $productImage = 'images/default.jpg';
        $productStock = 10;
    }
}

// Convert price based on selected currency
$current_currency = $_SESSION['currency'];
$displayPrice = $productPriceKSH * $exchange_rates[$current_currency];
$currency_symbol = $current_currency === 'KSH' ? 'KSh' : '$';

$productDescription = "Premium quality shoe made with durable materials and comfortable design. Perfect for both casual and formal wear.";
$productSizes = ['36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46'];

// Function to convert price for display
function convertPrice($priceKSH, $currency, $rates) {
    $converted = $priceKSH * $rates[$currency];
    $symbol = $currency === 'KSH' ? 'KSh' : '$';
    return $symbol . number_format($converted, 2);
}

// Check stock status
$stockStatus = '';
$stockClass = '';
$outOfStock = false;

if ($productStock == 0) {
    $stockStatus = 'Out of Stock';
    $stockClass = 'text-red-600';
    $outOfStock = true;
} elseif ($productStock <= 5) {
    $stockStatus = 'Low Stock (' . $productStock . ' left)';
    $stockClass = 'text-yellow-600';
} else {
    $stockStatus = 'In Stock (' . $productStock . ' available)';
    $stockClass = 'text-green-600';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $productName; ?> - Product Details</title>
    <link rel="shortcut icon" href="https://img.icons8.com/fluent/48/000000/enter-2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #f7fafc;
        }
        .main-header {
            background: #40E0D0;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
            position: relative;
        }
        .nav-menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
        }
        .nav-menu .nav-links {
            display: flex;
            gap: 20px;
        }
        .nav-menu a {
            text-decoration: none;
            color: #FFFFFF;
            font-weight: bold;
            padding: 8px 12px;
        }
        .nav-menu a:hover {
            color: #333;
        }
        .cart-icon {
            position: relative;
            color: #FFFFFF;
            font-size: 1.5rem;
            cursor: pointer;
        }
        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #ff4444;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem;
            min-width: 18px;
            text-align: center;
        }
        .currency-selector {
            background: rgba(255,255,255,0.2);
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 4px;
            margin-left: 20px;
        }
        .product-page-container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        @media (min-width: 768px) {
            .product-page-container {
                flex-direction: row;
            }
        }
        .product-image {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        @media (min-width: 768px) {
            .product-image-container {
                flex: 1;
            }
            .product-details {
                flex: 1;
            }
        }
        .size-box {
            display: inline-block;
            padding: 0.5rem 1rem;
            border: 2px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
            transition: all 0.2s ease-in-out;
            background: white;
        }
        .size-box:hover {
            border-color: #666;
        }
        .size-box.selected {
            background-color: #000;
            color: #fff;
            border-color: #000;
        }
        .size-box.disabled {
            background-color: #f5f5f5;
            color: #999;
            cursor: not-allowed;
            border-color: #ddd;
        }
        .price-container {
            font-size: 2rem;
            font-weight: bold;
            color: #333;
            margin: 1rem 0;
        }
        .discount-price {
            color: #d9534f;
            text-decoration: line-through;
            font-weight: normal;
            font-size: 1.25rem;
            margin-right: 1rem;
        }
        .current-price {
            color: #28a745;
            font-weight: bold;
        }
        .add-to-cart-btn, .buy-now-btn {
            padding: 1rem 2rem;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
            transition: all 0.3s;
            cursor: pointer;
            border: none;
            width: 100%;
        }
        .add-to-cart-btn {
            background-color: #333;
            color: #fff;
        }
        .add-to-cart-btn:hover:not(:disabled) {
            background-color: #555;
        }
        .add-to-cart-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .buy-now-btn {
            background-color: #28a745;
            color: #fff;
        }
        .buy-now-btn:hover:not(:disabled) {
            background-color: #218838;
        }
        .buy-now-btn:disabled {
            background-color: #ccc;
            cursor: not-allowed;
        }
        .cart-sidebar {
            position: fixed;
            top: 0;
            right: -400px;
            width: 400px;
            height: 100vh;
            background: white;
            box-shadow: -2px 0 10px rgba(0,0,0,0.1);
            transition: right 0.3s ease;
            z-index: 1000;
            overflow-y: auto;
        }
        .cart-sidebar.open {
            right: 0;
        }
        .cart-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 999;
            display: none;
        }
        .cart-overlay.open {
            display: block;
        }
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }
        .modal.open {
            display: flex;
        }
        .modal-content {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        .product-badge {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            z-index: 10;
        }
        .stock-indicator {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: bold;
            margin: 1rem 0;
            display: inline-block;
        }
    </style>
</head>
<body class="antialiased">

<!-- Enhanced Header with Cart -->
<header class="main-header">
    <div class="nav-menu">
        <div class="nav-links">
            <a href="index.php">HOME</a>
            <a href="products.php">PRODUCTS</a>
            <a href="about.php">ABOUT</a>
            <a href="contact.php">CONTACT</a>
        </div>
        <div class="flex items-center">
            <!-- Currency Selector -->
            <form method="POST" style="display: inline;">
                <select name="currency" class="currency-selector" onchange="this.form.submit()">
                    <option value="KSH" <?php echo $_SESSION['currency'] === 'KSH' ? 'selected' : ''; ?>>KSH</option>
                    <option value="USD" <?php echo $_SESSION['currency'] === 'USD' ? 'selected' : ''; ?>>USD</option>
                </select>
                <input type="hidden" name="change_currency" value="1">
            </form>
            <!-- Cart Icon -->
            <div class="cart-icon" onclick="toggleCart()">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count" id="cart-count"><?php echo count($_SESSION['cart']); ?></span>
            </div>
        </div>
    </div>
</header>

<!-- Cart Sidebar -->
<div class="cart-overlay" id="cart-overlay" onclick="toggleCart()"></div>
<div class="cart-sidebar" id="cart-sidebar">
    <div class="p-4 border-b">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-bold">Shopping Cart</h3>
            <button onclick="toggleCart()" class="text-xl">&times;</button>
        </div>
    </div>
    <div class="p-4" id="cart-items">
        <?php if (empty($_SESSION['cart'])): ?>
            <p class="text-gray-500">Your cart is empty</p>
        <?php else: ?>
            <?php $total = 0; ?>
            <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                <?php $itemTotal = $item['price'] * $item['quantity'] * $exchange_rates[$current_currency]; ?>
                <?php $total += $itemTotal; ?>
                <div class="border-b pb-4 mb-4">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-semibold"><?php echo $item['name']; ?></h4>
                            <p class="text-sm text-gray-600">Size: <?php echo $item['size']; ?></p>
                            <p class="text-sm text-gray-600">Qty: <?php echo $item['quantity']; ?></p>
                            <p class="font-bold"><?php echo $currency_symbol . number_format($itemTotal, 2); ?></p>
                        </div>
                        <button onclick="removeFromCart(<?php echo $index; ?>)" class="text-red-500 ml-2">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="border-t pt-4">
                <div class="flex justify-between items-center mb-4">
                    <strong>Total: <?php echo $currency_symbol . number_format($total, 2); ?></strong>
                </div>
                <button onclick="openCheckout()" class="w-full bg-green-600 text-white py-2 rounded-md hover:bg-green-700">
                    Checkout
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Checkout Modal -->
<div class="modal" id="checkout-modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Checkout</h2>
            <button onclick="closeCheckout()" class="text-xl">&times;</button>
        </div>
        
        <form id="checkout-form" action="process_order.php" method="POST">
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Customer Information</label>
                <input type="text" name="customer_name" placeholder="Full Name" required class="w-full p-2 border rounded-md mb-2">
                <input type="email" name="customer_email" placeholder="Email" required class="w-full p-2 border rounded-md mb-2">
                <input type="tel" name="customer_phone" placeholder="Phone Number" required class="w-full p-2 border rounded-md">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Delivery Address</label>
                <textarea name="delivery_address" placeholder="Full Address" required class="w-full p-2 border rounded-md" rows="3"></textarea>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-bold mb-2">Payment Method</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" name="payment_method" value="mpesa" checked class="mr-2">
                        M-Pesa (Till Number: 8739678)
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="payment_method" value="cod" class="mr-2">
                        Cash on Delivery
                    </label>
                </div>
            </div>
            
            <div id="mpesa-instructions" class="mb-4 p-3 bg-green-50 border border-green-200 rounded-md">
                <p class="text-sm"><strong>M-Pesa Payment Instructions:</strong></p>
                <p class="text-sm">1. Go to M-Pesa menu</p>
                <p class="text-sm">2. Select "Buy Goods and Services"</p>
                <p class="text-sm">3. Enter Till Number: <strong>8739678</strong></p>
                <p class="text-sm">4. Enter Amount: <strong id="checkout-total"></strong></p>
                <p class="text-sm">5. Complete the transaction</p>
            </div>
            
            <div class="mb-4">
                <input type="text" name="mpesa_code" placeholder="M-Pesa Transaction Code (if paid)" class="w-full p-2 border rounded-md">
            </div>
            
            <input type="hidden" name="cart_data" id="cart-data">
            <input type="hidden" name="total_amount" id="total-amount">
            <input type="hidden" name="currency" value="<?php echo $current_currency; ?>">
            
            <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-md hover:bg-blue-700 font-bold">
                Place Order
            </button>
        </form>
    </div>
</div>

<main class="product-page-container">
    <div class="product-image-container relative">
        <?php if ($isAdminProduct): ?>
            <div class="product-badge">ADMIN PRODUCT</div>
        <?php endif; ?>
        <img src="<?php echo $productImage; ?>" alt="<?php echo $productName; ?>" class="product-image">
    </div>

    <div class="product-details">
        <h1 class="text-3xl md:text-4xl font-bold mb-2"><?php echo $productName; ?></h1>
        
        <div class="stock-indicator <?php echo $stockClass; ?>">
            <i class="fas fa-box mr-2"></i><?php echo $stockStatus; ?>
        </div>
        
        <p class="text-gray-600 mb-4"><?php echo $productDescription; ?></p>
        
        <div class="price-container">
            <span class="discount-price"><?php echo convertPrice($productPriceKSH + 2000, $current_currency, $exchange_rates); ?></span>
            <span class="current-price"><?php echo convertPrice($productPriceKSH, $current_currency, $exchange_rates); ?></span>
        </div>

        <?php if (!$outOfStock): ?>
        <form method="POST" id="add-to-cart-form">
            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-2">Available Sizes</h3>
                <div class="flex flex-wrap">
                    <?php foreach ($productSizes as $size): ?>
                        <span class="size-box" onclick="selectSize('<?php echo $size; ?>', this)"><?php echo $size; ?></span>
                    <?php endforeach; ?>
                </div>
                <input type="hidden" name="selected_size" id="selected_size" required>
                <div id="size-error" class="text-red-500 text-sm mt-1" style="display: none;">Please select a size</div>
            </div>

            <div class="flex items-center gap-4 mb-6">
                <label for="quantity" class="font-semibold">Quantity:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?php echo min($productStock, 10); ?>" class="w-20 p-2 border border-gray-300 rounded-md text-center">
                <span class="text-sm text-gray-500">Max: <?php echo min($productStock, 10); ?></span>
            </div>
            
            <input type="hidden" name="product_name" value="<?php echo $productName; ?>">
            <input type="hidden" name="product_price" value="<?php echo $productPriceKSH; ?>">
            <input type="hidden" name="product_image" value="<?php echo $productImage; ?>">
            
            <div class="flex flex-col gap-4 w-full">
                <button type="submit" name="add_to_cart" class="add-to-cart-btn" onclick="return validateForm()">Add to Cart</button>
                <button type="button" class="buy-now-btn" onclick="buyNow()">Buy it now</button>
            </div>
        </form>
        <?php else: ?>
            <div class="flex flex-col gap-4 w-full">
                <button disabled class="add-to-cart-btn">Out of Stock</button>
                <button disabled class="buy-now-btn">Currently Unavailable</button>
            </div>
            <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-md">
                <p class="text-red-700 font-semibold">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    This item is currently out of stock. Please check back later or contact us for availability.
                </p>
            </div>
        <?php endif; ?>
        
        <div class="mt-8 p-4 bg-gray-100 rounded-md">
            <p class="flex items-center text-sm font-semibold text-gray-700">
                <i class="fa-solid fa-lock mr-2 text-green-500"></i>
                Guaranteed secure & safe checkout
            </p>
        </div>

        <!-- Product Information Section -->
        <div class="mt-8 space-y-4">
            <div class="border-t pt-4">
                <h3 class="font-semibold mb-2">Product Information</h3>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>SKU:</strong> <?php echo strtoupper(substr(md5($productName), 0, 8)); ?></p>
                    <p><strong>Category:</strong> <?php echo $isAdminProduct ? 'Premium Collection' : 'Classic Collection'; ?></p>
                    <p><strong>Availability:</strong> <?php echo $stockStatus; ?></p>
                    <?php if ($current_currency === 'USD'): ?>
                        <p><strong>Price in KSH:</strong> KSh<?php echo number_format($productPriceKSH, 2); ?></p>
                    <?php else: ?>
                        <p><strong>Price in USD:</strong> $<?php echo number_format($productPriceKSH / 127, 2); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="bg-white py-12 border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-2 gap-12">
        <div>
            <h3 class="text-xl font-bold mb-6 text-gray-800">Customer Care</h3>
            <div class="space-y-4 text-gray-600">
                <p>
                    <strong class="block text-gray-800">Address:</strong> Ronald Ngala St, Nairobi Dubai Merchant mall C67
                </p>
                <p class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <a href="https://wa.me/254741421583" target="_blank" class="hover:underline font-semibold">Ask your questions on WhatsApp</a>
                </p>
            </div>
        </div>

        <div>
            <h3 class="text-xl font-bold mb-6 text-gray-800">Follow us</h3>
            <div class="flex space-x-4 mb-8">
                <a href="#" target="_blank" class="text-gray-600 hover:text-gray-900">
                    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.71.01 3.653.051.92.04 1.5.17 1.943.344.475.187.893.472 1.284.86.391.391.674.809.86 1.284.174.444.304 1.023.344 1.943.04.943.051 1.223.051 3.653s-.01 2.71-.051 3.653c-.04.92-.17 1.5-.344 1.943-.187.475-.472.893-.86 1.284-.391.391-.809.674-1.284.86-.444.174-1.023.304-1.943.344-.943.04-1.223.051-3.653.051s-2.71-.01-3.653-.051c-.92-.04-1.5-.17-1.943-.344-.475-.187-.893-.472-1.284-.86-.391-.391-.674-.809-.86-1.284-.174-.444-.304-1.023-.344-1.943-.04-.943-.051-1.223-.051-3.653s.01-2.71.051-3.653c.04-.92.17-1.5.344-1.943.187-.475.472-.893.86-1.284.391-.391.809-.674 1.284-.86.444-.174 1.023-.304 1.943-.344.943-.04 1.223-.051 3.653-.051zm-1.5 5.86c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7-7-3.13-7-7zM12 9c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zm5.5-8.5c.28 0 .5.22.5.5s-.22.5-.5.5-.5-.22-.5-.5.22-.5.5-.5z" clip-rule="evenodd" /></svg>
                </a>
                <a href="#" target="_blank" class="text-gray-600 hover:text-gray-900">
                    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.776-3.89 1.094 0 2.24.195 2.24.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.56V12h2.773l-.443 2.89h-2.33V22C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                </a>
                <a href="#" target="_blank" class="text-gray-600 hover:text-gray-900">
                    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.21-6.174L4.99 21.75H1.68l7.73-8.825L1.254 2.25H8.08l4.714 5.918L18.244 2.25zm-2.972 1.34h-.838L7.546 17.38h1.615L16.272 3.59z" /></svg>
                </a>
            </div>

            <h3 class="text-xl font-bold mb-4 text-gray-800">Subscribe to our emails</h3>
            <form class="flex flex-col space-y-4">
                <input type="email" placeholder="Email" class="p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-gray-300">
                <label class="flex items-center text-gray-600">
                    <input type="checkbox" class="form-checkbox h-4 w-4 text-gray-600 rounded">
                    <span class="ml-2 text-sm">Receive Offers & Product Drops</span>
                </label>
                <button type="submit" class="bg-gray-500 text-white py-3 px-6 rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50 font-semibold">
                    Subscribe
                </button>
            </form>
        </div>
    </div>
</footer>

<script>
let selectedSize = null;

function selectSize(size, element) {
    // Check if out of stock
    if (<?php echo json_encode($outOfStock); ?>) {
        return;
    }
    
    // Remove selected class from all size boxes
    document.querySelectorAll('.size-box').forEach(box => {
        box.classList.remove('selected');
    });
    
    // Add selected class to clicked element
    element.classList.add('selected');
    
    // Update hidden input
    document.getElementById('selected_size').value = size;
    selectedSize = size;
    
    // Hide error message
    document.getElementById('size-error').style.display = 'none';
}

function validateForm() {
    if (<?php echo json_encode($outOfStock); ?>) {
        alert('This product is currently out of stock.');
        return false;
    }
    
    if (!selectedSize) {
        document.getElementById('size-error').style.display = 'block';
        return false;
    }
    
    const quantity = parseInt(document.getElementById('quantity').value);
    const maxStock = <?php echo $productStock; ?>;
    
    if (quantity > maxStock) {
        alert(`Sorry, only ${maxStock} items available in stock.`);
        return false;
    }
    
    return true;
}

function buyNow() {
    if (!validateForm()) {
        return;
    }
    
    // Add to cart first, then open checkout
    const form = document.getElementById('add-to-cart-form');
    const formData = new FormData(form);
    formData.append('add_to_cart', '1');
    
    fetch(window.location.href, {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            // Refresh page to update cart, then open checkout
            window.location.reload();
        }
    }).catch(error => {
        console.error('Error:', error);
        alert('There was an error adding the product to cart. Please try again.');
    });
}

function toggleCart() {
    const sidebar = document.getElementById('cart-sidebar');
    const overlay = document.getElementById('cart-overlay');
    
    sidebar.classList.toggle('open');
    overlay.classList.toggle('open');
}

function removeFromCart(index) {
    if (confirm('Remove this item from cart?')) {
        fetch('remove_from_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'remove_index=' + index
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        }).catch(error => {
            console.error('Error:', error);
            alert('There was an error removing the item. Please try again.');
        });
    }
}

function openCheckout() {
    // Calculate total
    const cartItems = <?php echo json_encode($_SESSION['cart']); ?>;
    const exchangeRates = <?php echo json_encode($exchange_rates); ?>;
    const currentCurrency = '<?php echo $current_currency; ?>';
    const currencySymbol = '<?php echo $currency_symbol; ?>';
    
    let total = 0;
    cartItems.forEach(item => {
        total += item.price * item.quantity * exchangeRates[currentCurrency];
    });
    
    document.getElementById('checkout-total').textContent = currencySymbol + total.toFixed(2);
    document.getElementById('cart-data').value = JSON.stringify(cartItems);
    document.getElementById('total-amount').value = total.toFixed(2);
    
    document.getElementById('checkout-modal').classList.add('open');
    toggleCart(); // Close cart sidebar
}

function closeCheckout() {
    document.getElementById('checkout-modal').classList.remove('open');
}

// Show/hide M-Pesa instructions based on payment method
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
    const mpesaInstructions = document.getElementById('mpesa-instructions');
    
    paymentMethods.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'mpesa') {
                mpesaInstructions.style.display = 'block';
            } else {
                mpesaInstructions.style.display = 'none';
            }
        });
    });
    
    // Update quantity limit based on stock
    const quantityInput = document.getElementById('quantity');
    if (quantityInput) {
        quantityInput.addEventListener('change', function() {
            const maxStock = <?php echo $productStock; ?>;
            if (parseInt(this.value) > maxStock) {
                this.value = maxStock;
                alert(`Maximum quantity available: ${maxStock}`);
            }
        });
    }
    
    // Real-time price update based on currency
    const currencySelector = document.querySelector('.currency-selector');
    if (currencySelector) {
        currencySelector.addEventListener('change', function() {
            // Show loading indicator or disable form temporarily
            document.body.style.opacity = '0.7';
            document.body.style.pointerEvents = 'none';
        });
    }
});

// Close modals when clicking outside
window.addEventListener('click', function(e) {
    const checkoutModal = document.getElementById('checkout-modal');
    if (e.target === checkoutModal) {
        closeCheckout();
    }
});

// Auto-refresh stock information every 30 seconds
setInterval(function() {
    // In a real application, this would make an AJAX call to check stock
    console.log('Checking stock levels...');
}, 30000);
</script>

</body>
</html>tel:0741421583" class="hover:underline">0741421583</a>
                </p>
                <p class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-7 6l-4 4m-3 0l7-7-4-4"></path>
                    </svg>
                    <a href="mailto:titanich2024@gmail.com" class="hover:underline">titanich2024@gmail.com</a>
                </p>
                <p class="flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <a href="