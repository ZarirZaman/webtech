<?php
session_start();
require_once '../Model/config.php';
require_once '../Model/auth.php';
require_once '../Model/cart_func.php';

class CartController {
    private $conn;
    private $products;
    
    public function __construct() {
        global $conn;
        $this->conn = $conn;
        
        $this->products = $this->getProducts();
    }
    
    public function index() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('login.php');
        }
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePostRequest();
        }
        
        if (isset($_GET['add_to_cart'])) {
            $this->handleAddToCart();
        }

        $data = $this->getCartData();
        
        include '../View/html/cart_view.php';
    }
    
    private function handlePostRequest() {
        if (isset($_POST['add_to_cart'])) {
            $product_id = intval($_POST['product_id']);
            $quantity = intval($_POST['quantity'] ?? 1);
            
            if ($quantity < 1) $quantity = 1;
            
            addToCart($product_id, $quantity);
            $_SESSION['cart_message'] = 'Product added to cart successfully!';
            
        } elseif (isset($_POST['update_cart'])) {
            $this->handleUpdateCart();
            
        } elseif (isset($_POST['remove_item'])) {
            $index = intval($_POST['item_index'] ?? 0);
            if (isset($_SESSION['cart'][$index])) {
                unset($_SESSION['cart'][$index]);
                $_SESSION['cart'] = array_values($_SESSION['cart']);
            }
            $_SESSION['cart_message'] = 'Item removed from cart!';
            
        } elseif (isset($_POST['clear_cart'])) {
            clearCart();
            $_SESSION['cart_message'] = 'Cart cleared successfully!';
        }
        
        header('Location: cart.php');
        exit();
    }
    
    private function handleUpdateCart() {
        if (isset($_POST['quantity']) && is_array($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $index => $qty) {
                $index = intval($index);
                $qty = intval($qty);
                if ($qty <= 0) {
                    if (isset($_SESSION['cart'][$index])) {
                        unset($_SESSION['cart'][$index]);
                    }
                } else {
                    if (isset($_SESSION['cart'][$index])) {
                        $_SESSION['cart'][$index]['quantity'] = $qty;
                    }
                }
            }
            $_SESSION['cart'] = array_values($_SESSION['cart']);
            $_SESSION['cart_message'] = 'Cart updated successfully!';
        }
    }
    
    private function handleAddToCart() {
        $product_id = intval($_GET['add_to_cart']);
        addToCart($product_id);
        
        $_SESSION['cart_message'] = 'Product added to cart!';
        header('Location: cart.php');
        exit();
    }
    
    private function getProducts() {
        return [
            1 => ['id' => 1, 'name' => 'Ceramic Dinner Plate Set', 'description' => 'Beautiful ceramic dinner plates, set of 6', 'price' => 29.99, 'category' => 'Plates'],
            2 => ['id' => 2, 'name' => 'Porcelain Soup Bowls', 'description' => 'Elegant porcelain bowls for soup or salad', 'price' => 24.99, 'category' => 'Bowls'],
            3 => ['id' => 3, 'name' => 'Coffee Mug Collection', 'description' => 'Set of 4 ceramic coffee mugs', 'price' => 19.99, 'category' => 'Cups'],
            4 => ['id' => 4, 'name' => 'Complete Dinner Set', 'description' => '32-piece dinner set for family occasions', 'price' => 149.99, 'category' => 'Sets'],
            5 => ['id' => 5, 'name' => 'Decorative Serving Platters', 'description' => 'Set of 4 decorative serving platters', 'price' => 39.99, 'category' => 'Plates'],
            6 => ['id' => 6, 'name' => 'Handcrafted Salad Bowls', 'description' => 'Unique handcrafted bowls for salads', 'price' => 34.99, 'category' => 'Bowls'],
            7 => ['id' => 7, 'name' => 'Tea Cup Set', 'description' => 'Elegant tea cup set with saucers, set of 4', 'price' => 27.99, 'category' => 'Cups'],
            8 => ['id' => 8, 'name' => 'Luxury Dinnerware Set', 'description' => '24-piece luxury dinnerware set for special occasions', 'price' => 199.99, 'category' => 'Sets']
        ];
    }
    
    private function getCartData() {
        $cart_items = [];
        $cart_total = 0;
        $item_count = 0;
        
        foreach ($_SESSION['cart'] as $index => $cart_item) {
            if (isset($cart_item['id'])) {
                $product_id = intval($cart_item['id']);
            } else {
                continue;
            }
            
            if (isset($this->products[$product_id])) {
                $product = $this->products[$product_id];
                $quantity = intval($cart_item['quantity'] ?? 1);
                $item_total = $product['price'] * $quantity;
                
                $cart_items[] = [
                    'index' => $index, 
                    'product_id' => $product_id,
                    'product' => $product,
                    'quantity' => $quantity,
                    'item_total' => $item_total
                ];
                
                $cart_total += $item_total;
                $item_count += $quantity;
            }
        }
        
        $shipping_fee = ($cart_total > 50 || $item_count == 0) ? 0 : 5.99;
        $tax_rate = 0.08;
        $tax_amount = $cart_total * $tax_rate;
        $grand_total = $cart_total + $shipping_fee + $tax_amount;
        
        $user = $this->getUserInfo();
        
        return [
            'cart_items' => $cart_items,
            'cart_total' => $cart_total,
            'item_count' => $item_count,
            'shipping_fee' => $shipping_fee,
            'tax_amount' => $tax_amount,
            'grand_total' => $grand_total,
            'tax_rate' => $tax_rate,
            'user' => $user,
            'cart_message' => $_SESSION['cart_message'] ?? null,
            'is_logged_in' => isLoggedIn()
        ];
    }
    
    private function getUserInfo() {
        if (!isset($_SESSION['user_id'])) {
            return null;
        }
        
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT username, email FROM users WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            return $user;
        }
        
        return null;
    }
}
$cartController = new CartController();
$cartController->index();

if (isset($_SESSION['cart_message'])) {
    unset($_SESSION['cart_message']);
}
?>