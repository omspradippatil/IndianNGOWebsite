<?php
require_once 'auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isLoggedIn()) {
        header('Location: login.php?redirect=products.php');
        exit;
    }
    
    $product_id = intval($_POST['product_id'] ?? 0);
    $user_id = intval($_SESSION['user_id']);
    global $conn;
    
    if ($product_id > 0) {
        // Check if product exists and has stock using prepared statement
        $stmt = $conn->prepare("SELECT id, stock_quantity FROM products WHERE id = ? AND stock_quantity > 0");
        if (!$stmt) {
            header('Location: products.php?error=Database error');
            exit;
        }
        
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();
        
        if ($product) {
            // Check if already in cart
            $check_stmt = $conn->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
            if (!$check_stmt) {
                header('Location: products.php?error=Database error');
                exit;
            }
            
            $check_stmt->bind_param("ii", $user_id, $product_id);
            $check_stmt->execute();
            $existing_result = $check_stmt->get_result();
            $existing = $existing_result->fetch_assoc();
            $check_stmt->close();
            
            if ($existing) {
                // Update quantity
                $update_stmt = $conn->prepare("UPDATE cart SET quantity = quantity + 1 WHERE user_id = ? AND product_id = ?");
                if (!$update_stmt) {
                    header('Location: products.php?error=Database error');
                    exit;
                }
                
                $update_stmt->bind_param("ii", $user_id, $product_id);
                $update_stmt->execute();
                $update_stmt->close();
            } else {
                // Insert new item
                $insert_stmt = $conn->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1)");
                if (!$insert_stmt) {
                    header('Location: products.php?error=Database error');
                    exit;
                }
                
                $insert_stmt->bind_param("ii", $user_id, $product_id);
                $insert_stmt->execute();
                $insert_stmt->close();
            }
            
            header('Location: products.php?message=Product added to cart');
            exit;
        } else {
            header('Location: products.php?error=Product not found or out of stock');
            exit;
        }
    }
}
header('Location: products.php');
exit;
?>

