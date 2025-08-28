<?php
session_start();
include 'config.php';

if ($_POST) {
    try {
        // Get form data
        $customer_name = mysqli_real_escape_string($conn, $_POST['customer_name']);
        $customer_email = mysqli_real_escape_string($conn, $_POST['customer_email']);
        $customer_phone = mysqli_real_escape_string($conn, $_POST['customer_phone']);
        $delivery_address = mysqli_real_escape_string($conn, $_POST['delivery_address']);
        $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
        $mpesa_code = isset($_POST['mpesa_code']) ? mysqli_real_escape_string($conn, $_POST['mpesa_code']) : '';
        $cart_data = $_POST['cart_data'];
        $total_amount = floatval($_POST['total_amount']);
        $currency = mysqli_real_escape_string($conn, $_POST['currency']);
        
        $order_id = 'ORD-' . date('YmdHis') . '-' . rand(1000, 9999);
        $order_date = date('Y-m-d H:i:s');
        $order_status = ($payment_method === 'mpesa' && !empty($mpesa_code)) ? 'paid' : 'pending';
        
        // Insert order into database
        $insert_order = "INSERT INTO orders (
            order_id, 
            customer_name, 
            customer_email, 
            customer_phone, 
            delivery_address, 
            payment_method, 
            mpesa_code, 
            total_amount, 
            currency, 
            cart_data, 
            order_status, 
            created_at
        ) VALUES (
            '$order_id', 
            '$customer_name', 
            '$customer_email', 
            '$customer_phone', 
            '$delivery_address', 
            '$payment_method', 
            '$mpesa_code', 
            $total_amount, 
            '$currency', 
            '$cart_data', 
            '$order_status', 
            '$order_date'
        )";
        
        if (mysqli_query($conn, $insert_order)) {
            // Clear cart
            $_SESSION['cart'] = [];
            
            // Send notification email to admin (optional)
            $admin_email = "titanich2024@gmail.com";
            $subject = "New Order Received - $order_id";
            $message = "
                New order received:\n\n
                Order ID: $order_id\n
                Customer: $customer_name\n
                Email: $customer_email\n
                Phone: $customer_phone\n
                Address: $delivery_address\n
                Payment Method: $payment_method\n
                M-Pesa Code: $mpesa_code\n
                Total: $currency $total_amount\n
                Status: $order_status\n
                Date: $order_date\n\n
                Please check your admin dashboard for full details.
            ";
            
            // Uncomment the line below to send email notifications
            // mail($admin_email, $subject, $message);
            
            // Redirect to success page
            header("Location: order_success.php?order_id=$order_id");
            exit();
            
        } else {
            throw new Exception("Database error: " . mysqli_error($conn));
        }
        
    } catch (Exception $e) {
        // Redirect to error page
        header("Location: order_error.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}
?>