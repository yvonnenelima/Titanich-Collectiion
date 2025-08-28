<?php
session_start();
include 'config.php';

// Redirect if already logged in
if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {
    header("Location: index.php");
    exit();
}

$error_message = '';
$success_message = '';

// Handle signup form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $full_name = sanitize_input($_POST['full_name']);
    $email = sanitize_input($_POST['email']);
    $phone = sanitize_input($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = sanitize_input($_POST['address']);
    
    // Validation
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = "Please fill in all required fields";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Please enter a valid email address";
    } elseif (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters long";
    } elseif ($password !== $confirm_password) {
        $error_message = "Passwords do not match";
    } else {
        // Check if users table exists, create if not
        $check_table = "SHOW TABLES LIKE 'users'";
        $table_result = mysqli_query($conn, $check_table);
        
        if (mysqli_num_rows($table_result) == 0) {
            // Create users table
            $create_table = "CREATE TABLE `users` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `full_name` varchar(255) NOT NULL,
                `email` varchar(255) NOT NULL UNIQUE,
                `phone` varchar(20) DEFAULT NULL,
                `password` varchar(255) NOT NULL,
                `address` text DEFAULT NULL,
                `is_active` tinyint(1) DEFAULT 1,
                `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
                `updated_at` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                KEY `email` (`email`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";
            mysqli_query($conn, $create_table);
        }
        
        // Check if email already exists
        $check_email = "SELECT id FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $check_email);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $email_result = mysqli_stmt_get_result($stmt);
        
        if (mysqli_num_rows($email_result) > 0) {
            $error_message = "An account with this email already exists";
        } else {
            // Hash password and insert user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $insert_query = "INSERT INTO users (full_name, email, phone, password, address) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_query);
            mysqli_stmt_bind_param($stmt, "sssss", $full_name, $email, $phone, $hashed_password, $address);
            
            if (mysqli_stmt_execute($stmt)) {
                // Get the newly created user ID
                $user_id = mysqli_insert_id($conn);
                
                // Auto-login after successful signup
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $full_name;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_phone'] = $phone;
                $_SESSION['user_address'] = $address;
                
                // Set success message
                $success_message = "Account created successfully! Redirecting to homepage...";
                
                // Immediate redirect using JavaScript (more reliable)
                echo "<script>
                        setTimeout(function() {
                            window.location.href = 'index.php';
                        }, 1500);
                      </script>";
                
                // Backup PHP redirect
                header("refresh:2;url=index.php");
            } else {
                $error_message = "Error creating account. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Titanic Collection</title>
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
        .signup-container {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .input-group {
            position: relative;
        }
        .input-group input:focus + label,
        .input-group input:not(:placeholder-shown) + label,
        .input-group textarea:focus + label,
        .input-group textarea:not(:placeholder-shown) + label {
            transform: translateY(-28px) scale(0.8);
            color: #40E0D0;
        }
        .input-group label {
            position: absolute;
            left: 12px;
            top: 12px;
            background: white;
            padding: 0 4px;
            transition: all 0.3s ease;
            pointer-events: none;
            color: #666;
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
        .password-strength {
            height: 4px;
            border-radius: 2px;
            transition: all 0.3s ease;
        }
        .strength-weak { background: #ef4444; width: 33%; }
        .strength-medium { background: #f59e0b; width: 66%; }
        .strength-strong { background: #10b981; width: 100%; }
        
        /* Loading overlay styles */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .loading-content {
            background: white;
            padding: 2rem;
            border-radius: 1rem;
            text-align: center;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="w-16 h-16 bg-gradient-to-r from-cyan-400 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-spinner fa-spin text-white text-2xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Creating Your Account</h3>
            <p class="text-gray-600">Please wait while we set up your profile...</p>
        </div>
    </div>

    <div class="signup-container rounded-2xl shadow-2xl p-8 w-full max-w-lg">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-cyan-400 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-ship text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Join Titanic Collection</h1>
            <p class="text-gray-600">Create your account and start shopping premium footwear</p>
        </div>

        <!-- Error/Success Messages -->
        <?php if (!empty($error_message)): ?>
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700"><?php echo $error_message; ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($success_message)): ?>
            <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700"><?php echo $success_message; ?></p>
                        <div class="mt-2">
                            <div class="w-full bg-gray-200 rounded-full h-1">
                                <div class="bg-green-500 h-1 rounded-full redirect-progress" style="width: 0%; transition: width 1.5s ease-in-out;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                // Start progress bar animation
                setTimeout(function() {
                    document.querySelector('.redirect-progress').style.width = '100%';
                }, 100);
                
                // Hide form after success
                document.getElementById('signup-form').style.display = 'none';
                document.querySelector('.text-center.mt-8').style.display = 'none';
                document.querySelector('.text-center.mt-4').style.display = 'none';
            </script>
        <?php endif; ?>

        <!-- Signup Form -->
        <form method="POST" class="space-y-6" id="signup-form">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="input-group">
                    <input type="text" name="full_name" id="full_name" required placeholder=" "
                           class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all">
                    <label for="full_name">Full Name *</label>
                </div>

                <div class="input-group">
                    <input type="tel" name="phone" id="phone" placeholder=" "
                           class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all">
                    <label for="phone">Phone Number</label>
                </div>
            </div>

            <div class="input-group">
                <input type="email" name="email" id="email" required placeholder=" "
                       class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all">
                <label for="email">Email Address *</label>
                <div class="text-xs text-gray-500 mt-1">We'll use this for order confirmations and updates</div>
            </div>

            <div class="input-group">
                <input type="password" name="password" id="password" required placeholder=" " oninput="checkPasswordStrength()"
                       class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all">
                <label for="password">Password *</label>
                <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-eye" id="password-toggle"></i>
                </button>
                <div class="mt-2">
                    <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>Password Strength</span>
                        <span id="strength-text">Weak</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-1">
                        <div class="password-strength strength-weak" id="strength-bar"></div>
                    </div>
                </div>
            </div>

            <div class="input-group">
                <input type="password" name="confirm_password" id="confirm_password" required placeholder=" " oninput="checkPasswordMatch()"
                       class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all">
                <label for="confirm_password">Confirm Password *</label>
                <button type="button" onclick="togglePassword('confirm_password')" class="absolute right-3 top-3 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-eye" id="confirm-password-toggle"></i>
                </button>
                <div id="password-match-message" class="text-xs mt-1 hidden"></div>
            </div>

            <div class="input-group">
                <textarea name="address" id="address" rows="3" placeholder=" "
                          class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all resize-none"></textarea>
                <label for="address">Delivery Address</label>
                <div class="text-xs text-gray-500 mt-1">Help us deliver your orders faster</div>
            </div>

            <div class="flex items-start">
                <input type="checkbox" id="terms" name="terms" required class="rounded border-gray-300 text-cyan-400 focus:ring-cyan-400 mt-1">
                <label for="terms" class="ml-3 text-sm text-gray-700">
                    I agree to the <a href="#" class="text-cyan-600 hover:text-cyan-800 underline">Terms of Service</a> 
                    and <a href="#" class="text-cyan-600 hover:text-cyan-800 underline">Privacy Policy</a>
                </label>
            </div>

           

            <button type="submit" name="signup" class="btn-primary w-full py-3 px-4 rounded-lg text-white font-semibold text-lg shadow-lg" id="signup-btn">
                <i class="fas fa-user-plus mr-2"></i>Create Account
            </button>
        </form>

        <!-- Divider -->
        <div class="my-6 flex items-center">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="mx-4 text-gray-500 text-sm">or</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <!-- Social Signup -->
        <div class="space-y-3">
            <button class="w-full py-3 px-4 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-all flex items-center justify-center">
                <i class="fab fa-google text-red-500 mr-3"></i>Sign up with Google
            </button>
         
        </div>

        <!-- Sign In Link -->
        <div class="text-center mt-8 pt-6 border-t border-gray-200">
            <p class="text-gray-600">
                Already have an account? 
                <a href="signin.php" class="text-cyan-600 hover:text-cyan-800 font-semibold transition-colors">Sign In</a>
            </p>
        </div>

        <!-- Back to Store -->
        <div class="text-center mt-4">
            <a href="index.php" class="text-gray-500 hover:text-gray-700 text-sm transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Store
            </a>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(inputId + '-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');
            
            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'password-strength';
            
            if (strength < 2) {
                strengthBar.classList.add('strength-weak');
                strengthText.textContent = 'Weak';
                strengthText.className = 'text-red-600';
            } else if (strength < 3) {
                strengthBar.classList.add('strength-medium');
                strengthText.textContent = 'Medium';
                strengthText.className = 'text-yellow-600';
            } else {
                strengthBar.classList.add('strength-strong');
                strengthText.textContent = 'Strong';
                strengthText.className = 'text-green-600';
            }
        }

        function checkPasswordMatch() {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const message = document.getElementById('password-match-message');
            
            if (confirmPassword.length > 0) {
                message.classList.remove('hidden');
                if (password === confirmPassword) {
                    message.textContent = '✓ Passwords match';
                    message.className = 'text-xs mt-1 text-green-600';
                } else {
                    message.textContent = '✗ Passwords do not match';
                    message.className = 'text-xs mt-1 text-red-600';
                }
            } else {
                message.classList.add('hidden');
            }
        }

        // Auto-focus on full name input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('full_name').focus();
        });

        // Form validation and loading state
        document.getElementById('signup-form').addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm_password').value;
            const terms = document.getElementById('terms').checked;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                alert('Passwords do not match!');
                return false;
            }
            
            if (!terms) {
                e.preventDefault();
                alert('Please accept the Terms of Service and Privacy Policy');
                return false;
            }
            
            // Show loading overlay
            document.getElementById('loadingOverlay').style.display = 'flex';
            
            // Update submit button
            const submitBtn = document.getElementById('signup-btn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating Account...';
            submitBtn.disabled = true;
        });
    </script>
</body>
</html>