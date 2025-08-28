<?php
session_start();
include 'config.php';

// Handle currency change
if (isset($_POST['change_currency']) && isset($_POST['currency'])) {
    $_SESSION['currency'] = $_POST['currency'];
    header("Location: product.php?id=" . $_POST['product_id']);
    exit();
}

// Handle add to cart from quick add or product details
if (isset($_POST['add_to_cart'])) {
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    
    $cart_item = [
        'name' => $_POST['product_name'],
        'price' => floatval($_POST['product_price']),
        'image' => $_POST['product_image'],
        'size' => $_POST['selected_size'] ?? 'N/A',
        'quantity' => intval($_POST['quantity'])
    ];
    
    // Check if item already exists in cart with same size
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['name'] === $cart_item['name'] && $item['size'] === $cart_item['size']) {
            $item['quantity'] += $cart_item['quantity'];
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $_SESSION['cart'][] = $cart_item;
    }
    
    // Return to the product page after adding to cart
    header("Location: product.php?id=" . $_POST['product_id']);
    exit();
}

// Currency helper functions
function convertCurrency($price_ksh, $currency) {
    if ($currency === 'USD') {
        return $price_ksh / 130; // Approximate KSH to USD conversion
    }
    return $price_ksh;
}

function formatCurrency($price_ksh, $currency) {
    $converted_price = convertCurrency($price_ksh, $currency);
    if ($currency === 'USD') {
        return '$' . number_format($converted_price, 2);
    }
    return 'Ksh ' . number_format($converted_price, 2);
}

// Get product details from database or fallback data
$product = null;
if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);
    $products = [];
    $products_query = "SELECT * FROM products WHERE id = " . $product_id . " AND is_active = 1 LIMIT 1";

    // Try to execute query if database connection exists
    try {
        if (isset($conn) && $conn) {
            $products_result = mysqli_query($conn, $products_query);
            if ($products_result && mysqli_num_rows($products_result) > 0) {
                $product = mysqli_fetch_assoc($products_result);
                $product['available_sizes'] = json_decode($product['available_sizes'], true);
            }
        }
    } catch (Exception $e) {
        // Database connection failed, use sample data
    }
}

