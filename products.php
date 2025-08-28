<?php
session_start();
include 'config.php';

// Handle currency change
if (isset($_POST['change_currency']) && isset($_POST['currency'])) {
    $_SESSION['currency'] = $_POST['currency'];
    header("Location: products.php");
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
        'size' => $_POST['selected_size'] ?? 'N/A', // Handle case where size might not be selected
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
    
    // Return JSON response for AJAX
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'cart_count' => count($_SESSION['cart'])]);
        exit();
    } else {
        header("Location: products.php");
        exit();
    }
}

// Handle product sync from homepage
if (isset($_POST['sync_homepage_products'])) {
    $homepage_products = $_POST['homepage_products'];
    // Store in session or database for homepage sync
    $_SESSION['homepage_featured'] = $homepage_products;
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
    exit();
}

// Currency helper functions
function convertCurrency($price_ksh, $currency) {
    if ($currency === 'USD') {
        return $price_ksh / 127; // Approximate KSH to USD conversion
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

// Get products from database (with enhanced fallback data)
$products = [];
$products_query = "SELECT * FROM products WHERE is_active = 1 ORDER BY created_at DESC";

// Try to execute query if database connection exists
try {
    if (isset($conn) && $conn) {
        $products_result = mysqli_query($conn, $products_query);
        if ($products_result && mysqli_num_rows($products_result) > 0) {
            while ($row = mysqli_fetch_assoc($products_result)) {
                $row['available_sizes'] = json_decode($row['available_sizes'], true);
                $products[] = $row;
            }
        }
    }
} catch (Exception $e) {
    // Database connection failed, use sample data
}

// Enhanced sample products data if no database
if (empty($products)) {
    $products = [
        [
            'id' => 1,
            'name' => 'Premium Leather Oxford',
            'description' => 'Classic Oxford shoes crafted from premium Italian leather with handstitched details',
            'price_ksh' => 7800.00,
            'image_url' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=400&fit=crop',
            'available_sizes' => ['39', '40', '41', '42', '43', '44'],
            'category' => 'formal',
            'featured' => true,
            'stock' => 25,
            'rating' => 4.8
        ],
        [
            'id' => 2,
            'name' => 'Sport Running Sneakers',
            'description' => 'Professional running shoes with advanced air cushioning and breathable mesh',
            'price_ksh' => 6500.00,
            'image_url' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop',
            'available_sizes' => ['38', '39', '40', '41', '42', '43', '44', '45'],
            'category' => 'sports',
            'featured' => true,
            'stock' => 18,
            'rating' => 4.6
        ],
        [
            'id' => 3,
            'name' => 'Casual Canvas Shoes',
            'description' => 'Versatile canvas shoes perfect for everyday wear with rubber sole',
            'price_ksh' => 4200.00,
            'image_url' => 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?w=400&h=400&fit=crop',
            'available_sizes' => ['36', '37', '38', '39', '40', '41', '42'],
            'category' => 'casual',
            'featured' => false,
            'stock' => 32,
            'rating' => 4.3
        ],
        [
            'id' => 4,
            'name' => 'High-Top Basketball Shoes',
            'description' => 'Professional basketball shoes with ankle support and grip technology',
            'price_ksh' => 8900.00,
            'image_url' => 'https://images.unsplash.com/photo-1552346154-21d32810aba3?w=400&h=400&fit=crop',
            'available_sizes' => ['40', '41', '42', '43', '44', '45', '46'],
            'category' => 'sports',
            'featured' => true,
            'stock' => 14,
            'rating' => 4.9
        ],
        [
            'id' => 5,
            'name' => 'Elegant Loafers',
            'description' => 'Sophisticated loafers for business and formal occasions with memory foam',
            'price_ksh' => 7200.00,
            'image_url' => 'https://images.unsplash.com/photo-1582897085656-c636d006a246?w=400&h=400&fit=crop',
            'available_sizes' => ['39', '40', '41', '42', '43', '44'],
            'category' => 'formal',
            'featured' => false,
            'stock' => 22,
            'rating' => 4.7
        ],
        [
            'id' => 6,
            'name' => 'Adventure Hiking Boots',
            'description' => 'Waterproof hiking boots for outdoor adventures with anti-slip sole',
            'price_ksh' => 9500.00,
            'image_url' => 'https://images.unsplash.com/photo-1544966503-7cc5ac882d5f?w=400&h=400&fit=crop',
            'available_sizes' => ['40', '41', '42', '43', '44', '45'],
            'category' => 'boots',
            'featured' => true,
            'stock' => 16,
            'rating' => 4.8
        ],
        [
            'id' => 7,
            'name' => 'Classic White Sneakers',
            'description' => 'Timeless white sneakers with leather upper and comfortable padding',
            'price_ksh' => 5800.00,
            'image_url' => 'https://images.unsplash.com/photo-1600269452121-4f2416e55c28?w=400&h=400&fit=crop',
            'available_sizes' => ['37', '38', '39', '40', '41', '42', '43'],
            'category' => 'casual',
            'featured' => true,
            'stock' => 28,
            'rating' => 4.5
        ],
        [
            'id' => 8,
            'name' => 'Professional Work Boots',
            'description' => 'Steel toe work boots with slip-resistant sole and reinforced heel',
            'price_ksh' => 8200.00,
            'image_url' => 'https://images.unsplash.com/photo-1605348532760-6753d2c43329?w=400&h=400&fit=crop',
            'available_sizes' => ['40', '41', '42', '43', '44', '45', '46'],
            'category' => 'boots',
            'featured' => false,
            'stock' => 19,
            'rating' => 4.4
        ]
    ];
}

// Get current currency
$current_currency = $_SESSION['currency'] ?? 'KSH';
$currency_symbol = $current_currency === 'KSH' ? 'Ksh' : '$';

// Get featured products for homepage sync
$featured_products = array_filter($products, function($product) {
    return isset($product['featured']) && $product['featured'];
});

// Store featured products in session for homepage access
$_SESSION['featured_products'] = array_slice($featured_products, 0, 6);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Titanich Store</title>
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
        .product-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
            overflow: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0,0,0,0.05);
        }
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
        }
        .product-image {
            width: 100%;
            height: 280px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .product-card:hover .product-image {
            transform: scale(1.05);
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
        .category-badge {
            position: absolute;
            top: 12px;
            right: 12px;
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 0.4rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            text-transform: uppercase;
            font-weight: 600;
            backdrop-filter: blur(10px);
        }
        .featured-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: linear-gradient(135deg, #ffd700, #ffed4e);
            color: #333;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
        }
        .stock-indicator {
            display: flex;
            align-items: center;
            font-size: 0.8rem;
            margin-top: 0.5rem;
        }
        .stock-low { color: #dc3545; }
        .stock-medium { color: #ffc107; }
        .stock-high { color: #28a745; }
        .rating {
            display: flex;
            align-items: center;
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        .stars {
            color: #ffc107;
            margin-right: 0.5rem;
        }
        .filter-btn {
            transition: all 0.3s ease;
        }
        .filter-btn.active {
            background: linear-gradient(135deg, #40E0D0, #36C5D6) !important;
            color: white !important;
            transform: scale(1.05);
        }
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-5px); }
            60% { transform: translateY(-3px); }
        }
        .sync-indicator {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #28a745;
            color: white;
            padding: 10px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            transform: translateX(300px);
            transition: transform 0.3s ease;
            z-index: 1000;
        }
        .sync-indicator.show {
            transform: translateX(0);
        }
    </style>
</head>
<body>

<!-- Sync Notification -->
<div id="sync-notification" class="sync-indicator">
    <i class="fas fa-sync-alt mr-2"></i>
    <span id="sync-message">Products synced with homepage!</span>
</div>

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
            <!-- Sync Button -->
            <button onclick="syncWithHomepage()" class="bg-white bg-opacity-20 text-white px-4 py-2 rounded-lg mr-4 hover:bg-opacity-30 transition-colors">
                <i class="fas fa-sync-alt mr-2"></i>Sync Homepage
            </button>
            
            <!-- Currency Selector -->
            <form method="POST" style="display: inline;">
                <select name="currency" class="currency-selector" onchange="this.form.submit()">
                    <option value="KSH" <?php echo $current_currency === 'KSH' ? 'selected' : ''; ?>>KSH (Ksh)</option>
                    <option value="USD" <?php echo $current_currency === 'USD' ? 'selected' : ''; ?>>USD ($)</option>
                </select>
                <input type="hidden" name="change_currency" value="1">
            </form>
            
            <!-- Cart Icon -->
            <div class="cart-icon" onclick="window.location.href='cart.php'">
                <i class="fas fa-shopping-cart"></i>
                <span class="cart-count" id="cart-count"><?php echo count($_SESSION['cart'] ?? []); ?></span>
            </div>
        </div>
    </div>
</header>

<!-- Main Content -->
<main class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="text-center mb-12">
        <h1 class="text-5xl font-bold text-gray-900 mb-4">Our Products</h1>
        <p class="text-gray-600 text-xl">Discover our premium collection of footwear</p>
        <p class="text-sm text-gray-500 mt-2">
            <i class="fas fa-box mr-1"></i><?php echo count($products); ?> products available
        </p>
    </div>

    <!-- Enhanced Filter/Sort Section -->
    <div class="bg-white rounded-2xl p-6 mb-8 shadow-sm border border-gray-100">
        <div class="flex flex-wrap justify-between items-center">
            <div class="flex flex-wrap gap-3 items-center mb-4 lg:mb-0">
                <span class="font-semibold text-gray-700 text-lg">Filter by category:</span>
                <button onclick="filterProducts('all')" class="filter-btn active px-6 py-3 rounded-full bg-gray-200 hover:bg-gray-300 transition-all font-medium">
                    All (<?php echo count($products); ?>)
                </button>
                <?php 
                    $categories = array_unique(array_column($products, 'category'));
                    foreach ($categories as $category):
                        $count = count(array_filter($products, fn($p) => $p['category'] === $category));
                ?>
                    <button onclick="filterProducts('<?php echo $category; ?>')" class="filter-btn px-6 py-3 rounded-full bg-gray-200 hover:bg-gray-300 transition-all font-medium">
                        <?php echo ucfirst($category); ?> (<?php echo $count; ?>)
                    </button>
                <?php endforeach; ?>
            </div>
            <div class="flex gap-4 items-center">
                <span class="font-semibold text-gray-700">Sort by:</span>
                <select id="sort-select" onchange="sortProducts()" class="px-4 py-3 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="default">Default</option>
                    <option value="price-low">Price: Low to High</option>
                    <option value="price-high">Price: High to Low</option>
                    <option value="name">Name: A to Z</option>
                    <option value="rating">Highest Rated</option>
                    <option value="stock">Stock Level</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" id="products-grid">
        <?php foreach ($products as $product): ?>
            <?php 
                $displayPrice = convertCurrency($product['price_ksh'], $current_currency);
                $formattedPrice = formatCurrency($product['price_ksh'], $current_currency);
                $oldPrice = formatCurrency($product['price_ksh'] * 1.3, $current_currency);
                
                // Determine stock level class
                $stock = $product['stock'] ?? 20;
                $stockClass = $stock < 10 ? 'stock-low' : ($stock < 20 ? 'stock-medium' : 'stock-high');
                $stockIcon = $stock < 10 ? 'fa-exclamation-triangle' : ($stock < 20 ? 'fa-clock' : 'fa-check-circle');
                
                // Generate star rating
                $rating = $product['rating'] ?? 4.0;
                $fullStars = floor($rating);
                $halfStar = ($rating - $fullStars) >= 0.5;
            ?>
            <div class="product-card" 
                data-category="<?php echo $product['category']; ?>" 
                data-price="<?php echo $product['price_ksh']; ?>" 
                data-name="<?php echo htmlspecialchars($product['name']); ?>"
                data-rating="<?php echo $rating; ?>"
                data-stock="<?php echo $stock; ?>">
                <div class="relative overflow-hidden">
                    <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" class="product-image">
                    <div class="category-badge"><?php echo ucfirst($product['category']); ?></div>
                    <?php if (isset($product['featured']) && $product['featured']): ?>
                        <div class="featured-badge">
                            <i class="fas fa-star mr-1"></i>Featured
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2 text-gray-900 line-clamp-1"><?php echo $product['name']; ?></h3>
                    
                    <!-- Rating -->
                    <div class="rating">
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
                        <span>(<?php echo $rating; ?>)</span>
                    </div>
                    
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2"><?php echo $product['description']; ?></p>
                    
                    <div class="mb-4">
                        <span class="old-price"><?php echo $oldPrice; ?></span>
                        <span class="price-tag"><?php echo $formattedPrice; ?></span>
                    </div>
                    
                    <!-- Stock Indicator -->
                    <div class="stock-indicator <?php echo $stockClass; ?>">
                        <i class="fas <?php echo $stockIcon; ?> mr-2"></i>
                        <?php if ($stock < 10): ?>
                            Only <?php echo $stock; ?> left in stock!
                        <?php elseif ($stock < 20): ?>
                            <?php echo $stock; ?> available
                        <?php else: ?>
                            In Stock (<?php echo $stock; ?>)
                        <?php endif; ?>
                    </div>
                    
                    <div class="mb-4 mt-3">
                        <p class="text-sm text-gray-600 mb-2">Available sizes:</p>
                        <div class="flex flex-wrap gap-1">
                            <?php foreach ($product['available_sizes'] as $size): ?>
                                <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-md border"><?php echo $size; ?></span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="product.php?id=<?php echo $product['id']; ?>" 
                           class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all text-center text-sm font-semibold transform hover:scale-105">
                            <i class="fas fa-eye mr-2"></i>View Details
                        </a>
                        <button onclick="quickAddToCart(<?php echo htmlspecialchars(json_encode($product)); ?>)" 
                                class="bg-gradient-to-r from-green-600 to-green-700 text-white py-3 px-4 rounded-lg hover:from-green-700 hover:to-green-800 transition-all transform hover:scale-105">
                            <i class="fas fa-cart-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Empty state (hidden by default) -->
    <div id="no-products" class="text-center py-20 hidden">
        <i class="fas fa-search text-8xl text-gray-300 mb-6"></i>
        <h3 class="text-2xl font-semibold text-gray-600 mb-3">No products found</h3>
        <p class="text-gray-500 text-lg">Try adjusting your filters or search criteria</p>
        <button onclick="filterProducts('all')" class="mt-4 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Show All Products
        </button>
    </div>
</main>

<!-- Enhanced Quick Add Modal -->
<div id="quick-add-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full mx-4 transform scale-95 transition-transform">
        <div class="text-center">
            <i class="fas fa-tshirt text-4xl text-blue-600 mb-4"></i>
            <h3 class="text-2xl font-semibold mb-2">Select Size</h3>
            <p class="text-gray-600 mb-6">Choose your preferred size for this product</p>
            <div id="size-options" class="flex flex-wrap gap-3 justify-center mb-8">
                <!-- Size options will be populated here -->
            </div>
            <div class="flex gap-4">
                <button onclick="closeQuickAdd()" class="flex-1 bg-gray-200 text-gray-700 py-3 px-6 rounded-lg hover:bg-gray-300 transition-colors font-semibold">
                    <i class="fas fa-times mr-2"></i>Cancel
                </button>
                <button onclick="confirmQuickAdd()" class="flex-1 bg-gradient-to-r from-green-600 to-green-700 text-white py-3 px-6 rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-semibold">
                    <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

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
                <span>Free pickup at our shop</span>
            </div>
        </div>
    </div>
</footer>

<script>
// The product object for the quick-add modal
let quickAddProduct = null;
let selectedSize = null;

// Products data for client-side filtering and sorting
const productsData = <?php echo json_encode($products); ?>;
const productGrid = document.getElementById('products-grid');
const filterButtons = document.querySelectorAll('.filter-btn');
const sortSelect = document.getElementById('sort-select');
const noProductsMessage = document.getElementById('no-products');
const cartCountElement = document.getElementById('cart-count');

// Functions for filtering products
function filterProducts(category) {
    // Update active state of filter buttons
    filterButtons.forEach(btn => {
        btn.classList.remove('active');
    });
    document.querySelector(`.filter-btn[onclick="filterProducts('${category}')"]`).classList.add('active');

    const productCards = document.querySelectorAll('.product-card');
    let foundProducts = false;
    productCards.forEach(card => {
        if (category === 'all' || card.dataset.category === category) {
            card.style.display = 'block';
            foundProducts = true;
        } else {
            card.style.display = 'none';
        }
    });

    if (foundProducts) {
        noProductsMessage.classList.add('hidden');
    } else {
        noProductsMessage.classList.remove('hidden');
    }
}

// Functions for sorting products
function sortProducts() {
    const sortBy = sortSelect.value;
    const sortedProducts = [...productsData];

    switch(sortBy) {
        case 'price-low':
            sortedProducts.sort((a, b) => a.price_ksh - b.price_ksh);
            break;
        case 'price-high':
            sortedProducts.sort((a, b) => b.price_ksh - a.price_ksh);
            break;
        case 'name':
            sortedProducts.sort((a, b) => a.name.localeCompare(b.name));
            break;
        case 'rating':
            sortedProducts.sort((a, b) => b.rating - a.rating);
            break;
        case 'stock':
            sortedProducts.sort((a, b) => a.stock - b.stock);
            break;
        case 'default':
        default:
            // The default sort order from PHP is already applied
            break;
    }

    // Re-render the grid with sorted products
    productGrid.innerHTML = '';
    sortedProducts.forEach(product => {
        const tempDiv = document.createElement('div');
        tempDiv.innerHTML = getProductCardHtml(product);
        productGrid.appendChild(tempDiv.firstChild);
    });
}

// Helper function to generate HTML for a product card
function getProductCardHtml(product) {
    const current_currency = '<?php echo $current_currency; ?>';
    const convertedPrice = convertCurrency(product.price_ksh, current_currency);
    const formattedPrice = formatCurrency(product.price_ksh, current_currency);
    const oldPrice = formatCurrency(product.price_ksh * 1.3, current_currency);

    const stock = product.stock ?? 20;
    const stockClass = stock < 10 ? 'stock-low' : (stock < 20 ? 'stock-medium' : 'stock-high');
    const stockIcon = stock < 10 ? 'fa-exclamation-triangle' : (stock < 20 ? 'fa-clock' : 'fa-check-circle');
    
    const rating = product.rating ?? 4.0;
    const fullStars = Math.floor(rating);
    const halfStar = (rating - fullStars) >= 0.5;
    
    let starHtml = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= fullStars) {
            starHtml += '<i class="fas fa-star"></i>';
        } else if (i === fullStars + 1 && halfStar) {
            starHtml += '<i class="fas fa-star-half-alt"></i>';
        } else {
            starHtml += '<i class="far fa-star"></i>';
        }
    }

    let sizesHtml = '';
    product.available_sizes.forEach(size => {
        sizesHtml += `<span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-md border">${size}</span>`;
    });

    const featuredBadge = product.featured ? `<div class="featured-badge"><i class="fas fa-star mr-1"></i>Featured</div>` : '';

    return `
        <div class="product-card" 
            data-category="${product.category}" 
            data-price="${product.price_ksh}" 
            data-name="${product.name}"
            data-rating="${rating}"
            data-stock="${stock}">
            <div class="relative overflow-hidden">
                <img src="${product.image_url}" alt="${product.name}" class="product-image">
                <div class="category-badge">${product.category.charAt(0).toUpperCase() + product.category.slice(1)}</div>
                ${featuredBadge}
            </div>
            
            <div class="p-6">
                <h3 class="text-xl font-semibold mb-2 text-gray-900 line-clamp-1">${product.name}</h3>
                
                <!-- Rating -->
                <div class="rating">
                    <div class="stars">${starHtml}</div>
                    <span>(${rating})</span>
                </div>
                
                <p class="text-gray-600 text-sm mb-4 line-clamp-2">${product.description}</p>
                
                <div class="mb-4">
                    <span class="old-price">${oldPrice}</span>
                    <span class="price-tag">${formattedPrice}</span>
                </div>
                
                <!-- Stock Indicator -->
                <div class="stock-indicator ${stockClass}">
                    <i class="fas ${stockIcon} mr-2"></i>
                    ${stock < 10 ? `Only ${stock} left in stock!` : (stock < 20 ? `${stock} available` : `In Stock (${stock})`)}
                </div>
                
                <div class="mb-4 mt-3">
                    <p class="text-sm text-gray-600 mb-2">Available sizes:</p>
                    <div class="flex flex-wrap gap-1">${sizesHtml}</div>
                </div>
                
                <div class="flex gap-2">
                    <a href="product.php?id=${product.id}" 
                       class="flex-1 bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 px-4 rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all text-center text-sm font-semibold transform hover:scale-105">
                        <i class="fas fa-eye mr-2"></i>View Details
                    </a>
                    <button onclick="quickAddToCart(${product.id}, '${product.name}', ${product.price_ksh}, '${product.image_url}', '${JSON.stringify(product.available_sizes).replace(/'/g, "\\'")}')" 
                            class="bg-gradient-to-r from-green-600 to-green-700 text-white py-3 px-4 rounded-lg hover:from-green-700 hover:to-green-800 transition-all transform hover:scale-105">
                        <i class="fas fa-cart-plus"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Quick Add to Cart Modal Functions
function quickAddToCart(product_id, product_name, product_price, product_image, available_sizes_json) {
    const modal = document.getElementById('quick-add-modal');
    const sizeOptionsDiv = document.getElementById('size-options');
    const available_sizes = JSON.parse(available_sizes_json);

    // Store the product details in a global variable
    quickAddProduct = {
        id: product_id,
        name: product_name,
        price: product_price,
        image: product_image,
        available_sizes: available_sizes
    };
    
    // Clear previous size options
    sizeOptionsDiv.innerHTML = '';
    selectedSize = null;

    available_sizes.forEach(size => {
        const sizeButton = document.createElement('button');
        sizeButton.textContent = size;
        sizeButton.classList.add('size-btn', 'px-4', 'py-2', 'rounded-full', 'bg-gray-100', 'hover:bg-gray-200', 'transition-colors', 'font-medium');
        sizeButton.onclick = () => {
            document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('bg-blue-600', 'text-white'));
            sizeButton.classList.add('bg-blue-600', 'text-white');
            selectedSize = size;
        };
        sizeOptionsDiv.appendChild(sizeButton);
    });

    modal.classList.remove('hidden');
    modal.querySelector('.transform').classList.remove('scale-95');
    modal.querySelector('.transform').classList.add('scale-100');
}

function closeQuickAdd() {
    const modal = document.getElementById('quick-add-modal');
    modal.querySelector('.transform').classList.remove('scale-100');
    modal.querySelector('.transform').classList.add('scale-95');
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 200);
}

function confirmQuickAdd() {
    if (!selectedSize) {
        alert('Please select a size first.');
        return;
    }

    const formData = new FormData();
    formData.append('add_to_cart', 1);
    formData.append('product_name', quickAddProduct.name);
    formData.append('product_price', quickAddProduct.price);
    formData.append('product_image', quickAddProduct.image);
    formData.append('selected_size', selectedSize);
    formData.append('quantity', 1); // Quick add adds 1 item

    fetch('products.php', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest' // Indicate an AJAX request
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            cartCountElement.textContent = data.cart_count;
            closeQuickAdd();
        }
    })
    .catch(error => console.error('Error:', error));
}


// Homepage sync functionality
function syncWithHomepage() {
    const syncNotification = document.getElementById('sync-notification');
    const syncMessage = document.getElementById('sync-message');
    
    // Show syncing message
    syncMessage.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Syncing with homepage...';
    syncNotification.classList.add('show');
    
    // Get featured products for sync
    const featuredProductsData = productsData.filter(p => p.featured);

    // Simulate API call to sync with homepage
    fetch('products.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'sync_homepage_products=1&homepage_products=' + encodeURIComponent(JSON.stringify(featuredProductsData))
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            syncMessage.innerHTML = '<i class="fas fa-check mr-2"></i>Products synced with homepage!';
            
            // Store in localStorage for homepage to access
            localStorage.setItem('featuredProducts', JSON.stringify(featuredProductsData));
            localStorage.setItem('lastSync', new Date().toISOString());
            
            // Hide notification after 3 seconds
            setTimeout(() => {
                syncNotification.classList.remove('show');
            }, 3000);
        }
    })
    .catch(error => {
        syncMessage.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Sync failed!';
        console.error('Error syncing with homepage:', error);
        setTimeout(() => {
            syncNotification.classList.remove('show');
        }, 3000);
    });
}

// Initial call to hide no-products message
document.addEventListener('DOMContentLoaded', () => {
    filterProducts('all');
});
</script>

</body>
</html>
