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

// Define the currency conversion function and constants - UNIFIED VERSION
function convertCurrency($amount, $target_currency) {
    // Define exchange rates based on KSH as base currency
    $exchange_rates = [
        'KSH' => 1,      // Base currency
        'USD' => 1/127   // 1 KSH = 1/127 USD (127 KSH = 1 USD)
    ];
    
    // Source is always KSH (prices stored in KSH)
    $source_currency = 'KSH'; 

    if ($source_currency === $target_currency) {
        return $amount;
    }
    
    if (isset($exchange_rates[$target_currency])) {
        return $amount * $exchange_rates[$target_currency];
    }
    
    // Return original amount if conversion is not possible
    return $amount;
}

// Helper function for formatting currency - ADDED FOR CONSISTENCY
function formatCurrency($amount, $currency) {
    $converted = convertCurrency($amount, $currency);
    $symbol = $currency === 'KSH' ? 'KSh' : '$';
    return $symbol . number_format($converted, 2);
}

// Define your currency-related constants
$shipping_cost_ksh = 400;
$free_shipping_threshold_ksh = 5000;

// Handle cart operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['remove_item'])) {
        $index = intval($_POST['remove_item']);
        if (isset($_SESSION['cart'][$index])) {
            array_splice($_SESSION['cart'], $index, 1);
        }
    }
    
    if (isset($_POST['update_quantity'])) {
        $index = intval($_POST['item_index']);
        $new_quantity = max(1, intval($_POST['quantity']));
        if (isset($_SESSION['cart'][$index])) {
            $_SESSION['cart'][$index]['quantity'] = $new_quantity;
        }
    }
    
    if (isset($_POST['clear_cart'])) {
        $_SESSION['cart'] = [];
    }
    
    if (isset($_POST['change_currency'])) {
        $_SESSION['currency'] = $_POST['currency'];
    }
    
    // Redirect to prevent form resubmission
    header("Location: cart.php");
    exit();
}

// Get current currency and exchange rates
$current_currency = $_SESSION['currency'] ?? 'KSH';
$currency_symbol = $current_currency === 'KSH' ? 'KSh' : '$';
$cart_items = $_SESSION['cart'] ?? [];

// Calculate totals
$subtotal = 0;
$total_items = 0;
foreach ($cart_items as $item) {
    $item_total = $item['price'] * $item['quantity'];
    $subtotal += $item_total;
    $total_items += $item['quantity'];
}

// Convert to current currency
$subtotal_converted = convertCurrency($subtotal, $current_currency);
$shipping_cost = convertCurrency($shipping_cost_ksh, $current_currency);
$free_shipping_threshold = convertCurrency($free_shipping_threshold_ksh, $current_currency);

// Determine shipping
$is_free_shipping = $subtotal >= $free_shipping_threshold_ksh;
$final_shipping = $is_free_shipping ? 0 : $shipping_cost;
$total = $subtotal_converted + $final_shipping;

