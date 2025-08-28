<?php
// remove_from_cart.php
session_start();

if (isset($_POST['remove_index'])) {
    $index = intval($_POST['remove_index']);
    if (isset($_SESSION['cart'][$index])) {
        array_splice($_SESSION['cart'], $index, 1);
    }
}

header('Content-Type: application/json');
echo json_encode(['success' => true]);
?>