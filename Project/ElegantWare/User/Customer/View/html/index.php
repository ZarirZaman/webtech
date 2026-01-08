<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crockery Store - Premium Ceramics & Tableware</title>
    <link rel="stylesheet" href="<?php echo ASSETS_URL; ?>css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!-- Header -->
    <header>
        <nav class="navbar">
            <div class="container">
                <a href="index.php" class="logo">Elegant<span>Ware</span></a>
                <ul class="nav-links">
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="#categories">Categories</a></li>
                    <li><a href="cart.php" class="cart-link">
                        <i class="fas fa-shopping-cart"></i> Cart
                        <?php if ($data['cart_count'] > 0): ?>
                            <span class="cart-badge"><?php echo $data['cart_count']; ?></span>
                        <?php endif; ?>
                    </a></li>
                    <li><a href="#products">Checkout</a></li>
                    <?php if($data['is_logged_in']): ?>
                        <li><a href="#dashboard">Dashboard</a></li>
                        <li><a href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="home-hero">
            <div class="hero-content">
                <h1 class="hero-title">Crafted by Nature, Inspired by Earth</h1>
                <p class="hero-subtitle">
                    Discover our collection of handmade ceramics, where art meets functionality and every piece tells a story of craftsmanship.
                </p>
                <div class="hero-buttons">
                    <?php if(!$data['is_logged_in']): ?>
                        <a href="register.php" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Get Started
                        </a>
                    <?php endif; ?>
                    <a href="#products" class="btn btn-secondary">
                        <i class="fas fa-shopping-bag"></i> Shop Now
                    </a>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features-section">
            <div class="container">
                <h2 class="section-title">Why Choose Our Store</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shipping-fast"></i>
                        </div>
                        <h3>Free Shipping</h3>
                        <p>On all orders over $50</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-undo-alt"></i>
                        </div>
                        <h3>30-Day Returns</h3>
                        <p>Easy return policy</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <h3>24/7 Support</h3>
                        <p>Customer service always available</p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-award"></i>
                        </div>
                        <h3>Premium Quality</h3>
                        <p>Handcrafted with care</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section id="products" class="products-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Featured Products</h2>
                    <a href="#products" class="view-all">
                        View All <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                
                <div class="products-grid">
                    <?php foreach ($data['featured_products'] as $product): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <i class="fas <?php echo $product['image_class']; ?>"></i>
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <span class="product-category"><?php echo $product['category']; ?></span>
                                <p class="product-description">
                                    <?php echo htmlspecialchars($product['description']); ?>
                                </p>
                                <div class="product-price">
                                    $<?php echo number_format($product['price'], 2); ?>
                                </div>
                                <div class="product-actions">
                                    <?php if($data['is_logged_in']): ?>
                                        <a href="index.php?add_to_cart=<?php echo $product['id']; ?>" 
                                           class="add-to-cart-btn"
                                           onclick="return addToCart(<?php echo $product['id']; ?>, event)">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </a>
                                    <?php else: ?>
                                        <a href="login.php" 
                                           class="add-to-cart-btn login-to-buy-btn">
                                            <i class="fas fa-sign-in-alt"></i> Login to Buy
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section id="categories" class="categories-section">
            <div class="container">
                <h2 class="section-title">Browse by Category</h2>
                <div class="categories-grid">
                    <a href="#products" onclick="filterByCategory('Plates')" class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h3>Plates</h3>
                        <p>Dinner plates, salad plates, dessert plates</p>
                    </a>
                    
                    <a href="#products" onclick="filterByCategory('Bowls')" class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-bowl-food"></i>
                        </div>
                        <h3>Bowls</h3>
                        <p>Soup bowls, salad bowls, serving bowls</p>
                    </a>
                    
                    <a href="#products" onclick="filterByCategory('Cups')" class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-mug-hot"></i>
                        </div>
                        <h3>Cups & Mugs</h3>
                        <p>Coffee mugs, tea cups, espresso cups</p>
                    </a>
                    
                    <a href="#products" onclick="filterByCategory('Sets')" class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-box-open"></i>
                        </div>
                        <h3>Complete Sets</h3>
                        <p>Dinner sets, tea sets, gift sets</p>
                    </a>
                </div>
            </div>
        </section>

        <!-- Call to Action Section -->
        <section class="cta-section">
            <div class="container">
                <div class="cta-content">
                    <h2>Ready to Transform Your Dining Experience?</h2>
                    <p>Join thousands of satisfied customers who have elevated their table settings with our premium ceramics.</p>
                    <div class="cta-buttons">
                        <?php if($data['is_logged_in']): ?>
                            <a href="cart.php" class="btn btn-primary">
                                <i class="fas fa-shopping-cart"></i> View Cart (<?php echo $data['cart_count']; ?>)
                            </a>
                        <?php else: ?>
                            <a href="register.php" class="btn btn-primary">
                                <i class="fas fa-user-plus"></i> Create Account
                            </a>
                            <a href="login.php" class="btn btn-secondary">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Crockery Store</h3>
                    <p>Premium ceramics and tableware for modern living. Crafted with care, designed for life.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-pinterest"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="index.php">Home</a></li>
                        <li><a href="#products">Products</a></li>
                        <li><a href="#categories">Categories</a></li>
                        <li><a href="#about">About Us</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Categories</h3>
                    <ul>
                        <li><a href="#products" onclick="filterByCategory('Plates')">Plates</a></li>
                        <li><a href="#products" onclick="filterByCategory('Bowls')">Bowls</a></li>
                        <li><a href="#products" onclick="filterByCategory('Cups')">Cups & Mugs</a></li>
                        <li><a href="#products" onclick="filterByCategory('Sets')">Complete Sets</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>Contact Info</h3>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> 123 Ceramic Street, Art District</li>
                        <li><i class="fas fa-phone"></i> (123) 456-7890</li>
                        <li><i class="fas fa-envelope"></i> info@crockerystore.com</li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Crockery Store. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="<?php echo ASSETS_URL; ?>js/index.js"></script>
</body>
</html>