// ======================
// CART FUNCTIONS
// ======================

function addToCart(productId, event) {
    if (event) {
        event.preventDefault();
        // Show loading animation
        const button = event.target.closest('.add-to-cart-btn');
        if (button) {
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Adding...';
            button.disabled = true;
        }
    }
    
    window.location.href = 'index.php?add_to_cart=' + productId;
    return false;
}

function filterByCategory(category) {
    alert('Filtering by: ' + category);
    return false;
}

// ======================
// DOM CONTENT LOADED
// ======================

document.addEventListener('DOMContentLoaded', function() {
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
