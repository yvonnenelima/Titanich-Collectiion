<?php
// Start session to access user data
session_start();

include 'config.php'; // Include the DB connection

// Fetch admin products if available
$admin_products = [];
if (isset($conn) && $conn) {
    $admin_query = "SELECT * FROM products ORDER BY created_at DESC";
    $admin_result = mysqli_query($conn, $admin_query);
    if ($admin_result) {
        while ($row = mysqli_fetch_assoc($admin_result)) {
            $admin_products[] = $row;
            // Get featured shoes saved from products.php
$featured_products = $_SESSION['featured_products'] ?? [];

        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titanic Collection</title>
    <link rel="shortcut icon" href="https://img.icons8.com/fluent/48/000000/enter-2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS CDN for the new sections -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background-color: #ffffff; /* Changed to white */
        }
        .main-header {
            background: #40E0D0; /* Turquoise blue */
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }
        .nav-menu {
            display: flex;
            justify-content: flex-start;
            gap: 20px;
            padding: 10px 0;
        }
        .nav-menu a {
            text-decoration: none;
            color: #FFFFFF; /* White for contrast */
            font-weight: bold;
            padding: 8px 12px;
        }
        .nav-menu a:hover {
            color: #333;
        }
        .top-bar {
            display: flex;
            justify-content: space-between;
            padding: 8px 20px;
            background-color: #eee;
            font-size: 14px;
        }
        .logo-search-cart {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 20px;
            flex-wrap: wrap;
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .logo img {
            height: 40px;
        }
        .search-bar input {
            padding: 6px;
            width: 200px;
        }
        .search-bar button {
            padding: 6px 10px;
        }
        .user-cart a {
            margin-left: 15px;
            color: #333;
        }
        .cart-count {
            background: red;
            color: white;
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 50%;
            margin-left: 2px;
        }

        /* Updated hero section for video */
        .video-hero {
            position: relative;
            width: 100%;
            height: 400px; /* Maintain similar height to original hero */
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #000; /* Fallback background for video */
        }
        .video-hero {
            position: relative;
            width: 100%;
            height: 250px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #000;
        }

        .video-hero video {
            width: 600px;
            height: 480px;
            z-index: 0;
            position: relative;
            border-radius: 8px;
        }

        .video-hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.4); /* Dark overlay for text readability */
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            z-index: 1; /* Ensure overlay is above video */
        }
        .video-hero-overlay h1 {
            font-size: 48px;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.6); /* Background for text */
            border-radius: 8px;
        }

        /* Admin badge for new products */
        .admin-badge {
            position: absolute;
            top: 8px;
            left: 8px;
            background: linear-gradient(45deg, #4CAF50, #45a049);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            z-index: 10;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }

        .out-of-stock-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background: linear-gradient(45deg, #f44336, #d32f2f);
            color: white;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            z-index: 10;
        }

        /* Existing styles for featured products and footer */
        .featured {
            padding: 40px 20px;
            text-align: center;
        }
        .featured h2 {
            margin-bottom: 30px;
        }
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
        }
        .product {
            border: 1px solid #ccc;
            border-radius: 8px;
            padding: 15px;
            width: 220px;
            text-align: center;
            background-color: #fff;
            transition: 0.3s;
            position: relative;
        }
        .product:hover {
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .product img {
            width: 100%;
            height: auto;
            border-radius: 5px;
        }
        .product h3 {
            font-size: 18px;
            margin: 10px 0 5px;
        }
        .product p {
            font-size: 16px;
            color: #28a745;
        }

        /* Custom scrollbar for better aesthetics - from Kapeer layout */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Styles for the new "NEW IN" section */
        .new-in-section {
            padding: 40px 20px;
            text-align: center;
        }
        .new-in-section h2 {
            margin-bottom: 30px;
            font-size: 2xl;
            font-weight: 600;
            color: #333;
            text-align: left;
            padding-left: 1rem; /* Adjust as needed for alignment */
        }
        .new-in-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            justify-content: center;
        }
        .new-in-product {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            text-align: center;
            transition: transform 0.3s ease;
            position: relative;
        }
        .new-in-product:hover {
            transform: translateY(-5px);
        }
        .new-in-product img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }
        .new-in-product-info {
            padding: 15px;
        }
        .new-in-product-info h3 {
            font-size: 16px;
            font-weight: 500;
            color: #333;
            margin-bottom: 5px;
        }
        .new-in-product-info p {
            font-size: 14px;
            color: #666;
            font-weight: bold;
        }

        /* Stock indicator styles */
        .stock-indicator {
            font-size: 12px;
            padding: 2px 6px;
            border-radius: 10px;
            margin-top: 5px;
            display: inline-block;
        }
        .stock-low {
            background-color: #ff4444;
            color: white;
        }
        .stock-normal {
            background-color: #44ff44;
            color: #333;
        }
        .stock-out {
            background-color: #cccccc;
            color: #666;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 100;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(5px);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border-radius: 12px;
            width: 80%;
            max-width: 400px;
            text-align: center;
            animation-name: animatetop;
            animation-duration: 0.4s;
        }
        .close-button {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close-button:hover,
        .close-button:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        @keyframes animatetop {
            from {top: -300px; opacity: 0}
            to {top: 0; opacity: 1}
        }

        /* User greeting styles */
        .user-greeting {
            color: white;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .user-greeting i {
            font-size: 18px;
        }

        /* Login status indicator (for debugging) */
        .login-status {
            position: fixed;
            top: 10px;
            right: 10px;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            z-index: 1000;
        }
        .logged-in-status {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .logged-out-status {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body class="antialiased">

<!-- Login Status Indicator (for debugging - remove in production) -->
<?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
    <div class="login-status logged-in-status">
        ✓ Logged in as: <?php echo htmlspecialchars($_SESSION['user_name']); ?>
    </div>
<?php else: ?>
    <div class="login-status logged-out-status">
        ✗ Not logged in
    </div>
<?php endif; ?>

<!-- Top bar -->
<div class="top-bar">
    <div><i class="fa-solid fa-check-double"></i> FAST DELIVERY</div>
    <!-- Updated Contact Us link with onclick handler -->
    <div><i class="fa-solid fa-phone"></i> <a href="#" id="openContactModal">Contact us</a> | <a href="admin.php" style="color: #666;">Admin</a></div>
</div>
    
<!-- Header -->
<header class="main-header">
    <div class="logo-search-cart">
        <div class="logo">
            <img src="./images/logo.jpg.jpg" alt="Titan">
            <span style="color: white; font-weight: bold;">TITANIC COLLECTION</span>
        </div>
        <div class="catalog-dropdown">
            <button>View catalog <i class="fa fa-caret-down"></i></button>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="What are you looking for?">
            <button><i class="fa fa-search"></i></button>
        </div>
        <div class="user-cart">
            <?php if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true): ?>
                <!-- User is logged in, show greeting and logout -->
                <div class="user-greeting">
                    <i class="fa fa-user-circle"></i>
                    <span>Hello, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</span>
                    <a href="logout.php" style="color: white; margin-left: 10px; text-decoration: underline;">Logout</a>
                </div>
            <?php else: ?>
                <!-- User is not logged in, show sign in link with redirect to homepage -->
                <a href="signin.php?redirect=index.php"><i class="fa fa-user-circle"></i></a>
            <?php endif; ?>
            <a href="cart.php"><i class="fa fa-shopping-bag"></i><span class="cart-count">0</span></a>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="nav-menu">
        <a href="index.php">HOME</a>
        <a href="products.php">PRODUCTS</a>
        <a href="contact.php">CONTACT US</a>
        <a href="about.php">ABOUT US</a>
    </nav>
</header>

<!-- Video Hero Section -->
<div class="video-hero">
    <?php $videoPath = "videos/myvideo.mp4"; ?>

    <video width="600" height="480" autoplay muted loop playsinline>
        <source src="<?php echo $videoPath; ?>" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="video-hero-overlay">
        <h1>Step into Greatness with Titanic Collection</h1>
    </div>
</div>

<!-- Main Content Area -->
<main class="flex-grow container mx-auto px-4 py-8 md:px-10 lg:px-16">

    <!-- NEW IN Section - Now displays newest admin products -->
    <section class="new-in-section">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">NEW IN</h2>
        </div>
        <div class="new-in-grid">
            <?php
            // Combine admin products with default products, prioritizing newest admin products
            $new_products = [];
            
            // Add newest admin products first (limit to 4 most recent)
            if (!empty($admin_products)) {
                // Sort admin products by creation date, newest first
                usort($admin_products, function($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });
                
                $newest_admin = array_slice($admin_products, 0, 4);
                foreach ($newest_admin as $product) {
                    $new_products[] = [
                        'name' => $product['name'],
                        'price' => 'KSh' . number_format($product['price']) . '.00',
                        'image' => $product['image'],
                        'is_admin' => true,
                        'stock' => $product['stock']
                    ];
                }
            }
            
            // Fill remaining slots with default products if needed
            if (count($new_products) < 4) {
                $default_products = [
                    ['name' => 'New Balance 530', 'price' => 'KSh4500.00', 'image' => 'images/Nb.jpeg', 'is_admin' => false],
                    ['name' => 'Naked Wolfe', 'price' => 'KSh4000.00', 'image' => 'images/Nw.jpeg', 'is_admin' => false],
                    ['name' => 'Jordan Retro 3', 'price' => 'KSh3500.00', 'image' => 'images/Jr.jpeg', 'is_admin' => false],
                    ['name' => 'Airmax 95 Futura', 'price' => 'KSh3000.00', 'image' => 'images/Am.jpeg', 'is_admin' => false],
                ];
                
                $needed = 4 - count($new_products);
                $new_products = array_merge($new_products, array_slice($default_products, 0, $needed));
            }

            foreach ($new_products as $product) {
                $stock_class = '';
                $stock_text = '';
                $out_of_stock = false;
                
                if (isset($product['stock'])) {
                    if ($product['stock'] == 0) {
                        $stock_class = 'stock-out';
                        $stock_text = 'Out of Stock';
                        $out_of_stock = true;
                    } elseif ($product['stock'] <= 5) {
                        $stock_class = 'stock-low';
                        $stock_text = 'Low Stock (' . $product['stock'] . ')';
                    } else {
                        $stock_class = 'stock-normal';
                        $stock_text = 'In Stock (' . $product['stock'] . ')';
                    }
                }
                
                echo '
                <div class="new-in-product">
                    ' . ($out_of_stock ? '<div class="out-of-stock-badge">OUT OF STOCK</div>' : '') . '
                    ' . (isset($product['is_admin']) && $product['is_admin'] ? '<div class="admin-badge">NEW</div>' : '') . '
                    <a href="' . ($out_of_stock ? '#' : 'product-detail.php?name=' . urlencode($product['name']) . '&price=' . urlencode($product['price']) . '&image=' . urlencode($product['image'])) . '" ' . ($out_of_stock ? 'style="pointer-events: none; opacity: 0.6;"' : '') . '>
                        <img src="' . $product['image'] . '" alt="' . $product['name'] . '">
                    </a>
                    <div class="new-in-product-info">
                        <h3>' . $product['name'] . '</h3>
                        <p>' . $product['price'] . '</p>
                        ' . ($stock_text ? '<div class="stock-indicator ' . $stock_class . '">' . $stock_text . '</div>' : '') . '
                    </div>
                </div>';
            }
            ?>
        </div>
    </section>

    <!-- Featured Products Section - Shows all admin products plus defaults -->
    <section class="mt-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800">FEATURED PRODUCTS</h2>
            <a href="#" class="bg-gray-800 hover:bg-gray-900 text-white font-bold py-2 px-4 rounded-md shadow-md transition-colors duration-300">VIEW ALL</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            <?php
            // Combine admin products with default products for featured section
            $all_products = [];
            
            // Add admin products first
            if (!empty($admin_products)) {
                foreach ($admin_products as $product) {
                    $all_products[] = [
                        'name' => $product['name'],
                        'category' => 'ADMIN ADDED',
                        'price' => 'KSh' . number_format($product['price']) . '.00',
                        'image' => $product['image'],
                        'is_admin' => true,
                        'stock' => $product['stock']
                    ];
                }
            }
            
            // Add default products
            $default_featured = [
                ['name' => 'Wetlook', 'category' => 'TACTICAL BOOTS', 'price' => 'KSh6000.00', 'image' => 'images/Wetlook.jpg', 'is_admin' => false],
                ['name' => 'Salvatore Ferragamo', 'category' => 'SHOES, UNISEX', 'price' => 'KSh5500.00', 'image' => 'images/Salvatore Ferragamo.jpg', 'is_admin' => false],
                ['name' => 'Adidas Samba', 'category' => 'UNISEX', 'price' => 'KSh4500.00', 'image' => 'images/Adidas Samba.jpg', 'is_admin' => false],
                ['name' => 'Chelsea boots', 'category' => 'SHOES, UNISEX', 'price' => 'KSh7000.00', 'image' => 'images/Chelsea boots.jpg', 'is_admin' => false],
                ['name' => 'Jordan 4', 'category' => 'SHOES, SNEAKERS', 'price' => 'KSh8500.00', 'image' => 'images/Jordan 4.jpg', 'is_admin' => false],
                ['name' => 'Tommy', 'category' => 'CASUAL', 'price' => 'KSh4000.00', 'image' => 'images/Tommy.jpg', 'is_admin' => false],
                ['name' => 'SB Dunk', 'category' => 'SNEAKERS', 'price' => 'KSh7500.00', 'image' => 'images/SB dunk.jpg', 'is_admin' => false],
                ['name' => 'Versace Mules', 'category' => 'SHOES', 'price' => 'KSh9000.00', 'image' => 'images/Versace Mules.jpg', 'is_admin' => false],
            ];
            
            $all_products = array_merge($all_products, $default_featured);

            foreach ($all_products as $product) {
                $stock_class = '';
                $stock_text = '';
                $out_of_stock = false;
                
                if (isset($product['stock'])) {
                    if ($product['stock'] == 0) {
                        $stock_class = 'stock-out';
                        $stock_text = 'Out of Stock';
                        $out_of_stock = true;
                    } elseif ($product['stock'] <= 5) {
                        $stock_class = 'stock-low';
                        $stock_text = 'Low Stock (' . $product['stock'] . ')';
                    } else {
                        $stock_class = 'stock-normal';
                        $stock_text = 'In Stock (' . $product['stock'] . ')';
                    }
                }
                
                echo '
                <div class="new-in-product">
                    ' . ($out_of_stock ? '<div class="out-of-stock-badge">OUT OF STOCK</div>' : '') . '
                    ' . (isset($product['is_admin']) && $product['is_admin'] ? '<div class="admin-badge">ADMIN</div>' : '') . '
                    <a href="' . ($out_of_stock ? '#' : 'product-detail.php?name=' . urlencode($product['name']) . '&price=' . urlencode($product['price']) . '&image=' . urlencode($product['image'])) . '" ' . ($out_of_stock ? 'style="pointer-events: none; opacity: 0.6;"' : '') . '>
                        <img src="' . $product['image'] . '" alt="' . $product['name'] . '">
                    </a>
                    <div class="new-in-product-info">
                        <h3>' . $product['name'] . '</h3>
                        <p>' . $product['price'] . '</p>
                        ' . ($stock_text ? '<div class="stock-indicator ' . $stock_class . '">' . $stock_text . '</div>' : '') . '
                    </div>
                </div>';
            }
            ?>
        </div>
    </section>
</main>

<!-- footer.php -->
<footer class="bg-cyan-100 py-12 border-t border-gray-200">
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
                    <a href="tel:0741421583" class="hover:underline">0741421583</a>
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
                    <a href="https://wa.me/254741421583" target="_blank" class="hover:underline font-semibold">Ask your questions on WhatsApp</a>
                </p>
            </div>
        </div>

        <div>
            <h3 class="text-xl font-bold mb-6 text-gray-800">Follow us</h3>
            <div class="flex space-x-4 mb-8">
                <!-- Social media icons with placeholder links -->
                <a href="#" target="_blank" class="text-gray-600 hover:text-gray-900">
                    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.71.01 3.653.051.92.04 1.5.17 1.943.344.475.187.893.472 1.284.86.391.391.674.809.86 1.284.174.444.304 1.023.344 1.943.04.943.051 1.223.051 3.653s-.01 2.71-.051 3.653c-.04.92-.17 1.5-.344 1.943-.187.475-.472.893-.86 1.284-.391.391-.809.674-1.284.86-.444.174-1.023.304-1.943.344-.943.04-1.223.051-3.653.051s-2.71-.01-3.653-.051c-.92-.04-1.5-.17-1.943-.344-.475-.187-.893-.472-1.284-.86-.391-.391-.674-.809-.86-1.284-.174-.444-.304-1.023-.344-1.943-.04-.943-.051-1.223-.051-3.653s.01-2.71.051-3.653c.04-.92.17-1.5.344-1.943.187-.475.472-.893.86-1.284.391-.391.809-.674 1.284-.86.444-.174 1.023-.304 1.943-.344.943-.04 1.223-.051 3.653-.051s2.71.01 3.653.051c.92.04 1.5.17 1.943.344.475.187.893.472 1.284.86.391.391.674.809.86 1.284.174.444.304 1.023.344 1.943.04.943.051 1.223.051 3.653s-.01 2.71-.051 3.653c-.04.92-.17 1.5-.344 1.943-.187.475-.472.893-.86 1.284-.391.391-.809.674-1.284.86-.444.174-1.023.304-1.943.344-.943.04-1.223.051-3.653.051s-2.71-.01-3.653-.051c-.92-.04-1.5-.17-1.943-.344-.475-.187-.893-.472-1.284-.86-.391-.391-.674-.809-.86-1.284-.174-.444-.304-1.023-.344-1.943-.04-.943-.051-1.223-.051-3.653s.01-2.71.051-3.653c.04-.92.17-1.5.344-1.943.187-.475.472-.893.86-1.284.391-.391.809-.674 1.284-.86.444-.174 1.023-.304 1.943-.344.943-.04 1.223-.051 3.653-.051zm-1.5 5.86c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7-7-3.13-7-7zM12 9c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zm5.5 3.5c0 .28-.22.5-.5.5s-.5-.22-.5-.5.22-.5.5-.5.5.22.5.5z" clip-rule="evenodd" /></svg>
                </a>
                <a href="#" target="_blank" class="text-gray-600 hover:text-gray-900">
                    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12 0C5.373 0 0 5.373 0 12c0 5.093 3.067 9.404 7.427 11.272.541.099.648-.235.648-.521 0-.256-.009-1.04-.016-2.035-2.883.627-3.493-1.385-3.493-1.385-.47-.962-1.147-1.22-1.147-1.22-.939-.64-.07-.626.07-.614 1.02.072 1.558 1.05 1.558 1.05.906 1.55 2.378 1.1 2.955.845.092-.656.353-1.1.644-1.352-2.257-.256-4.634-1.13-4.634-5.018 0-1.107.394-2.012 1.037-2.723-.105-.256-.45-1.29.098-2.684 0 0 .846-.272 2.775 1.036.8-.22 1.65-.33 2.5-.33.85 0 1.7.11 2.5.33 1.928-1.308 2.775-1.036 2.775-1.036.54 1.394.195 2.428.098 2.684.643.711 1.037 1.616 1.037 2.723 0 3.896-2.377 4.757-4.641 5.011.365.314.686.937.686 1.892 0 1.352-.013 2.441-.013 2.766 0 .288.106.625.654.52C20.933 21.404 24 17.093 24 12c0-6.627-5.373-12-12-12z" /></svg>
                </a>
                <a href="#" target="_blank" class="text-gray-600 hover:text-gray-900">
                    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19.812 5.404c.83-.09 1.408.061 1.831.332.396.257.697.665.848 1.157.102.348.151.722.151 1.161v6.946c0 .439-.049.813-.151 1.161-.151.492-.452.9-.848 1.157-.423.27-.993.407-1.831.332-.716-.065-1.053-.13-1.632-.207-1.124-.149-2.73-.243-4.75-.285-1.107-.023-2.12-.034-3.047-.034s-1.94.011-3.047.034c-2.02.042-3.626.136-4.75.285-.579.077-.916.142-1.632.207-.83.09-1.408-.061-1.831-.332-.396-.257-.697-.665-.848-1.157-.102-.348-.151-.722-.151-1.161V8.054c0-.439.049-.813.151-1.161.151-.492.452-.9.848-1.157.423-.27.993-.407 1.831-.332.716.065 1.053.13 1.632.207 1.124.149 2.73.243 4.75.285 1.107.023 2.12.034 3.047.034s1.94-.011 3.047-.034c2.02-.042 3.626-.136 4.75-.285zM9.458 9.303v5.408l5.44-2.704-5.44-2.704z" clip-rule="evenodd" /></svg>
                </a>
                <a href="#" target="_blank" class="text-gray-600 hover:text-gray-900">
                    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.776-3.89 1.094 0 2.24.195 2.24.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.56V12h2.773l-.443 2.89h-2.33V22C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>
                </a>
                <a href="#" target="_blank" class="text-gray-600 hover:text-gray-900">
                    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.21-6.174L4.99 21.75H1.68l7.73-8.825L1.254 2.25H8.08l4.714 5.918L18.244 2.25zm-2.972 1.34h-.838L7.546 17.38h1.615L16.272 3.59z" /></svg>
                </a>
                <a href="#" target="_blank" class="text-gray-600 hover:text-gray-900">
                    <svg class="h-7 w-7" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.525 2.118C14.288.629 16.279 0 16.279 0H21v5.151c0 4.545-3.056 7.844-7.394 7.844-1.956 0-3.805-.623-5.321-1.748L11.536 21H17v-4.131c0-4.545 3.056-7.844 7.394-7.844h-.001c.21 0 .421.002.631.004V0H12.525Z" /></svg>
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

<!-- The Modal -->
<div id="contactModal" class="modal">
  <div class="modal-content">
    <span class="close-button" onclick="closeModal()">&times;</span>
    <h2 class="text-2xl font-bold mb-4">Contact Us</h2>
    <p class="text-lg mb-2">
      <i class="fa fa-phone-alt text-teal-500 mr-2"></i>
      <a href="tel:0741421583" class="text-gray-700 hover:underline">0741421583</a>
    </p>
    <p class="text-lg">
      <i class="fa fa-envelope text-teal-500 mr-2"></i>
      <a href="mailto:titanich2024@gmail.com" class="text-gray-700 hover:underline">titanich2024@gmail.com</a>
    </p>
  </div>
</div>

<script>
    // Get the modal
    var modal = document.getElementById("contactModal");

    // Get the button that opens the modal
    var btn = document.getElementById("openContactModal");

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close-button")[0];

    // When the user clicks on the button, open the modal
    btn.onclick = function() {
      modal.style.display = "block";
    }

    // When the user clicks on <span> (x), close the modal
    span.onclick = function() {
      modal.style.display = "none";
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target == modal) {
        modal.style.display = "none";
      }
    }
    
    // Function to close the modal, can be called by the close button
    function closeModal() {
        modal.style.display = "none";
    }

    // Auto-refresh functionality to check for new products
    setInterval(function() {
        // Check if there are new products from admin
        // This would typically be an AJAX call in a real application
        console.log('Checking for new products...');
    }, 30000); // Check every 30 seconds

    // Search functionality
    document.querySelector('.search-bar input').addEventListener('keyup', function(e) {
        if (e.key === 'Enter') {
            const searchTerm = this.value.toLowerCase();
            const products = document.querySelectorAll('.new-in-product');
            
            products.forEach(product => {
                const productName