// Enhanced sample products data if no database match found
if ($product === null) {
    $sample_products = [
        1 => [
            'id' => 1,
            'name' => 'Premium Leather Oxford',
            'description' => 'Classic Oxford shoes crafted from premium Italian leather with handstitched details. A timeless design that combines sophistication with comfort for the modern gentleman.',
            'price_ksh' => 7800.00,
            'image_url' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=600&h=600&fit=crop',
            'available_sizes' => ['39', '40', '41', '42', '43', '44'],
            'category' => 'formal',
            'featured' => true,
            'stock' => 25,
            'rating' => 4.8
        ],
        2 => [
            'id' => 2,
            'name' => 'Sport Running Sneakers',
            'description' => 'Professional running shoes with advanced air cushioning and breathable mesh. Designed for peak performance, offering maximum support and a lightweight feel for athletes.',
            'price_ksh' => 6500.00,
            'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=600&h=600&fit=crop',
            'available_sizes' => ['38', '39', '40', '41', '42', '43', '44', '45'],
            'category' => 'sports',
            'featured' => true,
            'stock' => 18,
            'rating' => 4.6
        ],
        3 => [
            'id' => 3,
            'name' => 'Casual Canvas Shoes',
            'description' => 'Versatile canvas shoes perfect for everyday wear with a durable rubber sole. The ultimate choice for a relaxed yet stylish look, suitable for any casual outing.',
            'price_ksh' => 4200.00,
            'image_url' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=600&h=600&fit=crop',
            'available_sizes' => ['36', '37', '38', '39', '40', '41', '42'],
            'category' => 'casual',
            'featured' => false,
            'stock' => 32,
            'rating' => 4.3
        ],
        4 => [
            'id' => 4,
            'name' => 'High-Top Basketball Shoes',
            'description' => 'Professional basketball shoes with superior ankle support and advanced grip technology. Built to handle intense court action while providing comfort and stability.',
            'price_ksh' => 8900.00,
            'image_url' => 'https://images.unsplash.com/photo-1552346154-21d32810aba3?w=600&h=600&fit=crop',
            'available_sizes' => ['40', '41', '42', '43', '44', '45', '46'],
            'category' => 'sports',
            'featured' => true,
            'stock' => 14,
            'rating' => 4.9
        ],
        5 => [
            'id' => 5,
            'name' => 'Elegant Loafers',
            'description' => 'Sophisticated loafers for business and formal occasions with a luxurious memory foam insole. A perfect blend of style and comfort, making them ideal for long hours.',
            'price_ksh' => 7200.00,
            'image_url' => 'https://images.unsplash.com/photo-1582897085656-c636d006a246?w=600&h=600&fit=crop',
            'available_sizes' => ['39', '40', '41', '42', '43', '44'],
            'category' => 'formal',
            'featured' => false,
            'stock' => 22,
            'rating' => 4.7
        ],
        6 => [
            'id' => 6,
            'name' => 'Adventure Hiking Boots',
            'description' => 'Waterproof hiking boots for outdoor adventures with an anti-slip sole for superior traction. Engineered to keep your feet dry and protected on any trail.',
            'price_ksh' => 9500.00,
            'image_url' => 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=600&h=600&fit=crop',
            'available_sizes' => ['40', '41', '42', '43', '44', '45'],
            'category' => 'boots',
            'featured' => true,
            'stock' => 16,
            'rating' => 4.8
        ],
        7 => [
            'id' => 7,
            'name' => 'Classic White Sneakers',
            'description' => 'Timeless white sneakers with a smooth leather upper and comfortable padding. A versatile staple for any wardrobe, offering a clean and modern aesthetic.',
            'price_ksh' => 5800.00,
            'image_url' => 'https://images.unsplash.com/photo-1600269452121-4f2416e55c28?w=600&h=600&fit=crop',
            'available_sizes' => ['37', '38', '39', '40', '41', '42', '43'],
            'category' => 'casual',
            'featured' => true,
            'stock' => 28,
            'rating' => 4.5
        ],
        8 => [
            'id' => 8,
            'name' => 'Professional Work Boots',
            'description' => 'Steel-toe work boots with a slip-resistant sole and reinforced heel for maximum safety and durability. Ideal for tough work environments and long days on your feet.',
            'price_ksh' => 8200.00,
            'image_url' => 'https://images.unsplash.com/photo-1605348532760-6753d2c43329?w=600&h=600&fit=crop',
            'available_sizes' => ['40', '41', '42', '43', '44', '45', '46'],
            'category' => 'boots',
            'featured' => false,
            'stock' => 19,
            'rating' => 4.4
        ]
    ];
    if (isset($product_id) && isset($sample_products[$product_id])) {
        $product = $sample_products[$product_id];
    }
}

// Redirect if product not found
if ($product === null) {
    header("Location: products.php");
    exit();
}

// Get current currency
$current_currency = $_SESSION['currency'] ?? 'KSH';
$currency_symbol = $current_currency === 'KSH' ? 'Ksh' : '$';

