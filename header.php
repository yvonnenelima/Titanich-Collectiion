<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Titanic Collection</title>
    <link rel="shortcut icon" href="https://img.icons8.com/fluent/48/000000/enter-2.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #ffffff; /* White background */
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

        .hero {
            background: url('https://images.unsplash.com/photo-1606813905315-d733b1e845f1') center/cover no-repeat;
            height: 400px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        .hero h1 {
            font-size: 48px;
            background-color: rgba(0, 0, 0, 0.6);
            padding: 20px;
        }

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

        footer {
            background-color: #222;
            color: #ddd;
            padding: 30px 20px;
            text-align: center;
            font-size: 14px;
        }
        footer a {
            color: #ddd;
            margin: 0 10px;
            text-decoration: none;
        }
        footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Top bar -->
<div class="top-bar">
    <div><i class="fa-solid fa-check-double"></i> FAST DELIVERY</div>
    <div><i class="fa-solid fa-phone"></i> <a href="#">Contact us</a></div>
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
            <a href="login.php"><i class="fa fa-user-circle"></i></a>
 <a href="cart.php" class="cart-icon">
    <svg></svg>
    <span class="cart-count">0</span>
</a>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="nav-menu">
        <a href="index.php">HOME</a>
        <a href="kicks.php">PRODUCTS</a>
    
        <a href="about.php">ABOUT US</a>
    </nav>
</header>
