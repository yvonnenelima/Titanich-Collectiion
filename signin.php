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

// Sanitize input function
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = sanitize_input($_POST['email']);
    $password = $_POST['password'];
    
    if (empty($email) || empty($password)) {
        $error_message = "Please fill in all fields";
    } else {
        // Check database connection
        if (!isset($conn) || !$conn) {
            $error_message = "Database connection failed. Please try again later.";
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
                
                if (!mysqli_query($conn, $create_table)) {
                    $error_message = "Error creating users table. Please contact administrator.";
                }
            }
            
            if (empty($error_message)) {
                // Use prepared statement for security
                $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE email = ? AND is_active = 1");
                mysqli_stmt_bind_param($stmt, "s", $email);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if ($result && mysqli_num_rows($result) == 1) {
                    $user = mysqli_fetch_assoc($result);
                    
                    if (password_verify($password, $user['password'])) {
                        // Login successful - Clear any previous session data
                        session_regenerate_id(true);
                        
                        $_SESSION['user_logged_in'] = true;
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['user_name'] = $user['full_name'];
                        $_SESSION['user_email'] = $user['email'];
                        $_SESSION['user_phone'] = $user['phone'];
                        $_SESSION['user_address'] = $user['address'];
                        
                        // Set success message and redirect after a brief delay
                        $success_message = "Login successful! Redirecting...";
                        
                        // Use JavaScript redirect to ensure the session is properly set
                        echo "<script>
                            setTimeout(function() {
                                window.location.href = 'index.php';
                            }, 1000);
                        </script>";
                        
                    } else {
                        $error_message = "Invalid email or password";
                    }
                } else {
                    $error_message = "Invalid email or password";
                }
                
                mysqli_stmt_close($stmt);
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
    <title>Login - Titanich Store</title>
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
        .login-container {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .input-group {
            position: relative;
        }
        .input-group input:focus + label,
        .input-group input:not(:placeholder-shown) + label {
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
        .social-btn {
            transition: all 0.3s ease;
        }
        .social-btn:hover {
            transform: translateY(-2px);
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            z-index: 10;
        }
        .password-toggle:hover {
            color: #40E0D0;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

    <div class="login-container rounded-2xl shadow-2xl p-8 w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-gradient-to-r from-cyan-400 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-shoe-prints text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h1>
            <p class="text-gray-600">Sign in to your Titanich Store account</p>
        </div>

        <!-- Error/Success Messages -->
        <?php if (!empty($error_message)): ?>
            <div class="bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700"><?php echo htmlspecialchars($error_message); ?></p>
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
                        <p class="text-sm text-green-700"><?php echo htmlspecialchars($success_message); ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Login Form -->
        <form method="POST" class="space-y-6" id="loginForm">
            <div class="input-group">
                <input type="email" name="email" id="email" required placeholder=" " 
                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                       class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all">
                <label for="email">Email Address</label>
            </div>

            <div class="input-group">
                <input type="password" name="password" id="password" required placeholder=" "
                       class="w-full px-3 py-3 pr-12 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all">
                <label for="password">Password</label>
                <button type="button" onclick="togglePassword()" class="password-toggle">
                    <i class="fas fa-eye" id="password-toggle"></i>
                </button>
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center">
                    <input type="checkbox" name="remember_me" class="rounded border-gray-300 text-cyan-400 focus:ring-cyan-400">
                    <span class="ml-2 text-sm text-gray-600">Remember me</span>
                </label>
                <a href="forgot-password.php" class="text-sm text-cyan-600 hover:text-cyan-800 transition-colors">Forgot password?</a>
            </div>

            <button type="submit" name="login" class="btn-primary w-full py-3 px-4 rounded-lg text-white font-semibold text-lg shadow-lg" id="loginBtn">
                <i class="fas fa-sign-in-alt mr-2"></i>Sign In
            </button>
        </form>

        <!-- Divider -->
        <div class="my-6 flex items-center">
            <div class="flex-grow border-t border-gray-300"></div>
            <span class="mx-4 text-gray-500 text-sm">or</span>
            <div class="flex-grow border-t border-gray-300"></div>
        </div>

        <!-- Demo Account Info -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-blue-700 font-medium mb-2">Demo Account</p>
                    <p class="text-xs text-blue-600 mb-1"><strong>Email:</strong> demo@titanichstore.com</p>
                    <p class="text-xs text-blue-600"><strong>Password:</strong> demo123</p>
                    <button onclick="fillDemoCredentials()" class="text-xs text-blue-800 underline hover:no-underline mt-1">
                        Use Demo Account
                    </button>
                </div>
            </div>
        </div>

        <!-- Social Login (Optional) -->
        <div class="space-y-3">
            <button type="button" class="social-btn w-full py-3 px-4 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-all flex items-center justify-center">
                <i class="fab fa-google text-red-500 mr-3"></i>Continue with Google
            </button>
            <button type="button" class="social-btn w-full py-3 px-4 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-all flex items-center justify-center">
                <i class="fab fa-facebook text-blue-600 mr-3"></i>Continue with Facebook
            </button>
        </div>

        <!-- Sign Up Link -->
        <div class="text-center mt-8 pt-6 border-t border-gray-200">
            <p class="text-gray-600">
                Don't have an account? 
                <a href="signup.php" class="text-cyan-600 hover:text-cyan-800 font-semibold transition-colors">Create Account</a>
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
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle');
            
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

        function fillDemoCredentials() {
            document.getElementById('email').value = 'demo@titanichstore.com';
            document.getElementById('password').value = 'demo123';
            
            // Trigger label animation
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            emailInput.focus();
            passwordInput.focus();
            emailInput.focus(); // Focus back to email
        }

        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('loginBtn');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Signing In...';
            submitBtn.disabled = true;
        });

        // Auto-focus on email input
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
            
            // Add enter key support for form submission
            document.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    document.getElementById('loginForm').submit();
                }
            });
        });

        // Client-side form validation
        function validateForm() {
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value.trim();
            
            if (!email || !password) {
                showError('Please fill in all fields');
                return false;
            }
            
            if (!isValidEmail(email)) {
                showError('Please enter a valid email address');
                return false;
            }
            
            return true;
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }

        function showError(message) {
            // Create error element if it doesn't exist
            let errorDiv = document.getElementById('js-error');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.id = 'js-error';
                errorDiv.className = 'bg-red-50 border-l-4 border-red-400 p-4 mb-6 rounded-r-lg';
                errorDiv.innerHTML = `
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700" id="js-error-text"></p>
                        </div>
                    </div>
                `;
                document.querySelector('.login-container').insertBefore(errorDiv, document.getElementById('loginForm'));
            }
            
            document.getElementById('js-error-text').textContent = message;
            errorDiv.style.display = 'block';
            
            // Hide after 5 seconds
            setTimeout(() => {
                errorDiv.style.display = 'none';
            }, 5000);
        }

        // Add form validation on submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                const submitBtn = document.getElementById('loginBtn');
                submitBtn.innerHTML = '<i class="fas fa-sign-in-alt mr-2"></i>Sign In';
                submitBtn.disabled = false;
                return false;
            }
        });
    </script>
</body>
</html>