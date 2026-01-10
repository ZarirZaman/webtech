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
    if (checkoutBtn && checkoutBtn.tagName === 'A') {
        checkoutBtn.addEventListener('click', function(e) {
            // Check if cart has items
            const emptyCart = document.querySelector('.cart-empty');
            const cartTableRows = document.querySelectorAll('.cart-table tbody tr');
            const hasItems = !emptyCart && cartTableRows.length > 0;
            
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
    // Initialize cart functionality
    if (document.querySelector('.cart-page')) {
        initializeCart();
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
});

// Global cart functions
window.updateCartBadge = function(count) {
    const badges = document.querySelectorAll('.cart-badge');
    badges.forEach(badge => {
        if (count > 0) {
            badge.textContent = count;
            badge.style.display = 'inline-block';
        } else {
            badge.style.display = 'none';
        }
    });
};