<?php
session_start();

// Check login state
$isLoggedIn = isset($_SESSION['user']);

$wishlistKey = $isLoggedIn ? 'user_wishlist_' . $_SESSION['user']['id'] : 'wishlist';
$cartKey = $isLoggedIn ? 'user_cart_' . $_SESSION['user']['id'] : 'cart';

$wishlist = isset($_SESSION[$wishlistKey]) ? $_SESSION[$wishlistKey] : [];
$cart = isset($_SESSION[$cartKey]) ? $_SESSION[$cartKey] : [];

// Handle removal
if (isset($_GET['remove'])) {
    $removeIndex = (int)$_GET['remove'];
    if (isset($wishlist[$removeIndex])) {
        unset($wishlist[$removeIndex]);
        $_SESSION[$wishlistKey] = array_values($wishlist);
        echo json_encode(['status' => 'removed']);
        exit();
    }
}

// Handle quantity update via AJAX
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_quantity'])) {
        $index = (int)$_POST['index'];
        $newQty = max(1, (int)$_POST['quantity']);
        if (isset($wishlist[$index])) {
            $wishlist[$index]['quantity'] = $newQty;
            $_SESSION[$wishlistKey] = $wishlist;
            $subtotal = $wishlist[$index]['price'] * $newQty;
            $total = 0;
            foreach ($wishlist as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            echo json_encode(['status' => 'updated', 'subtotal' => $subtotal, 'total' => $total]);
            exit();
        }
    }

    if (isset($_POST['move_to_cart'])) {
        $index = (int)$_POST['index'];
        if (isset($wishlist[$index])) {
            $item = $wishlist[$index];
            unset($wishlist[$index]);
            $_SESSION[$wishlistKey] = array_values($wishlist);
            $cart[] = $item;
            $_SESSION[$cartKey] = $cart;
            echo json_encode(['status' => 'moved', 'message' => 'Item moved to cart successfully.']);
            exit();
        }
    }
}

$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Wishlist - Titanic Collection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            margin: 0;
            background: #f9f9f9;
        }
        .cart-item {
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #fff;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .cart-item img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .cart-details {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .remove-btn {
            color: red;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }
        .remove-btn:hover {
            text-decoration: underline;
        }
        input[type='number'] {
            width: 80px;
            padding: 5px;
            font-size: 14px;
        }
        .cart-summary {
            margin-top: 30px;
            font-weight: bold;
            font-size: 18px;
        }
        .move-btn {
            display: inline-block;
            background: darkgreen;
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            width: fit-content;
        }
        .message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            position: relative;
        }
        .message .dismiss {
            position: absolute;
            top: 5px;
            right: 10px;
            background: transparent;
            border: none;
            color: #155724;
            font-weight: bold;
            cursor: pointer;
        }
        @media (min-width: 600px) {
            .cart-item {
                flex-direction: row;
            }
            .cart-item img {
                width: 120px;
            }
        }
    </style>
</head>
<body>
    <h1>Your Wishlist</h1>
    <div id="message-container"></div>

    <?php if (count($wishlist) === 0): ?>
        <p>Your wishlist is empty. <a href="index.php">Continue Shopping</a></p>
    <?php else: ?>
        <?php foreach ($wishlist as $index => $item): 
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;
        ?>
        <div class="cart-item" data-index="<?= $index ?>">
            <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
            <div class="cart-details">
                <h3><?= htmlspecialchars($item['name']) ?></h3>
                <p>Price: KSh<?= number_format($item['price'], 2) ?></p>
                <label for="qty<?= $index ?>">Quantity:</label>
                <input type="number" name="quantity" id="qty<?= $index ?>" value="<?= $item['quantity'] ?>" min="1" onchange="updateQuantity(<?= $index ?>, this.value)">
                <p>Subtotal: KSh<span id="subtotal<?= $index ?>"><?= number_format($subtotal, 2) ?></span></p>
                <span class="remove-btn" onclick="removeItem(<?= $index ?>)">Remove</span>
                <span class="move-btn" onclick="moveToCart(<?= $index ?>)">Move to Cart</span>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="cart-summary">
            <p>Total: KSh<span id="cart-total"><?= number_format($total, 2) ?></span></p>
        </div>
    <?php endif; ?>

    <script>
    function updateQuantity(index, quantity) {
        fetch('wishlist.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ update_quantity: 1, index, quantity })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'updated') {
                document.getElementById('subtotal' + index).textContent = Number(data.subtotal).toLocaleString();
                document.getElementById('cart-total').textContent = Number(data.total).toLocaleString();
            }
        });
    }

    function removeItem(index) {
        fetch('wishlist.php?remove=' + index)
        .then(res => res.json())
        .then(data => {
            if (data.status === 'removed') {
                location.reload();
            }
        });
    }

    function moveToCart(index) {
        fetch('wishlist.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ move_to_cart: 1, index })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'moved') {
                const messageBox = document.getElementById('message-container');
                messageBox.innerHTML = `
                    <div class="message">
                        ${data.message}
                        <button class="dismiss" onclick="this.parentElement.remove()">&times;</button>
                    </div>
                `;
                setTimeout(() => location.reload(), 1500);
            }
        });
    }
    </script>
</body>
</html>
