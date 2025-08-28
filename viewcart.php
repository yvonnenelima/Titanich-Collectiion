<?php
// viewcart.php (for displaying the cart HTML)
session_start();

// Check login state and determine the correct cart key
$isLoggedIn = isset($_SESSION['user']);
$cartKey = $isLoggedIn ? 'user_cart_' . $_SESSION['user']['id'] : 'cart';

// Get the current cart from the session
$cart = isset($_SESSION[$cartKey]) ? $_SESSION[$cartKey] : [];

// Calculate initial total
$total = array_reduce($cart, fn($carry, $item) => $carry + (floatval($item['price']) * intval($item['quantity'])), 0);

// Include your header here if it's a separate file
// include 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart - KICKS KENYA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Your CSS for the header and cart goes here */
        /* Header CSS */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f2f5; /* Lighter background for the page */
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
            position: absolute; /* Added for proper positioning over icon */
            top: -5px; /* Adjust as needed */
            right: -5px; /* Adjust as needed */
        }

        /* Cart specific styling */
        .cart-container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .cart-item {
            display: flex;
            align-items: center;
            border-bottom: 1px solid #e5e7eb; /* gray-200 */
            padding: 15px 0;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .cart-item-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }
        .cart-item-details {
            flex-grow: 1;
        }
        .cart-item-name {
            font-weight: bold;
            font-size: 1.125rem; /* text-lg */
            margin-bottom: 5px;
        }
        .cart-item-price {
            color: #4b5563; /* gray-700 */
            font-size: 0.9rem;
        }
        .cart-item-quantity {
            display: flex;
            align-items: center;
            margin-left: auto; /* Pushes to the right */
            margin-right: 20px;
        }
        .cart-item-quantity input {
            width: 60px;
            text-align: center;
            border: 1px solid #d1d5db; /* gray-300 */
            border-radius: 4px;
            padding: 5px;
            margin: 0 8px;
        }
        .cart-item-subtotal {
            font-weight: bold;
            text-align: right;
            width: 100px; /* Fixed width for alignment */
        }
        .remove-btn {
            background-color: #ef4444; /* red-500 */
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.2s;
            margin-left: 15px;
        }
        .remove-btn:hover {
            background-color: #dc2626; /* red-600 */
        }
        .cart-summary {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: right;
            font-size: 1.25rem; /* text-xl */
            font-weight: bold;
        }
        .cart-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .cart-actions a, .cart-actions button {
            padding: 12px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.2s;
        }
        .continue-shopping {
            background-color: #6b7280; /* gray-500 */
            color: white;
        }
        .continue-shopping:hover {
            background-color: #4b5563; /* gray-700 */
        }
        .proceed-checkout {
            background-color: #10b981; /* emerald-500 */
            color: white;
        }
        .proceed-checkout:hover {
            background-color: #059669; /* emerald-600 */
        }
        .message-box {
            background-color: #d1fae5; /* green-100 */
            color: #065f46; /* green-900 */
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            display: none; /* Hidden by default */
            position: relative;
        }
        .message-box.show {
            display: block;
        }
        .message-box button {
            position: absolute;
            top: 5px;
            right: 10px;
            background: transparent;
            border: none;
            font-size: 16px;
            cursor: pointer;
            color: #065f46;
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <div><i class="fa-solid fa-check-double"></i> FAST DELIVERY</div>
        <div><i class="fa-solid fa-phone"></i> <a href="#">Contact us</a></div>
    </div>

    <header class="main-header">
        <div class="logo-search-cart">
            <div class="logo">
                <img src="./images/logo.jpg.jpg" alt="Titan"> <span style="color: white; font-weight: bold;">TITANIC COLLECTION</span>
            </div>
            <div class="catalog-dropdown">
                <button>View catalog <i class="fa fa-caret-down"></i></button>
            </div>
            <div class="search-bar">
                <input type="text" placeholder="What are you looking for?">
                <button><i class="fa fa-search"></i></button>
            </div>
            <div class="user-cart">
                <a href="login.php"><i class="fa fa-user-circle"></i></a>
                <a href="cart.php" class="cart-icon" style="position: relative; display: inline-block;">
                    <i class="fa fa-shopping-cart" style="color: #333;"></i> <?php
                        // Replicate cart key logic for header's cart count
                        $cartItemCount = 0;
                        foreach ($currentCart as $item) { // $currentCart comes from the initial PHP block
                            $cartItemCount += $item['quantity'];
                        }
                    ?>
                    <?php if ($cartItemCount > 0): ?>
                        <span class="cart-count"><?php echo $cartItemCount; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <nav class="nav-menu">
            <a href="index.php">HOME</a>
            <a href="kicks.php">KICKS CATALOG</a>
            <a href="apparel.php">WOMEN'S KICK CATALOG</a>
            <a href="about.php">ABOUT US</a>
        </nav>
    </header>
    <div class="cart-container">
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Your Shopping Cart</h2>

        <div id="cart-message" class="message-box" role="alert">
            <span id="message-text"></span>
            <button type="button" onclick="this.parentElement.classList.remove('show');">&times;</button>
        </div>

        <div id="cart-items-display">
            <?php if (empty($cart)): ?>
                <p class="text-gray-600 text-center py-10">Your cart is empty. <a href="kicks.php" class="text-blue-600 hover:underline">Start shopping!</a></p>
            <?php else: ?>
                <?php foreach ($cart as $id => $item):
                    // Ensure price and quantity are numeric
                    $itemPrice = floatval($item['price']);
                    $itemQuantity = intval($item['quantity']);
                    $itemSubtotal = $itemPrice * $itemQuantity;
                ?>
                    <div class="cart-item" data-id="<?= htmlspecialchars($id) ?>">
                        <img src="<?= htmlspecialchars($item['image'] ?? 'https://via.placeholder.com/80') ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="cart-item-image">
                        <div class="cart-item-details">
                            <div class="cart-item-name"><?= htmlspecialchars($item['name']) ?></div>
                            <div class="cart-item-price">KSh<?= number_format($itemPrice, 2) ?></div>
                        </div>
                        <div class="cart-item-quantity">
                            <input type="number" value="<?= $itemQuantity ?>" min="1" class="qty-input" data-id="<?= htmlspecialchars($id) ?>">
                        </div>
                        <div class="cart-item-subtotal">KSh<span class="subtotal-amount"><?= number_format($itemSubtotal, 2) ?></span></div>
                        <button class="remove-btn" data-id="<?= htmlspecialchars($id) ?>">Remove</button>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <div class="cart-summary">
            Total: KSh<span id="total-display"><?= number_format($total, 2) ?></span>
        </div>

        <div class="cart-actions">
            <a href="kicks.php" class="continue-shopping">
                <i class="fa fa-arrow-left mr-2"></i> Continue Shopping
            </a>
            <a href="checkout.php" class="proceed-checkout">
                Proceed to Checkout <i class="fa fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <script>
        function showMessage(message, type = 'success') {
            const msgBox = document.getElementById('cart-message');
            const msgText = document.getElementById('message-text');
            msgText.innerText = message;
            msgBox.classList.add('show');
            setTimeout(() => {
                msgBox.classList.remove('show');
            }, 3000); // Hide after 3 seconds
        }

        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('change', function () {
                const id = this.dataset.id;
                const quantity = parseInt(this.value);

                if (isNaN(quantity) || quantity < 1) {
                    this.value = 1; // Reset to 1 if invalid
                    showMessage('Quantity must be at least 1.', 'error');
                    return;
                }

                fetch('cart.php', { // AJAX call still points to cart.php (your API handler)
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `productId=${encodeURIComponent(id)}&quantity=${quantity}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const itemElement = document.querySelector(`.cart-item[data-id='${id}']`);
                        const priceText = itemElement.querySelector('.cart-item-price').innerText;
                        const price = parseFloat(priceText.replace('KSh', '').replace(/,/g, ''));
                        itemElement.querySelector('.subtotal-amount').innerText = (price * quantity).toLocaleString('en-KE', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                        document.getElementById('total-display').innerText = parseFloat(data.total).toLocaleString('en-KE', {minimumFractionDigits: 2, maximumFractionDigits: 2});

                        showMessage(data.message);
                    } else {
                        showMessage(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('An error occurred while updating quantity.', 'error');
                });
            });
        });

        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const id = this.dataset.id;

                fetch('cart.php', { // AJAX call still points to cart.php (your API handler)
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `remove=${encodeURIComponent(id)}`
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.querySelector(`.cart-item[data-id='${id}']`).remove();
                        document.getElementById('total-display').innerText = parseFloat(data.total).toLocaleString('en-KE', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        showMessage(data.message);

                        if (data.total === 0) {
                            document.getElementById('cart-items-display').innerHTML = '<p class="text-gray-600 text-center py-10">Your cart is empty. <a href="kicks.php" class="text-blue-600 hover:underline">Start shopping!</a></p>';
                        }

                    } else {
                        showMessage(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('An error occurred while removing item.', 'error');
                });
            });
        });
    </script>
</body>
</html>