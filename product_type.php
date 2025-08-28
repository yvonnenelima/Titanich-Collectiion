<?php
session_start();
include 'config.php'; // Database connection
include 'header.php'; // External styled header

$message = ''; // To store success or error messages

// Handle form submission for adding a new product type
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_product_type'])) {
    $newProductType = trim($_POST['new_product_type']);

    if (!empty($newProductType)) {
        try {
            // Check if the product type already exists (case-insensitive)
            $stmtCheck = $dbh->prepare("SELECT COUNT(*) FROM products WHERE LOWER(product_type) = LOWER(?)");
            $stmtCheck->execute([$newProductType]);
            $exists = $stmtCheck->fetchColumn();

            if ($exists > 0) {
                $message = "<p class='error-message'>Product type '<strong>" . htmlspecialchars($newProductType) . "</strong>' already exists!</p>";
            } else {
                // Insert the new product type into the 'products' table.
                // Note: This assumes 'product_type' is a column in your 'products' table.
                // For simplicity, we're inserting a dummy product with just the type.
                // In a real application, you'd likely have a dedicated table for types.
                $stmtInsert = $dbh->prepare("INSERT INTO products (name, product_type, image, old_price, new_price, stock) VALUES (?, ?, ?, ?, ?, ?)");
                // Using dummy data for other fields as we only care about the type for this page
                $dummyName = 'New ' . $newProductType . ' Shoe';
                $dummyImage = 'default.jpg'; // Ensure you have a default image
                $dummyOldPrice = 0;
                $dummyNewPrice = 0;
                $dummyStock = 0;

                $stmtInsert->execute([$dummyName, $newProductType, $dummyImage, $dummyOldPrice, $dummyNewPrice, $dummyStock]);

                $message = "<p class='success-message'>Product type '<strong>" . htmlspecialchars($newProductType) . "</strong>' added successfully!</p>";
            }
        } catch (PDOException $e) {
            error_log("Error adding product type: " . $e->getMessage());
            $message = "<p class='error-message'>Error adding product type: " . $e->getMessage() . "</p>";
        }
    } else {
        $message = "<p class='error-message'>Product type cannot be empty!</p>";
    }
}

// Fetch existing product types to display
$existingProductTypes = [];
try {
    $sql = "
        SELECT DISTINCT product_type
        FROM products
        WHERE product_type IS NOT NULL AND product_type != ''
        ORDER BY product_type ASC
    ";
    $stmtExistingTypes = $dbh->prepare($sql);
    $stmtExistingTypes->execute();
    $existingProductTypes = $stmtExistingTypes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching existing product types: " . $e->getMessage());
    $existingProductTypes = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Product Types</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 30px;
        }
        .form-section {
            margin-bottom: 40px;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .form-section label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .form-section input[type="text"] {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .form-section button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        .form-section button:hover {
            background-color: #0056b3;
        }
        .message-area {
            margin-top: 20px;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .existing-types-section {
            margin-top: 40px;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .existing-types-section ul {
            list-style: none;
            padding: 0;
        }
        .existing-types-section ul li {
            background-color: #e9ecef;
            margin-bottom: 8px;
            padding: 10px 15px;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 16px;
        }
        .existing-types-section ul li a {
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
        }
        .existing-types-section ul li a:hover {
            text-decoration: underline;
        }
        .existing-types-section ul li:last-child {
            margin-bottom: 0;
        }
        footer {
            background: #f8f8f8;
            padding: 15px 30px;
            text-align: center;
            border-top: 1px solid #ddd;
            margin-top: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Manage Product Types</h2>

    <div class="message-area">
        <?php echo $message; // Display success or error messages ?>
    </div>

    <div class="form-section">
        <h3>Add New Product Type</h3>
        <form method="POST" action="">
            <label for="new_product_type">Product Type Name:</label>
            <input type="text" id="new_product_type" name="new_product_type" placeholder="e.g., Running Shoes, Sneakers" required>
            <button type="submit">Add Product Type</button>
        </form>
    </div>

    <div class="existing-types-section">
        <h3>Existing Product Types</h3>
        <?php if (!empty($existingProductTypes)): ?>
            <ul>
                <?php foreach ($existingProductTypes as $type): ?>
                    <li>
                        <a href="shop_all_shoes.php?type=<?php echo urlencode($type['product_type']); ?>">
                            <?php echo htmlspecialchars($type['product_type']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No product types found in the database.</p>
        <?php endif; ?>
    </div>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Titanic Collection. All rights reserved.</p>
</footer>

</body>
</html>
