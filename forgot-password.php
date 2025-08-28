

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password | Titanic Collection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fff8f0;
        }
        .container {
            max-width: 400px;
            margin: 80px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            color: #cc6600;
        }
        input[type=email], input[type=submit] {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        input[type=submit] {
            background-color: #cc6600;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background-color: #b35900;
        }
        .message {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Reset Your Password</h2>
    <?php
    if (!empty($_SESSION['error'])) {
        echo '<div class="message" style="color:red;">'.$_SESSION['error'].'</div>';
        unset($_SESSION['error']);
    } elseif (!empty($_SESSION['success'])) {
        echo '<div class="message" style="color:green;">'.$_SESSION['success'].'</div>';
        unset($_SESSION['success']);
    }
    ?>
    <form action="include/send_reset.php" method="POST">
        <input type="email" name="email" placeholder="Enter your email" required>
        <input type="submit" value="Send Reset Link">
    </form>
</div>

</body>
</html>