// Get cart count
$cart_count = count($_SESSION['cart'] ?? []);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Titanich Store</title>
    <link rel="shortcut icon" href="https://img.icons8.com/fluent/48/000000/enter-2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f7fafc;
        }
        .main-header {
            background: linear-gradient(135deg, #40E0D0, #36C5D6);
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }
        .nav-menu a:hover, .nav-menu a.active {
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(10px);
        }
        .cart-icon {
            position: relative;
            color: #FFFFFF;
            font-size: 1.5rem;
            cursor: pointer;
            transition: transform 0.2s ease;
        }
        .cart-icon:hover {
            transform: scale(1.1);
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
            animation: bounce 0.3s ease;
        }
        .currency-selector {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            padding: 6px 12px;
            border-radius: 6px;
            margin-left: 20px;
            backdrop-filter: blur(10px);
        }
        .product-image {
            width: 100%;
            height: auto;
            max-height: 600px;
            object-fit: cover;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .price-tag {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 25px;
            font-weight: bold;
            display: inline-block;
            margin: 0.5rem 0;
            box-shadow: 0 2px 10px rgba(40, 167, 69, 0.3);
        }
        .old-price {
            text-decoration: line-through;
            color: #666;
            font-size: 0.9rem;
            margin-right: 0.5rem;
        }
        .stock-indicator {
            display: flex;
            align-items: center;
            font-size: 1rem;
            margin-top: 0.5rem;
            font-weight: 500;
        }
        .stock-low { color: #dc3545; }
        .stock-medium { color: #ffc107; }
        .stock-high { color: #28a745; }
        .rating {
            display: flex;
            align-items: center;
            font-size: 1rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        .stars {
            color: #ffc107;
            margin-right: 0.5rem;
        }
        .size-btn {
            background: #e5e7eb;
            color: #4b5563;
            border: 1px solid #d1d5db;
            transition: all 0.2s ease;
        }
        .size-btn.active {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }
    </style>
</head>
<body>

<!-- Enhanced Header with Cart -->
<header class="main-header">
    <div class="nav-menu">
        <div class="nav-links">
            <a href="index.php">
                <i class="fas fa-home mr-2"></i>Home
            </a>
            <a href="products.php" class="active">
                <i class="fas fa-shopping-bag mr-2"></i>Products
            </a>
            <a href="about.php">
                <i class="fas fa-info-circle mr-2"></i>About
            </a>
            <a href="contact.php">
                <i class="fas fa-phone mr-2"></i>Contact
            </a>
        </div>
        <div class="flex items-center">
            <!-- Currency Selector -->
            <form method="POST" style="display: inline;">
                <select name="currency" class="currency-selector" onchange="this.form.submit()">
                    <option value="KSH" <?php echo $current_currency === 'KSH' ? 'selected' : ''; ?>>KSH (Ksh)</option>
                    <option value="USD" <?php echo $current_currency === 'USD' ? 'selected' : ''; ?>>USD ($)</option>
                </select>
                <input type="hidden" name="change_currency" value="1">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
            </form>
            
            <!-- Cart Icon -->
            <div class="cart-icon" onclick="window.location.href='cart.php'">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count" id="cart-count"><?php echo $cart_count; ?></span>
            </div>
        </div>
    </div>
</header>

<main class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl p-8 shadow-md grid grid-cols-1 md:grid-cols-2 gap-12 items-start">
        <!-- Product Image -->
        <div>
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image">
        </div>

        <!-- Product Details -->
        <div>
            <div class="mb-6">
                <h1 class="text-4xl font-bold text-gray-900 mb-2"><?php echo htmlspecialchars($product['name']); ?></h1>
                
                <!-- Rating -->
                <?php
                    $rating = $product['rating'] ?? 4.0;
                    $fullStars = floor($rating);
                    $halfStar = ($rating - $fullStars) >= 0.5;
                ?>
                <div class="rating text-xl">
                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <?php if ($i <= $fullStars): ?>
                                <i class="fas fa-star"></i>
                            <?php elseif ($i === $fullStars + 1 && $halfStar): ?>
                                <i class="fas fa-star-half-alt"></i>
                            <?php else: ?>
                                <i class="far fa-star"></i>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                    <span class="ml-2 text-gray-700 font-medium">(<?php echo $rating; ?>)</span>
                </div>
            </div>

            <p class="text-gray-600 text-lg mb-6 leading-relaxed"><?php echo htmlspecialchars($product['description']); ?></p>

            <div class="flex items-baseline mb-6">
                <span class="old-price text-xl"><?php echo formatCurrency($product['price_ksh'] * 1.3, $current_currency); ?></span>
                <span class="price-tag text-2xl"><?php echo formatCurrency($product['price_ksh'], $current_currency); ?></span>
            </div>

            <!-- Stock Indicator -->
            <?php
                $stock = $product['stock'] ?? 20;
                $stockClass = $stock < 10 ? 'stock-low' : ($stock < 20 ? 'stock-medium' : 'stock-high');
                $stockIcon = $stock < 10 ? 'fa-exclamation-triangle' : ($stock < 20 ? 'fa-clock' : 'fa-check-circle');
            ?>
            <div class="stock-indicator <?php echo $stockClass; ?> mb-8">
                <i class="fas <?php echo $stockIcon; ?> mr-2"></i>
                <?php if ($stock < 10): ?>
                    Only <?php echo $stock; ?> left in stock!
                <?php elseif ($stock < 20): ?>
                    <?php echo $stock; ?> available
                <?php else: ?>
                    In Stock (<?php echo $stock; ?>)
                <?php endif; ?>
            </div>

            <!-- Add to Cart Form -->
            <form method="POST">
                <input type="hidden" name="add_to_cart" value="1">
                <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($product['id']); ?>">
                <input type="hidden" name="product_name" value="<?php echo htmlspecialchars($product['name']); ?>">
                <input type="hidden" name="product_price" value="<?php echo htmlspecialchars($product['price_ksh']); ?>">
                <input type="hidden" name="product_image" value="<?php echo htmlspecialchars($product['image_url']); ?>">

                <div class="mb-6">
                    <label for="size" class="block text-gray-700 font-semibold mb-2">Select Size:</label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($product['available_sizes'] as $size): ?>
                            <input type="radio" name="selected_size" id="size-<?php echo htmlspecialchars($size); ?>" value="<?php echo htmlspecialchars($size); ?>" class="hidden peer">
                            <label for="size-<?php echo htmlspecialchars($size); ?>" class="size-btn px-6 py-3 rounded-full font-semibold cursor-pointer text-base peer-checked:active">
                                <?php echo htmlspecialchars($size); ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-6">
                    <label for="quantity" class="block text-gray-700 font-semibold mb-2">Quantity:</label>
                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="<?php echo $stock; ?>" 
                           class="w-24 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-4 px-6 rounded-lg text-lg font-semibold hover:from-green-700 hover:to-green-800 transition-all transform hover:scale-105">
                    <i class="fas fa-cart-plus mr-3"></i>Add to Cart
                </button>
            </form>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="bg-white py-16 border-t border-gray-200 mt-20">
    <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-12">
        <div>
            <h3 class="text-2xl font-bold mb-6 text-gray-800">Titanich Store</h3>
            <p class="text-gray-600 mb-6 leading-relaxed">Premium footwear collection for every occasion. Quality, comfort, and style in every step.</p>
            <div class="flex space-x-4">
                <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors">
                    <i class="fab fa-facebook-f text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-pink-600 transition-colors">
                    <i class="fab fa-instagram text-xl"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-blue-400 transition-colors">
                    <i class="fab fa-twitter text-xl"></i>
                </a>
            </div>
        </div>
        
        <div>
            <h3 class="text-xl font-bold mb-6 text-gray-800">Customer Care</h3>
            <div class="space-y-4 text-gray-600">
                <p class="flex items-start">
                    <i class="fas fa-map-marker-alt mr-3 mt-1 text-blue-600"></i>
                    <span><strong class="block text-gray-800">Address:</strong> Ronald Ngala St, Nairobi<br>Dubai Merchant Mall C67</span>
                </p>
                <p class="flex items-center">
                    <i class="fas fa-phone mr-3 text-green-600"></i>
                    <a href="tel:0741421583" class="hover:underline hover:text-green-600 transition-colors">0741421583</a>
                </p>
                <p class="flex items-center">
                    <i class="fas fa-envelope mr-3 text-red-600"></i>
                    <a href="mailto:titanich2024@gmail.com" class="hover:underline hover:text-red-600 transition-colors">titanich2024@gmail.com</a>
                </p>
                <p class="flex items-center">
                    <i class="fab fa-whatsapp mr-3 text-green-500"></i>
                    <a href="https://wa.me/254741421583" target="_blank" class="hover:underline font-semibold text-green-600 hover:text-green-700 transition-colors">WhatsApp Support</a>
                </p>
            </div>
        </div>
        
        <div>
            <h3 class="text-xl font-bold mb-6 text-gray-800">Quick Links</h3>
            <div class="space-y-3">
                <a href="index.php" class="block text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="fas fa-home mr-2"></i>Homepage
                </a>
                <a href="products.php" class="block text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="fas fa-shopping-bag mr-2"></i>All Products
                </a>
                <a href="cart.php" class="block text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="fas fa-shopping-cart mr-2"></i>Shopping Cart
                </a>
                <a href="about.php" class="block text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="fas fa-info-circle mr-2"></i>About Us
                </a>
                <a href="contact.php" class="block text-gray-600 hover:text-blue-600 transition-colors">
                    <i class="fas fa-phone mr-2"></i>Contact
                </a>
            </div>
        </div>
    </div>
    
    <div class="max-w-6xl mx-auto px-6 pt-8 mt-8 border-t border-gray-200">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <p class="text-gray-500 text-sm mb-4 md:mb-0">
                © <?php echo date('Y'); ?> Titanich Store. All rights reserved.
            </p>
            <div class="flex items-center space-x-4 text-sm text-gray-500">
                <span>Secure payments</span>
                <i class="fas fa-lock text-green-600"></i>
                <span>Free shipping on orders over Ksh 5,000</span>
            </div>
        </div>
    </div>
</footer>

</body>
</html>
