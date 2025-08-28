<?php
session_start();

// Check if user is logged in
$was_logged_in = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';

// Preserve cart data before destroying session
$cart_data = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$currency_data = isset($_SESSION['currency']) ? $_SESSION['currency'] : 'KSH';

// Clear all user session data
unset($_SESSION['user_logged_in']);
unset($_SESSION['user_id']);
unset($_SESSION['user_name']);
unset($_SESSION['user_email']);
unset($_SESSION['user_phone']);
unset($_SESSION['user_address']);

// Restore cart and currency (optional - you might want to clear cart on logout too)
$_SESSION['cart'] = $cart_data;
$_SESSION['currency'] = $currency_data;

// Alternative: Clear everything including cart
// session_destroy();
// session_start();

$redirect_url = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout - Titanic Collection</title>
    <link rel="shortcut icon" href="https://img.icons8.com/fluent/48/000000/enter-2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #40E0D0 0%, #48D1CC 50%, #20B2AA 100%);
            min-height: 100vh;
        }
        .logout-container {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-primary {
            background: linear-gradient(135deg, #40E0D0, #20B2AA);
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #20B2AA, #40E0D0);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(64, 224, 208, 0.3);
        }
        .btn-secondary {
            background: #6b7280;
            transition: all 0.3s ease;
        }
        .btn-secondary:hover {
            background: #4b5563;
            transform: translateY(-2px);
        }
        .wave-animation {
            animation: wave 2s ease-in-out infinite;
        }
        @keyframes wave {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(20deg); }
            75% { transform: rotate(-10deg); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="logout-container rounded-2xl shadow-2xl p-8 w-full max-w-md text-center fade-in">
        <?php if ($was_logged_in): ?>
            <!-- Successful Logout -->
            <div class="mb-8">
                <div class="w-20 h-20 bg-gradient-to-r from-cyan-400 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-sign-out-alt text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">See You Soon!</h1>
                <p class="text-gray-600">
                    <?php if ($user_name): ?>
                        Thanks for visiting, <?php echo htmlspecialchars($user_name); ?>!
                    <?php else: ?>
                        You have been successfully logged out.
                    <?php endif; ?>
                </p>
            </div>

            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center justify-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">Logged out successfully</p>
                        <p class="text-xs text-green-600">Your cart has been preserved for your next visit</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <a href="<?php echo $redirect_url; ?>" class="btn-primary w-full py-3 px-4 rounded-lg text-white font-semibold shadow-lg block">
                    <i class="fas fa-shopping-bag mr-2"></i>Continue Shopping
                </a>
                
                <a href="login.php" class="btn-secondary w-full py-3 px-4 rounded-lg text-white font-semibold shadow-lg block">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In Again
                </a>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Quick Actions</h3>
                <div class="grid grid-cols-2 gap-4">
                    <a href="products.php" class="text-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-shoe-prints text-cyan-600 text-xl mb-2"></i>
                        <p class="text-xs text-gray-600">Browse Products</p>
                    </a>
                    <a href="tel:0717717985" class="text-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <i class="fas fa-phone text-green-600 text-xl mb-2"></i>
                        <p class="text-xs text-gray-600">Contact Us</p>
                    </a>
                </div>
            </div>

        <?php else: ?>
            <!-- User was not logged in -->
            <div class="mb-8">
                <div class="w-20 h-20 bg-gradient-to-r from-gray-400 to-gray-500 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-question-circle text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Already Logged Out</h1>
                <p class="text-gray-600">You weren't signed in to begin with.</p>
            </div>

            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center justify-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">No active session found</p>
                        <p class="text-xs text-blue-600">You can continue browsing or sign in for a personalized experience</p>
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                <a href="login.php" class="btn-primary w-full py-3 px-4 rounded-lg text-white font-semibold shadow-lg block">
                    <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                </a>
                
                <a href="<?php echo $redirect_url; ?>" class="btn-secondary w-full py-3 px-4 rounded-lg text-white font-semibold shadow-lg block">
                    <i class="fas fa-home mr-2"></i>Go to Homepage
                </a>
            </div>
        <?php endif; ?>

        <!-- Footer Links -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-center space-x-6 text-sm text-gray-500">
                <a href="index.php" class="hover:text-gray-700 transition-colors">Home</a>
                <a href="products.php" class="hover:text-gray-700 transition-colors">Products</a>
                <a href="contact.php" class="hover:text-gray-700 transition-colors">Contact</a>
            </div>
            <div class="mt-4">
                <p class="text-xs text-gray-400">
                    Titanic Collection - Premium Footwear
                </p>
            </div>
        </div>

        <!-- Fun goodbye message -->
        <div class="mt-6">
            <p class="text-sm text-gray-500">
                <span class="wave-animation inline-block">👋</span> 
                Thanks for visiting! Come back soon for new arrivals.
            </p>
        </div>
    </div>

    <script>
        // Auto redirect after 10 seconds if user was logged in
        <?php if ($was_logged_in): ?>
            let countdown = 10;
            const redirectTimer = setInterval(function() {
                countdown--;
                if (countdown <= 0) {
                    clearInterval(redirectTimer);
                    window.location.href = '<?php echo $redirect_url; ?>';
                }
            }, 1000);
        <?php endif; ?>

        // Add some interactive elements
        document.addEventListener('DOMContentLoaded', function() {
            // Add hover effects to buttons
            const buttons = document.querySelectorAll('a[class*="btn-"]');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px) scale(1.02)';
                });
                
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                });
            });
        });
    </script>
</body>
</html>