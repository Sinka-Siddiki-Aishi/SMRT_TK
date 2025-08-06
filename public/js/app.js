// Mobile menu toggle functionality
function toggleMobileMenu() {
    const menu = document.getElementById('mobile-menu');
    if (menu) {
        menu.classList.toggle('hidden');
    }
}

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add any initialization code here
    console.log('SmartTix app loaded successfully!');
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const menu = document.getElementById('mobile-menu');
        const menuButton = event.target.closest('[onclick="toggleMobileMenu()"]');
        
        if (menu && !menu.contains(event.target) && !menuButton) {
            menu.classList.add('hidden');
        }
    });
    
    // Add smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
});

// Form validation helpers
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return false;
    
    let isValid = true;
    const inputs = form.querySelectorAll('input[required]');
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('border-red-500');
            isValid = false;
        } else {
            input.classList.remove('border-red-500');
        }
        
        if (input.type === 'email' && input.value && !validateEmail(input.value)) {
            input.classList.add('border-red-500');
            isValid = false;
        }
    });
    
    return isValid;
}

// Add loading states to buttons
function addLoadingState(button) {
    if (button) {
        button.disabled = true;
        button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading...';
    }
}

function removeLoadingState(button, originalText) {
    if (button) {
        button.disabled = false;
        button.innerHTML = originalText;
    }
}

// Search functionality
function initializeSearch() {
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            // Add search suggestions or live search functionality here
            console.log('Searching for:', this.value);
        });
    }
}

// Initialize search when page loads
document.addEventListener('DOMContentLoaded', initializeSearch);