// Check if user is logged in
$is_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$user_name = $_SESSION['user_name'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart - Titanic Collection</title>
    <link rel="shortcut icon" href="https://img.icons8.com/fluent/48/000000/enter-2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .main-header {
            background: linear-gradient(135deg, #40E0D0 0%, #48D1CC 50%, #20B2AA 100%);
            padding: 15px 20px;
            box-shadow: 0 2px 10px rgba(64, 224, 208, 0.3);
        }
        .nav-menu {
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        .nav-links {
            display: flex;
            gap: 30px;
        }
        .nav-links a {
            text-decoration: none;
            color: #FFFFFF;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .nav-links a:hover {
            background: rgba(255,255,255,0.2);
            transform: translateY(-1px);
        }
        .nav-links a.active {
            background: rgba(255,255,255,0.3);
        }
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
            color: white;
        }
        .currency-selector {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 500;
        }
        .cart-item {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .cart-item:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        .quantity-input {
            width: 80px;
            text-align: center;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px;
            font-weight: 600;
        }
        .quantity-input:focus {
            border-color: #40E0D0;
            outline: none;
        }
        .btn-primary {
            background: linear-gradient(135deg, #40E0D0, #20B2AA);
            color: white;
            font-weight: 600;
            padding: 12px 24px;
            border-radius: 10px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #20B2AA, #40E0D0);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(64, 224, 208, 0.3);
        }
        .btn-danger {
            background: #ef4444;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }
        .btn-danger:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }
        .progress-bar {
            background: linear-gradient(90deg, #40E0D0, #20B2AA);
            height: 8px;
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        .empty-cart {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<header class="main-header">
    <div class="nav-menu">
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <a href="cart.php" class="active">Cart (<?php echo $total_items; ?>)</a>
            <a href="contact.php">Contact</a>
        </div>
        
        <div class="user-info">
            <form method="POST" style="display: inline;">
                <select name="currency" class="currency-selector" onchange="this.form.submit()">
                    <option value="KSH" <?php echo $current_currency === 'KSH' ? 'selected' : ''; ?>>KSH</option>
                    <option value="USD" <?php echo $current_currency === 'USD' ? 'selected' : ''; ?>>USD</option>
                </select>
                <input type="hidden" name="change_currency" value="1">
            </form>
            
            <?php if ($is_logged_in): ?>
                <div class="flex items-center gap-3">
                    <i class="fas fa-user-circle text-xl"></i>
                    <span class="font-semibold"><?php echo htmlspecialchars($user_name); ?></span>
                    <a href="logout.php" class="hover:underline">Logout</a>
                </div>
            <?php else: ?>
                <div class="flex items-center gap-3">
                    <a href="login.php" class="hover:underline font-semibold">Login</a>
                    <span>/</span>
                    <a href="signup.php" class="hover:underline font-semibold">Sign Up</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Shopping Cart</h1>
        <p class="text-gray-600">
            <?php if ($total_items > 0): ?>
                You have <?php echo $total_items; ?> item<?php echo $total_items > 1 ? 's' : ''; ?> in your cart
            <?php else: ?>
                Your cart is currently empty
            <?php endif; ?>
        </p>
    </div>

    <?php if (empty($cart_items)): ?>
        <div class="empty-cart text-center py-16">
            <div class="max-w-md mx-auto">
                <div class="w-32 h-32 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="fas fa-shopping-cart text-6xl text-gray-400"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Your cart is empty</h2>
                <p class="text-gray-600 mb-8">Start adding some awesome footwear to your collection!</p>
                
                <div class="space-y-4">
                    <a href="products.php" class="btn-primary inline-block">
                        <i class="fas fa-shopping-bag mr-2"></i>Browse Products
                    </a>
                    
                    <div class="flex justify-center space-x-8 text-sm text-gray-500 mt-8">
                        <div class="flex items-center">
                            <i class="fas fa-truck mr-2 text-green-500"></i>
                            <span>Free Delivery</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt mr-2 text-blue-500"></i>
                            <span>Secure Payment</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-undo mr-2 text-purple-500"></i>
                            <span>Easy Returns</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-4">
                <?php foreach ($cart_items as $index => $item): ?>
                    <?php 
                        $item_total = $item['price'] * $item['quantity'];
                        $item_total_converted = convertCurrency($item_total, $current_currency);
                    ?>
                    <div class="cart-item p-6">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img src="<?php echo htmlspecialchars($item['image']); ?>" 
                                    alt="<?php echo htmlspecialchars($item['name']); ?>"
                                    class="w-20 h-20 object-cover rounded-lg">
                            </div>
                            
                            <div class="flex-grow">
                                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                    <?php echo htmlspecialchars($item['name']); ?>
                                </h3>
                                <p class="text-gray-600 text-sm mb-2">Size: <?php echo htmlspecialchars($item['size']); ?></p>
                                <p class="text-lg font-bold text-green-600">
                                    <?php echo $currency_symbol . number_format($item_total_converted, 2); ?>
                                </p>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <form method="POST" class="flex items-center space-x-2">
                                    <input type="hidden" name="item_index" value="<?php echo $index; ?>">
                                    <button type="button" onclick="decreaseQuantity(this)" class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors">
                                        <i class="fas fa-minus text-sm"></i>
                                    </button>
                                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" 
                                            min="1" max="10" class="quantity-input" onchange="updateQuantity(this)">
                                    <button type="button" onclick="increaseQuantity(this)" class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors">
                                        <i class="fas fa-plus text-sm"></i>
                                    </button>
                                    <input type="hidden" name="update_quantity" value="1">
                                </form>
                            </div>
                            
                            <div class="flex-shrink-0">
                                <form method="POST" onsubmit="return confirm('Remove this item from cart?')">
                                    <input type="hidden" name="remove_item" value="<?php echo $index; ?>">
                                    <button type="submit" class="btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <div class="text-center pt-4">
                    <form method="POST" onsubmit="return confirm('Clear all items from cart?')" style="display: inline;">
                        <input type="hidden" name="clear_cart" value="1">
                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                            <i class="fas fa-trash-alt mr-1"></i>Clear Cart
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-8">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Order Summary</h2>
                    
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-600">Subtotal (<?php echo $total_items; ?> items)</span>
                        <span class="font-semibold"><?php echo $currency_symbol . number_format($subtotal_converted, 2); ?></span>
                    </div>
                    
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-600">Shipping</span>
                        <span class="font-semibold">
                            <?php if ($is_free_shipping): ?>
                                <span class="text-green-600">FREE</span>
                            <?php else: ?>
                                <?php echo $currency_symbol . number_format($final_shipping, 2); ?>
                            <?php endif; ?>
                        </span>
                    </div>
                    
                    <?php if (!$is_free_shipping): ?>
                        <?php 
                            $remaining = $free_shipping_threshold_ksh - $subtotal;
                            $progress = ($subtotal / $free_shipping_threshold_ksh) * 100;
                            $progress = min(100, max(0, $progress));
                        ?>
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-blue-700 font-medium">Free shipping progress</span>
                                <span class="text-xs text-blue-600"><?php echo number_format($progress, 1); ?>%</span>
                            </div>
                            <div class="w-full bg-blue-200 rounded-full h-2 mb-2">
                                <div class="progress-bar h-2 rounded-full" style="width: <?php echo $progress; ?>%"></div>
                            </div>
                            <p class="text-xs text-blue-600">
                                Add <?php echo formatCurrency($remaining, $current_currency); ?> more for free shipping!
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <hr class="my-4">
                    
                    <div class="flex justify-between items-center mb-6">
                        <span class="text-lg font-bold text-gray-900">Total</span>
                        <span class="text-xl font-bold text-green-600"><?php echo $currency_symbol . number_format($total, 2); ?></span>
                    </div>
                    
                    <?php if ($is_logged_in): ?>
                        <button onclick="proceedToCheckout()" class="btn-primary w-full mb-4">
                            <i class="fas fa-credit-card mr-2"></i>Proceed to Checkout
                        </button>
                    <?php else: ?>
                        <div class="space-y-3 mb-4">
                            <a href="login.php?redirect=cart.php" class="btn-primary w-full text-center block">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login to Checkout
                            </a>
                            <p class="text-xs text-gray-500 text-center">
                                Or <a href="signup.php" class="text-cyan-600 hover:underline">create an account</a> for faster checkout
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <a href="products.php" class="block text-center text-cyan-600 hover:text-cyan-800 font-medium mb-6">
                        <i class="fas fa-arrow-left mr-2"></i>Continue Shopping
                    </a>
                    
                    <div class="border-t pt-4">
                        <div class="grid grid-cols-1 gap-3 text-xs text-gray-600">
                            <div class="flex items-center">
                                <i class="fas fa-shield-alt text-green-500 mr-2"></i>
                                <span>Secure SSL Encryption</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-truck text-blue-500 mr-2"></i>
                                <span>Fast & Reliable Delivery</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-undo text-purple-500 mr-2"></i>
                                <span>30-Day Return Policy</span>
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-headset text-orange-500 mr-2"></i>
                                <span>24/7 Customer Support</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4 mt-4">
                        <h3 class="font-semibold text-gray-900 mb-3">Need Help?</h3>
                        <div class="space-y-2 text-sm">
                            <a href="tel:0741421583" class="flex items-center text-gray-600 hover:text-gray-900">
                                <i class="fas fa-phone mr-2 text-green-500"></i>
                                Call: 0741421583
                            </a>
                            <a href="https://wa.me/254741421583" target="_blank" class="flex items-center text-gray-600 hover:text-gray-900">
                                <i class="fab fa-whatsapp mr-2 text-green-500"></i>
                                WhatsApp Support
                            </a>
                            <a href="mailto:titanich2024@gmail.com" class="flex items-center text-gray-600 hover:text-gray-900">
                                <i class="fas fa-envelope mr-2 text-blue-500"></i>
                                Email Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<div id="checkout-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 p-6">
        <div class="text-center">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shopping-cart text-green-600 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-4">Proceed to Checkout</h3>
            <p class="text-gray-600 mb-6">Ready to complete your order?</p>
            
            <div class="space-y-4">
                <form action="process_order.php" method="POST" id="quick-checkout-form">
                    <input type="hidden" name="cart_data" value='<?php echo json_encode($cart_items); ?>'>
                    <input type="hidden" name="total_amount" value="<?php echo $total; ?>">
                    <input type="hidden" name="currency" value="<?php echo $current_currency; ?>">
                    <input type="hidden" name="customer_name" value="<?php echo htmlspecialchars($user_name); ?>">
                    <input type="hidden" name="customer_email" value="<?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?>">
                    <input type="hidden" name="customer_phone" value="<?php echo htmlspecialchars($_SESSION['user_phone'] ?? ''); ?>">
                    <input type="hidden" name="delivery_address" value="<?php echo htmlspecialchars($_SESSION['user_address'] ?? ''); ?>">
                    <input type="hidden" name="payment_method" value="mpesa">
                    
                    <button type="submit" class="btn-primary w-full">
                        <i class="fas fa-credit-card mr-2"></i>Quick Checkout
                    </button>
                </form>
                
                <button onclick="openDetailedCheckout()" class="w-full py-3 px-4 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Detailed Checkout
                </button>
                
                <button onclick="closeCheckoutModal()" class="text-gray-500 hover:text-gray-700 text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<footer class="bg-white border-t border-gray-200 mt-16">
    <div class="max-w-6xl mx-auto px-6 py-12">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div>
                <h3 class="text-lg font-bold mb-4 text-gray-900">Customer Care</h3>
                <div class="space-y-3 text-gray-600">
                    <p class="flex items-center">
                        <i class="fas fa-map-marker-alt mr-3 text-cyan-600"></i>
                        Ronald Ngala St, Nairobi Dubai Merchant mall C67
                    </p>
                    <p class="flex items-center">
                        <i class="fas fa-phone mr-3 text-green-600"></i>
                        <a href="tel:0741421583" class="hover:text-gray-900">0741421583</a>
                    </p>
                    <p class="flex items-center">
                        <i class="fas fa-envelope mr-3 text-blue-600"></i>
                        <a href="mailto:titanich2024@gmail.com" class="hover:text-gray-900">titanich2024@gmail.com</a>
                    </p>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-bold mb-4 text-gray-900">Quick Links</h3>
                <div class="space-y-3">
                    <a href="index.php" class="block text-gray-600 hover:text-gray-900 transition-colors">Home</a>
                    <a href="products.php" class="block text-gray-600 hover:text-gray-900 transition-colors">Products</a>
                    <a href="contact.php" class="block text-gray-600 hover:text-gray-900 transition-colors">Contact Us</a>
                    <a href="admin_dashboard.php" class="block text-gray-600 hover:text-gray-900 transition-colors">Admin</a>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-bold mb-4 text-gray-900">Stay Updated</h3>
                <p class="text-gray-600 mb-4">Get the latest updates on new products and exclusive offers.</p>
                <form class="flex">
                    <input type="email" placeholder="Your email" class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <button type="submit" class="bg-cyan-600 text-white px-6 py-2 rounded-r-lg hover:bg-cyan-700 transition-colors">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
        
        <div class="border-t border-gray-200 pt-8 mt-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-600 text-sm">
                    © 2025 Titanic Collection. All rights reserved.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-gray-600"><i class="fab fa-facebook text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-gray-600"><i class="fab fa-instagram text-xl"></i></a>
                    <a href="#" class="text-gray-400 hover:text-gray-600"><i class="fab fa-twitter text-xl"></i></a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
// Quantity controls
function decreaseQuantity(button) {
    const input = button.nextElementSibling;
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
        updateQuantity(input);
    }
}

function increaseQuantity(button) {
    const input = button.previousElementSibling;
    if (parseInt(input.value) < 10) {
        input.value = parseInt(input.value) + 1;
        updateQuantity(input);
    }
}

function updateQuantity(input) {
    const form = input.closest('form');
    if (form) {
        form.submit();
    }
}

// Checkout functions
function proceedToCheckout() {
    <?php if ($is_logged_in): ?>
        document.getElementById('checkout-modal').classList.remove('hidden');
    <?php else: ?>
        window.location.href = 'login.php?redirect=cart.php';
    <?php endif; ?>
}

function closeCheckoutModal() {
    document.getElementById('checkout-modal').classList.add('hidden');
}

function openDetailedCheckout() {
    // Redirect to the detailed checkout from the original product page
    window.location.href = 'product.php#checkout';
}

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    const modal = document.getElementById('checkout-modal');
    if (e.target === modal) {
        closeCheckoutModal();
    }
});

// Auto-save cart changes (optional enhancement)
function autoSaveCart() {
    // This could be implemented to save cart state via AJAX
    console.log('Cart auto-saved');
}

// Add some visual feedback for cart updates
document.addEventListener('DOMContentLoaded', function() {
    // Add loading states to quantity update buttons
    const quantityForms = document.querySelectorAll('form input[name="update_quantity"]');
    quantityForms.forEach(form => {
        form.closest('form').addEventListener('submit', function() {
            const button = this.querySelector('input[type="number"]');
            button.style.opacity = '0.5';
        });
    });
    
    // Add confirmation for cart clearing
    const clearCartForm = document.querySelector('form input[name="clear_cart"]');
    if (clearCartForm) {
        clearCartForm.closest('form').addEventListener('submit', function(e) {
            if (!confirm('Are you sure you want to clear all items from your cart?')) {
                e.preventDefault();
            }
        });
    }
});

// Enhanced user experience
function showToast(message, type = 'success') {
    // Simple toast notification system
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg text-white z-50 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}