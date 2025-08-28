<?php
session_start();
include 'config.php'; // DB connection
include 'header.php'; // External styled header

// --- PHP code to fetch product types and their counts ---
$productTypes = [];
try {
    // Query to get all distinct product types and their counts from the products table.
    $sql = "
        SELECT product_type, COUNT(*) AS product_count
        FROM products
        WHERE product_type IS NOT NULL AND product_type != ''
        GROUP BY product_type
        ORDER BY product_type ASC
    ";
    $stmtProductTypes = $dbh->prepare($sql);
    $stmtProductTypes->execute();
    $productTypes = $stmtProductTypes->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching product types: " . $e->getMessage());
    $productTypes = [];
}
// --- End PHP code for product types ---

// --- PHP code to fetch initial products ---
$initialProducts = [];
$initialProductType = ''; // Initialize variable for pre-selecting dropdown

try {
    $sql = "SELECT * FROM products";
    $params = [];

    // Check if a product type is passed in the URL
    if (isset($_GET['type']) && !empty($_GET['type'])) {
        $initialProductType = htmlspecialchars($_GET['type']);
        $sql .= " WHERE product_type = ?";
        $params[] = $initialProductType;
    }

    $sql .= " LIMIT 12"; // Limit for initial load

    $stmt = $dbh->prepare($sql);
    $stmt->execute($params);
    $initialProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching initial products: " . $e->getMessage());
    $initialProducts = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Kicks Catalog | Titanic Collection</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Your CSS styles go here */
        body { margin: 0; font-family: Arial, sans-serif; background: #fff; }
        .container { display: flex; padding: 30px; gap: 20px; }
        .sidebar { width: 250px; border-right: 1px solid #ddd; padding-right: 20px; }
        .sidebar h3 { font-size: 18px; margin-top: 30px; }
        .sidebar label { display: block; margin: 8px 0; cursor: pointer; }
        .sidebar input[type="checkbox"] { cursor: pointer; }
        .product-section { flex: 1; }
        .breadcrumbs { font-size: 14px; color: #999; margin-bottom: 20px; }
        .breadcrumbs a { text-decoration: none; color: #555; }
        .breadcrumbs a:hover { text-decoration: underline; }
        .product-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 30px; }
        .product-card {
            border: 1px solid #eee; padding: 10px; border-radius: 5px;
            text-align: center; transition: 0.3s; background: #fafafa;
        }
        .product-card:hover { box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .product-card img { width: 100%; height: auto; }
        .product-name { font-size: 16px; margin: 10px 0 5px; font-weight: bold; }
        .price-old { text-decoration: line-through; color: #999; font-size: 14px; }
        .price-new { color: #e60000; font-size: 16px; font-weight: bold; }
        .last-stock {
            background: #333; color: #fff; padding: 4px 8px; display: inline-block;
            font-size: 12px; margin-top: 8px; border-radius: 3px;
        }
        .top-bar {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;
        }
        .top-bar i { cursor: pointer; margin-right: 10px; }
        footer {
            background: #f8f8f8; padding: 15px 30px; text-align: center; border-top: 1px solid #ddd;
        }
        .filter-section input[type="checkbox"] { margin-right: 8px; }
        .filter-section input[type="range"] { width: 100%; }
        /* Style for the "no products found" message */
        .no-products-message {
            grid-column: 1 / -1; /* Make the message span the entire grid */
            text-align: center;
            font-size: 1.2em;
            color: #777;
            padding: 50px;
        }
    </style>
</head>
<body>

<div class="container">
    <aside class="sidebar">
        <div class="filter-section">
            <label><input type="checkbox" id="inStockFilter"> In stock</label>
        </div>

        <div class="filter-section">
            <h3>Price</h3>
            <input type="range" min="0" max="20000" value="0" id="priceRange">
            <div>
                <input type="text" placeholder="KSh 0" id="minPrice"> to
                <input type="text" placeholder="KSh 15000" id="maxPrice">
            </div>
        </div>

        <div class="filter-section">
            <h3>Product type</h3>
            <select id="productTypeDropdown">
                <option value="">All Brands</option>
                <option value="adidas">Adidas</option>
                <option value="airforce">Airforce</option>
                <option value="airmax">Airmax</option>
                <option value="new balance">New Balance</option>
                <option value="nike">Nike</option>
                <option value="converse">Converse</option>
                <option value="vans">Vans</option>
                <option value="jordan">Jordan</option>
            </select>
        </div>

        <div class="filter-section">
            <h3>Size</h3>
            <label><input type="checkbox" class="size-filter" data-size="36"> 36</label>
            <label><input type="checkbox" class="size-filter" data-size="37"> 37</label>
            <label><input type="checkbox" class="size-filter" data-size="38"> 38</label>
            <label><input type="checkbox" class="size-filter" data-size="39"> 39</label>
            <label><input type="checkbox" class="size-filter" data-size="40"> 40</label>
            <label><input type="checkbox" class="size-filter" data-size="41"> 41</label>
            <label><input type="checkbox" class="size-filter" data-size="42"> 42</label>
            <label><input type="checkbox" class="size-filter" data-size="43"> 43</label>
            <label><input type="checkbox" class="size-filter" data-size="44"> 44</label>
            <label><input type="checkbox" class="size-filter" data-size="45"> 45</label>
            <label><input type="checkbox" class="size-filter" data-size="46"> 46</label>
        </div>
    </aside>

    <section class="product-section">
        <div class="breadcrumbs"><a href="index.php">Homepage</a> > <a href="shop_all_shoes.php">SHOP ALL</a></div>

        <div class="top-bar">
            <div>
                <i class="fa fa-th-large"></i>
                <i class="fa fa-bars"></i>
            </div>
            <div>
                <label for="sortSelect">Sort by:</label>
                <select id="sortSelect">
                    <option value="new">Date, new to old</option>
                    <option value="old">Date, old to new</option>
                    <option value="low">Price, low to high</option>
                    <option value="high">Price, high to low</option>
                </select>
            </div>
        </div>

        <div class="product-grid" id="productGrid">
            <?php
            // Initial product load (without filters)
            if (!empty($initialProducts)) {
                foreach ($initialProducts as $row) {
                    $name = htmlspecialchars($row['name']);
                    $img = htmlspecialchars($row['image']);
                    $old_price = number_format($row['old_price']);
                    $new_price = number_format($row['new_price']);
                    $stock = $row['stock'];
                    $product_type = htmlspecialchars($row['product_type']);

                    echo '<div class="product-card" data-product-type="' . $product_type . '">';
                    echo "<img src='uploads/$img' alt='$name'>";
                    echo "<div class='product-name'>$name</div>";
                    echo "<div class='price-old'>KSh$old_price</div>";
                    echo "<div class='price-new'>KSh$new_price</div>";
                    if ($stock <= 3) echo "<div class='last-stock'>Last stock!</div>";
                    echo '</div>';
                }
            } else {
                echo '<p class="no-products-message">No products found at this time.</p>';
            }
            ?>
        </div>
    </section>
</div>

<footer>
    <p>&copy; <?php echo date("Y"); ?> Titanic Collection. All rights reserved.</p>
</footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productTypeDropdown = document.getElementById('productTypeDropdown');
        const productGrid = document.getElementById('productGrid');
        const inStockFilter = document.getElementById('inStockFilter');
        const priceRange = document.getElementById('priceRange');
        const minPriceInput = document.getElementById('minPrice');
        const maxPriceInput = document.getElementById('maxPrice');
        const sizeCheckboxes = document.querySelectorAll('.size-filter');
        const sortSelect = document.getElementById('sortSelect');

        // Get initial product type from URL if available
        const urlParams = new URLSearchParams(window.location.search);
        const initialUrlProductType = urlParams.get('type');

        // If a product type is in the URL, set the dropdown and apply filters
        if (initialUrlProductType) {
            productTypeDropdown.value = initialUrlProductType;
            // No need to call applyFilters here, as the PHP already filtered
            // However, we need to ensure the dropdown value is set correctly.
        }

        function applyFilters() {
            const selectedProductType = productTypeDropdown.value;
            
            const selectedSizes = Array.from(sizeCheckboxes)
                                       .filter(checkbox => checkbox.checked)
                                       .map(checkbox => checkbox.dataset.size);

            const inStock = inStockFilter.checked;
            const minPrice = parseInt(minPriceInput.value.replace('KSh ', '') || '0');
            const maxPrice = parseInt(maxPriceInput.value.replace('KSh ', '') || '20000');
            const sortBy = sortSelect.value;

            const formData = new FormData();
            if (selectedProductType) {
                formData.append('product_type', selectedProductType);
            }
            if (selectedSizes.length > 0) {
                formData.append('sizes', JSON.stringify(selectedSizes));
            }
            formData.append('in_stock', inStock ? '1' : '0');
            formData.append('min_price', minPrice);
            formData.append('max_price', maxPrice);
            formData.append('sort_by', sortBy);

            fetch('filter_products.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === '') {
                    productGrid.innerHTML = '<p class="no-products-message">No products matched your criteria.</p>';
                } else {
                    productGrid.innerHTML = data;
                }
            })
            .catch(error => {
                console.error('Error fetching filtered products:', error);
                productGrid.innerHTML = '<p class="no-products-message">Error loading products. Please try again.</p>';
            });
        }

        // Attach event listeners to all filter elements
        productTypeDropdown.addEventListener('change', applyFilters); 
        inStockFilter.addEventListener('change', applyFilters);
        priceRange.addEventListener('input', function() {
            maxPriceInput.value = 'KSh ' + this.value;
        });
        priceRange.addEventListener('change', applyFilters);
        minPriceInput.addEventListener('change', applyFilters);
        maxPriceInput.addEventListener('change', applyFilters);
        sizeCheckboxes.forEach(checkbox => checkbox.addEventListener('change', applyFilters));
        sortSelect.addEventListener('change', applyFilters);

        maxPriceInput.value = 'KSh ' + priceRange.value;
    });
</script>
<script>
// This script block seems to be a remnant from a previous iteration
// and might not be fully integrated with the current filtering logic.
// It's generally good practice to consolidate JavaScript for clarity.
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".catalog-filter").forEach(btn => {
        btn.addEventListener("click", function () {
            const type = this.getAttribute("data-type");

            fetch("filter_products.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `product_types=${JSON.stringify([type])}`
            })
            .then(res => res.text())
            .then(html => {
                document.getElementById("product-list").innerHTML = html;
            })
            .catch(err => console.error(err));
        });
    });
});
</script>
</body>
</html>
