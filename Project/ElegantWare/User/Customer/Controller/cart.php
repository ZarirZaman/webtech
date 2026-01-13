<?php
// Include config to start session and get database connection
require_once '../Model/config.php';

// Include auth functions
require_once '../Model/auth.php';

// Include cart functions
require_once '../Model/cart_func.php';

// Check if user is logged in
if (!isLoggedIn()) {
    redirect('login.php');
}

// Initialize cart in session if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$error = '';
$success = '';

// Handle GET requests for adding items (from index.php links)
if (isset($_GET['add_to_cart'])) {
    $product_id = intval($_GET['add_to_cart']);
    addToCart($product_id);
    
    $_SESSION['cart_message'] = 'Product added to cart!';
    header('Location: cart.php');
    exit();
}

// Handle POST requests for cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_to_cart'])) {
        $product_id = intval($_POST['product_id']);
        $quantity = intval($_POST['quantity'] ?? 1);
        
        if ($quantity < 1) $quantity = 1;
        
        addToCart($product_id, $quantity);
        $_SESSION['cart_message'] = 'Product added to cart successfully!';
        
    } elseif (isset($_POST['update_cart'])) {
        // Update quantities using array index
        if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $index => $qty) {
                $index = intval($index);
                $qty = intval($qty);
                if ($qty <= 0) {
                    // Remove item by index
                    if (isset($_SESSION['cart'][$index])) {
                        unset($_SESSION['cart'][$index]);
                    }
                } else {
                    // Update quantity by index
                    if (isset($_SESSION['cart'][$index])) {
                        $_SESSION['cart'][$index]['quantity'] = $qty;
                    }
                }
            }
            // Re-index array after possible deletions
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            $_SESSION['cart_message'] = 'Cart updated successfully!';
        }
        
    } elseif (isset($_POST['remove_item'])) {
        // Remove specific item by index
        $index = intval($_POST['index'] ?? 0);
        if (isset($_SESSION['cart'][$index])) {
            unset($_SESSION['cart'][$index]);
            // Re-index array
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
        $_SESSION['cart_message'] = 'Item removed from cart!';
        
    } elseif (isset($_POST['clear_cart'])) {
        // Clear entire cart
        clearCart();
        $_SESSION['cart_message'] = 'Cart cleared successfully!';
    }
    
    // Redirect to avoid form resubmission
    header('Location: cart.php');
    exit();
}

// Sample products database
$products = [
    1 => [
        'id' => 1, 
        'name' => 'Ceramic Dinner Plate Set', 
        'description' => 'Beautiful ceramic dinner plates, set of 6', 
        'price' => 29.99, 
        'category' => 'Plates', 
        'image' => 'plate.jpg'
    ],
    2 => [
        'id' => 2, 
        'name' => 'Porcelain Soup Bowls', 
        'description' => 'Elegant porcelain bowls for soup or salad', 
        'price' => 24.99, 
        'category' => 'Bowls', 
        'image' => 'bowl.jpg'
    ],
    3 => [
        'id' => 3, 
        'name' => 'Coffee Mug Collection', 
        'description' => 'Set of 4 ceramic coffee mugs', 
        'price' => 19.99, 
        'category' => 'Cups', 
        'image' => 'cup.jpg'
    ],
    4 => [
        'id' => 4, 
        'name' => 'Complete Dinner Set', 
        'description' => '32-piece dinner set for family occasions', 
        'price' => 149.99, 
        'category' => 'Sets', 
        'image' => 'set.jpg'
    ],
];

// Calculate cart totals
$cart_items = [];
$cart_total = 0;
$item_count = 0;

// Loop through cart items (indexed array)
foreach ($_SESSION['cart'] as $index => $cart_item) {
    // Get product_id from the cart item
    if (isset($cart_item['product_id'])) {
        $product_id = intval($cart_item['product_id']);
    } elseif (isset($cart_item['id'])) {
        $product_id = intval($cart_item['id']);
    } else {
        continue; // Skip if no product ID
    }
    
    if (isset($products[$product_id])) {
        $product = $products[$product_id];
        $quantity = intval($cart_item['quantity'] ?? 1);
        $item_total = $product['price'] * $quantity;
        
        $cart_items[] = [
            'index' => $index, // Store the index for removing items
            'product_id' => $product_id,
            'product' => $product,
            'quantity' => $quantity,
            'item_total' => $item_total
        ];
        
        $cart_total += $item_total;
        $item_count += $quantity;
    }
}

// Calculate shipping, tax, and totals
$shipping_fee = ($cart_total > 50 || $item_count == 0) ? 0 : 5.99;
$tax_rate = 0.08; // 8%
$tax_amount = $cart_total * $tax_rate;
$grand_total = $cart_total + $shipping_fee + $tax_amount;

// Get user info for header
$user_id = $_SESSION['user_id'];
$sql = "SELECT username FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$user = null;

if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

// Prepare data for view
$data = [
    'cart_items' => $cart_items,
    'cart_total' => $cart_total,
    'item_count' => $item_count,
    'shipping_fee' => $shipping_fee,
    'tax_amount' => $tax_amount,
    'grand_total' => $grand_total,
    'tax_rate' => $tax_rate,
    'products' => $products,
    'user' => $user,
    'cart_message' => $_SESSION['cart_message'] ?? null,
    'is_logged_in' => isLoggedIn()
];

// Clear the cart message after displaying
if (isset($_SESSION['cart_message'])) {
    unset($_SESSION['cart_message']);
}

// Include the view
include '../View/html/cart_view.php';
?>