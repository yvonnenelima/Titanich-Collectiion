<?php
session_start();
include 'config.php'; // DB connection

// Initialize variables for filters
$productTypes = [];
$sizes = [];
$inStock = false;
$minPrice = 0;
$maxPrice = 20000; // Default max price, adjust as needed
$sortBy = 'new'; // Default sort order

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_types'])) {
        $productTypes = json_decode($_POST['product_types']);
    }
    if (isset($_POST['sizes'])) {
        $sizes = json_decode($_POST['sizes']);
    }
    if (isset($_POST['in_stock']) && $_POST['in_stock'] === '1') {
        $inStock = true;
    }
    if (isset($_POST['min_price'])) {
        $minPrice = (int)$_POST['min_price'];
    }
    if (isset($_POST['max_price'])) {
        $maxPrice = (int)$_POST['max_price'];
    }
    if (isset($_POST['sort_by'])) {
        $sortBy = $_POST['sort_by'];
    }
}

// Build the SQL query based on filters
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];

if (!empty($productTypes)) {
    // Create a string of placeholders for the IN clause
    $placeholders = implode(',', array_fill(0, count($productTypes), '?'));
    $sql .= " AND product_type IN ($placeholders)";
    $params = array_merge($params, $productTypes);
}

// Check for sizes
if (!empty($sizes)) {
    // Assuming 'size' is a comma-separated string in the database column
    $sizeConditions = [];
    foreach ($sizes as $s) {
        $sizeConditions[] = "FIND_IN_SET(?, size)";
        $params[] = $s;
    }
    if (!empty($sizeConditions)) {
        $sql .= " AND (" . implode(' OR ', $sizeConditions) . ")";
    }
}

if ($inStock) {
    $sql .= " AND stock > 0";
}

$sql .= " AND new_price BETWEEN ? AND ?";
$params[] = $minPrice;
$params[] = $maxPrice;

// Add sorting
switch ($sortBy) {
    case 'new':
        $sql .= " ORDER BY date_added DESC"; // Assuming a 'date_added' column
        break;
    case 'old':
        $sql .= " ORDER BY date_added ASC";
        break;
    case 'low':
        $sql .= " ORDER BY new_price ASC";
        break;
    case 'high':
        $sql .= " ORDER BY new_price DESC";
        break;
    default:
        $sql .= " ORDER BY date_added DESC"; // Default sort
        break;
}

// Add a limit to prevent loading too many products at once, or implement pagination
$sql .= " LIMIT 20"; // Adjust limit as needed

try {
    $stmt = $dbh->prepare($sql);
    $stmt->execute($params);

    if ($stmt->rowCount() > 0) {
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $name = htmlspecialchars($row['name']);
            $img = htmlspecialchars($row['image']);
            $old_price = number_format($row['old_price']);
            $new_price = number_format($row['new_price']);
            $stock = $row['stock'];
            $product_type = htmlspecialchars($row['product_type']); // Ensure product type is included

            echo '<div class="product-card" data-product-type="' . $product_type . '">';
            echo "<img src='uploads/$img' alt='$name'>";
            echo "<div class='product-name'>$name</div>";
            echo "<div class='price-old'>KSh$old_price</div>";
            echo "<div class='price-new'>KSh$new_price</div>";
            if ($stock <= 3) echo "<div class='last-stock'>Last stock!</div>";
            echo '</div>';
        }
    }
    // If no products are found, this script will simply echo an empty string.
    // The JavaScript in the main Canvas will handle displaying the "No products matched" message.

} catch (PDOException $e) {
    error_log("Error filtering products: " . $e->getMessage());
    echo '<p class="no-products-message">An error occurred while loading products. Please try again later.</p>';
}
?>
