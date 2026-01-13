// ======================
// FORM VALIDATION FUNCTIONS
// ======================

function validatePassword(password) {
    const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;
    return regex.test(password);
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type}`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="alert-close" onclick="this.parentElement.remove()">&times;</button>
    `;
    
    const main = document.querySelector('main');
    if (main) {
        main.prepend(alertDiv);
    }
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 5000);
}

function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    if (input) {
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
    }
}

// ======================
// CART FUNCTIONS
// ======================

function updateQuantity(index, change) {
    const input = document.querySelector(`input[name="quantity[${index}]"]`);
    if (input) {
        let value = parseInt(input.value) + change;
        
        if (value < 1) value = 1;
        if (value > 10) value = 10;
        
        input.value = value;
    }
}

function showCartNotification(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.cart-notification');
    existingNotifications.forEach(notif => notif.remove());
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = `cart-notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Show animation
    setTimeout(() => {
        notification.classList.add('show');
    }, 10);
    
    // Auto-remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

function addToCart(productId, event) {
    // Show loading animation
    const button = event?.target?.closest('.add-to-cart-btn');
    if (button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        button.disabled = true;
        
        setTimeout(() => {
            window.location.href = 'cart.php?add_to_cart=' + productId;
        }, 500);
    } else {
        window.location.href = 'cart.php?add_to_cart=' + productId;
    }
}

// ======================
// CART INITIALIZATION
// ======================

function initializeCart() {
    // Quantity button listeners
    document.querySelectorAll('.quantity-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            const change = parseInt(this.getAttribute('data-change'));
            updateQuantity(index, change);
        });
    });
    
    // Remove item confirmation
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to remove this item from your cart?')) {
                e.preventDefault();
            }
        });
    });
    
    // Clear cart confirmation
    const clearCartBtn = document.getElementById('clearCartBtn');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to clear your entire cart?')) {
                e.preventDefault();
            }
        });
    }
    
    // Auto-hide cart messages
    const cartMessages = document.querySelectorAll('.alert-message');
    cartMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.opacity = '0';
            setTimeout(() => {
                if (msg.parentNode) {
                    msg.parentNode.removeChild(msg);
                }
            }, 300);
        }, 5000);
    });
    
    // Checkout button validation
    const checkoutBtn = document.querySelector('.btn-checkout');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function(e) {
            // Check if cart has items using global cartData or DOM
            let hasItems = false;
            
            if (typeof cartData !== 'undefined') {
                hasItems = cartData.hasItems;
            } else {
                // Fallback: check DOM elements
                const emptyCart = document.querySelector('.cart-empty');
                const cartTableRows = document.querySelectorAll('.cart-table tbody tr');
                hasItems = !emptyCart && cartTableRows.length > 0;
            }
            
            if (!hasItems) {
                e.preventDefault();
                showCartNotification('Your cart is empty!', 'error');
                return false;
            }
            
            return true;
        });
    }
    
    // Form validation for cart updates
    const cartForm = document.getElementById('cartForm');
    if (cartForm) {
        cartForm.addEventListener('submit', function(e) {
            const submitBtn = e.submitter;
            
            if (submitBtn && submitBtn.name === 'update_cart') {
                // Validate quantities before updating
                const quantityInputs = document.querySelectorAll('.quantity-input');
                let isValid = true;
                
                quantityInputs.forEach(input => {
                    const value = parseInt(input.value);
                    if (isNaN(value) || value < 1 || value > 10) {
                        isValid = false;
                        input.style.borderColor = '#e74c3c';
                    } else {
                        input.style.borderColor = '';
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    showCartNotification('Please enter valid quantities (1-10)', 'error');
                    return false;
                }
            }
            
            return true;
        });
    }
}

// ======================
// DOM CONTENT LOADED
// ======================

document.addEventListener('DOMContentLoaded', function() {
    // Initialize cart if on cart page
    if (document.querySelector('.cart-page')) {
        initializeCart();
    }
    
    // Form validation for login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const email = document.getElementById('email')?.value;
            const password = document.getElementById('password')?.value;
            
            if (!email || !password) {
                e.preventDefault();
                showAlert('Please fill in all fields', 'danger');
            }
        });
    }
    
    // Form validation for registration
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            const password = document.getElementById('password')?.value;
            const confirmPassword = document.getElementById('confirm_password')?.value;
            
            if (password !== confirmPassword) {
                e.preventDefault();
                showAlert('Passwords do not match', 'danger');
                return;
            }
            
            if (!validatePassword(password)) {
                e.preventDefault();
                showAlert('Password must be at least 8 characters with uppercase, lowercase and number', 'danger');
            }
        });
    }
    
    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => {
                if (alert.parentNode) {
                    alert.remove();
                }
            }, 300);
        }, 5000);
    });
    
    // Toggle password visibility
    const showPasswordCheckboxes = document.querySelectorAll('input[type="checkbox"][onclick*="togglePasswordVisibility"]');
    showPasswordCheckboxes.forEach(checkbox => {
        const onclickAttr = checkbox.getAttribute('onclick');
        const match = onclickAttr.match(/togglePasswordVisibility\('([^']+)'\)/);
        if (match) {
            const inputId = match[1];
            checkbox.addEventListener('click', function() {
                togglePasswordVisibility(inputId);
            });
            checkbox.removeAttribute('onclick');
        }
    });
});



// Update cart badge (can be called from other pages)
function updateCartBadge(count) {
    const badges = document.querySelectorAll('.cart-badge');
    badges.forEach(badge => {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    });
}

// Smooth scrolling for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});
// View More/Less functionality for product descriptions
function toggleDescription(link) {
    const card = link.closest('.product-card');
    const shortDesc = card.querySelector('.description-short');
    const fullDesc = card.querySelector('.description-full');
    
    if (fullDesc.style.display === 'none' || !fullDesc.style.display) {
        shortDesc.style.display = 'none';
        fullDesc.style.display = 'inline';
        link.textContent = 'View Less';
    } else {
        shortDesc.style.display = 'inline';
        fullDesc.style.display = 'none';
        link.textContent = 'View More';
    }
}

// Product sorting functionality
function sortProducts(sortBy) {
    const grid = document.querySelector('.products-grid');
    const products = Array.from(grid.querySelectorAll('.product-card'));
    
    products.sort((a, b) => {
        const priceA = parseFloat(a.dataset.price);
        const priceB = parseFloat(b.dataset.price);
        const nameA = a.dataset.name.toLowerCase();
        const nameB = b.dataset.name.toLowerCase();
        
        switch(sortBy) {
            case 'high-low':
                return priceB - priceA;
            case 'low-high':
                return priceA - priceB;
            case 'name-asc':
                return nameA.localeCompare(nameB);
            case 'name-desc':
                return nameB.localeCompare(nameA);
            default:
                return 0;
        }
    });
    
    // Reorder products in the grid
    products.forEach(product => grid.appendChild(product));
}
// View More/Less functionality for product descriptions
function toggleDescription(link) {
    const card = link.closest('.product-card');
    const shortDesc = card.querySelector('.description-short');
    const fullDesc = card.querySelector('.description-full');
    
    if (fullDesc.style.display === 'none' || !fullDesc.style.display) {
        shortDesc.style.display = 'none';
        fullDesc.style.display = 'inline';
        link.textContent = 'View Less';
    } else {
        shortDesc.style.display = 'inline';
        fullDesc.style.display = 'none';
        link.textContent = 'View More';
    }
}

// View More/Less functionality
document.addEventListener('DOMContentLoaded', function() {
    // Setup View More links
    document.querySelectorAll('.view-more-link').forEach(function(link) {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            toggleDescription(this);
        });
    });
    
    // Setup sorting
    const sortSelect = document.getElementById('sortProducts');
    if (sortSelect) {
        // Set initial value
        sortSelect.value = 'price-high-low';
        
        // Add event listener
        sortSelect.addEventListener('change', function() {
            sortProducts(this.value);
        });
    }
    
    // Setup Add to Cart buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
        if (btn.href.includes('add_to_cart')) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = this.href.split('add_to_cart=')[1];
                addToCart(productId, e);
            });
        }
    });
});

// Function to toggle description visibility
function toggleDescription(link) {
    const productCard = link.closest('.product-card');
    const shortDesc = productCard.querySelector('.description-short');
    const fullDesc = productCard.querySelector('.description-full');
    
    if (fullDesc.style.display === 'none' || fullDesc.style.display === '') {
        if (shortDesc) shortDesc.style.display = 'none';
        fullDesc.style.display = 'inline';
        link.textContent = 'View Less';
    } else {
        if (shortDesc) shortDesc.style.display = 'inline';
        fullDesc.style.display = 'none';
        link.textContent = 'View More';
    }
}

// Function to sort products
function sortProducts(sortBy) {
    const productsGrid = document.getElementById('productsGrid');
    if (!productsGrid) return;
    
    const productCards = Array.from(productsGrid.querySelectorAll('.product-card'));
    
    productCards.sort(function(a, b) {
        const priceA = parseFloat(a.getAttribute('data-price')) || 0;
        const priceB = parseFloat(b.getAttribute('data-price')) || 0;
        const nameA = (a.getAttribute('data-name') || '').toLowerCase();
        const nameB = (b.getAttribute('data-name') || '').toLowerCase();
        
        switch(sortBy) {
            case 'price-high-low':
                return priceB - priceA; // High to low
            case 'price-low-high':
                return priceA - priceB; // Low to high
            case 'name-a-z':
                return nameA.localeCompare(nameB); // A to Z
            case 'name-z-a':
                return nameB.localeCompare(nameA); // Z to A
            default:
                return 0;
        }
    });
    
    // Clear the grid and re-add sorted products
    productsGrid.innerHTML = '';
    productCards.forEach(function(card) {
        productsGrid.appendChild(card);
    });
    
    // Re-attach event listeners to View More links after sorting
    setTimeout(function() {
        document.querySelectorAll('.view-more-link').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                toggleDescription(this);
            });
        });
    }, 100);
}

// Function to handle Add to Cart
function addToCart(productId, event) {
    const button = event.target.closest('.add-to-cart-btn');
    if (button) {
        const originalHTML = button.innerHTML;
        const originalText = button.textContent;
        
        // Show loading state
        button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
        button.disabled = true;
        
        // Simulate API delay
        setTimeout(function() {
            // Redirect to add to cart
            window.location.href = 'index.php?add_to_cart=' + productId;
        }, 500);
    }
    return false;
}