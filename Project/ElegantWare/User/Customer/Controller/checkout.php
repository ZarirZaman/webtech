<?php
session_start();
require_once '../Model/config.php';
require_once '../Model/auth.php';
require_once '../Model/cart_func.php';

class CheckoutController {
    
    public function index() {
        if (!isLoggedIn()) {
            redirect('login.php');
        }
        
        if (empty($_SESSION['cart'] ?? [])) {
            $_SESSION['error_message'] = 'Your cart is empty. Please add items to your cart before checking out.';
            redirect('cart.php');
        }
        
        $cart_data = $this->getCartData();
        
        $user_data = $this->getUserData();
        
        $data = array_merge($cart_data, $user_data);
        
        include '../View/html/checkout_view.php';
    }
    
    public function process() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('checkout.php');
        }
        
        if ($this->validateCheckoutData()) {
            $order_id = $this->createOrder();
            
            if ($order_id) {
                $this->clearCart();
                $_SESSION['order_success'] = 'Order placed successfully! Your order ID is: ' . $order_id;
                redirect('order_confirmation.php?order_id=' . $order_id);
            } else {
                $_SESSION['error_message'] = 'Failed to process order. Please try again.';
                redirect('checkout.php');
            }
        }
    }
    
    private function getCartData() {
        $cart_total = 0;
        $item_count = 0;
        
        $products = [
            1 => ['price' => 29.99, 'name' => 'Ceramic Dinner Plate Set'],
            2 => ['price' => 24.99, 'name' => 'Porcelain Soup Bowls'],
            3 => ['price' => 19.99, 'name' => 'Coffee Mug Collection'],
            4 => ['price' => 149.99, 'name' => 'Complete Dinner Set'],
            5 => ['price' => 39.99, 'name' => 'Decorative Serving Platters'],
            6 => ['price' => 34.99, 'name' => 'Handcrafted Salad Bowls'],
            7 => ['price' => 27.99, 'name' => 'Tea Cup Set'],
            8 => ['price' => 199.99, 'name' => 'Luxury Dinnerware Set']
        ];
        
        $cart_items = [];
        foreach ($_SESSION['cart'] as $index => $item) {
            if (isset($item['id']) && isset($products[$item['id']])) {
                $product = $products[$item['id']];
                $quantity = $item['quantity'] ?? 1;
                $item_total = $product['price'] * $quantity;
                
                $cart_items[] = [
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
        
        return [
            'cart_items' => $cart_items,
            'cart_total' => $cart_total,
            'item_count' => $item_count,
            'shipping_fee' => $shipping_fee,
            'tax_amount' => $tax_amount,
            'grand_total' => $grand_total,
            'tax_rate' => $tax_rate
        ];
    }
    
    private function getUserData() {
        global $conn;
        $user_id = $_SESSION['user_id'] ?? 0;
        
        $sql = "SELECT username, email, full_name, phone, address FROM users WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        
        if ($stmt) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            $stmt->close();
            
            return [
                'user' => $user
            ];
        }
        
        return ['user' => null];
    }
    
    private function validateCheckoutData() {
        $required_fields = ['first_name', 'last_name', 'street_address', 'city', 'state', 'zip_code', 'phone', 'payment_method'];
        
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error_message'] = "Please fill in all required fields.";
                return false;
            }
        }
        
        return true;
    }
    
    private function createOrder() {
        return 'ORD' . time() . rand(100, 999);
    }
    
    private function clearCart() {
        unset($_SESSION['cart']);
    }
}
$action = $_GET['action'] ?? 'index';
$controller = new CheckoutController();

if ($action === 'process') {
    $controller->process();
} else {
    $controller->index();
}
?>