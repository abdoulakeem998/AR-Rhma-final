/**
 * AR-RAHMA WEBSITE - MAIN JAVASCRIPT FILE
 * Handles all interactive functionality
 */

// Initialize on DOM load
document.addEventListener('DOMContentLoaded', function() {
    initNavigation();
    initForms();
    initModals();
    initActivityCards();
    initGallery();
    initAlerts();
    initScrollAnimations();
});

/**
 * Navigation Functionality
 */
function initNavigation() {
    // Highlight active nav link
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index.php')) {
            link.classList.add('active');
        }
    });
    
    // Mobile menu toggle (if using custom menu)
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('show');
        });
    }
    
    // Sticky header on scroll
    let lastScroll = 0;
    const header = document.querySelector('.main-header');
    
    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
            header.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        } else {
            header.style.boxShadow = '0 2px 8px rgba(0,0,0,0.1)';
        }
        
        lastScroll = currentScroll;
    });
}

/**
 * Form Handling
 */
function initForms() {
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!form.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });
    
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const strengthIndicator = document.getElementById('passwordStrength');
    
    if (passwordInput && strengthIndicator) {
        passwordInput.addEventListener('input', function() {
            const strength = calculatePasswordStrength(this.value);
            updatePasswordStrength(strength, strengthIndicator);
        });
    }
    
    // Confirm password validation
    const confirmPassword = document.getElementById('confirmPassword');
    if (confirmPassword && passwordInput) {
        confirmPassword.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    }
    
    // File upload preview
    const fileInputs = document.querySelectorAll('input[type="file"]');
    fileInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            handleFilePreview(e.target);
        });
    });
}

/**
 * Calculate password strength
 */
function calculatePasswordStrength(password) {
    let strength = 0;
    
    if (password.length >= 8) strength++;
    if (password.length >= 12) strength++;
    if (/[a-z]/.test(password)) strength++;
    if (/[A-Z]/.test(password)) strength++;
    if (/[0-9]/.test(password)) strength++;
    if (/[^a-zA-Z0-9]/.test(password)) strength++;
    
    return strength;
}

/**
 * Update password strength display
 */
function updatePasswordStrength(strength, indicator) {
    const levels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
    const colors = ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#28a745', '#007bff'];
    
    indicator.textContent = levels[strength] || 'Very Weak';
    indicator.style.color = colors[strength] || colors[0];
}

/**
 * File preview handler
 */
function handleFilePreview(input) {
    const file = input.files[0];
    const previewId = input.getAttribute('data-preview');
    
    if (file && previewId) {
        const preview = document.getElementById(previewId);
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = function(e) {
                if (preview.tagName === 'IMG') {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                }
            };
            reader.readAsDataURL(file);
        }
    }
}

/**
 * Modal Functionality
 */
function initModals() {
    // Activity detail modal
    const viewButtons = document.querySelectorAll('.view-activity-details');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const activityId = this.getAttribute('data-activity-id');
            loadActivityDetails(activityId);
        });
    });
}

/**
 * Load activity details via AJAX
 */
function loadActivityDetails(activityId) {
    const modal = document.getElementById('activityModal');
    const modalBody = document.getElementById('activityModalBody');
    
    if (!modal || !modalBody) return;
    
    // Show loading spinner
    modalBody.innerHTML = '<div class="spinner"></div>';
    
    // Fetch activity details
    fetch(`php/get_activity.php?id=${activityId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.activity) {
                displayActivityDetails(data.activity, modalBody);
            } else {
                modalBody.innerHTML = '<p class="text-danger">Failed to load activity details.</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = '<p class="text-danger">An error occurred while loading details.</p>';
        });
}

/**
 * Display activity details in modal
 */
function displayActivityDetails(activity, container) {
    const html = `
        <div class="row">
            <div class="col-md-6">
                <img src="${activity.image_url || 'images/placeholder.jpg'}" 
                     alt="${escapeHtml(activity.title)}" 
                     class="img-fluid rounded"
                     onerror="this.src='images/placeholder.jpg'">
            </div>
            <div class="col-md-6">
                <h4>${escapeHtml(activity.title)}</h4>
                <p><strong>Date:</strong> ${formatDate(activity.date)}</p>
                <p><strong>Location:</strong> ${escapeHtml(activity.location)}</p>
                ${activity.beneficiaries ? `<p><strong>Beneficiaries:</strong> ${activity.beneficiaries} people</p>` : ''}
                ${activity.category ? `<p><strong>Category:</strong> ${formatCategory(activity.category)}</p>` : ''}
            </div>
            <div class="col-12 mt-3">
                <h5>Description</h5>
                <p>${escapeHtml(activity.description).replace(/\n/g, '<br>')}</p>
            </div>
        </div>
    `;
    
    container.innerHTML = html;
}

/**
 * Activity Cards Interactive Features
 */
function initActivityCards() {
    const cards = document.querySelectorAll('.activity-card');
    
    cards.forEach(card => {
        // Add hover animation
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
}

/**
 * Gallery Functionality
 */
function initGallery() {
    const galleryItems = document.querySelectorAll('.gallery-item');
    
    galleryItems.forEach(item => {
        item.addEventListener('click', function() {
            const imgSrc = this.querySelector('img').src;
            const title = this.querySelector('img').alt;
            showLightbox(imgSrc, title);
        });
    });
}

/**
 * Show image lightbox
 */
function showLightbox(imgSrc, title) {
    // Create lightbox overlay
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox-overlay';
    lightbox.innerHTML = `
        <div class="lightbox-content">
            <span class="lightbox-close">&times;</span>
            <img src="${imgSrc}" alt="${escapeHtml(title)}">
            <p class="lightbox-title">${escapeHtml(title)}</p>
        </div>
    `;
    
    document.body.appendChild(lightbox);
    document.body.style.overflow = 'hidden';
    
    // Close lightbox on click
    lightbox.addEventListener('click', function(e) {
        if (e.target.classList.contains('lightbox-overlay') || 
            e.target.classList.contains('lightbox-close')) {
            closeLightbox(lightbox);
        }
    });
    
    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeLightbox(lightbox);
        }
    });
}

/**
 * Close lightbox
 */
function closeLightbox(lightbox) {
    lightbox.style.opacity = '0';
    setTimeout(() => {
        lightbox.remove();
        document.body.style.overflow = 'auto';
    }, 300);
}

/**
 * Auto-dismiss alerts
 */
function initAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
    
    // Close button for alerts
    const closeButtons = document.querySelectorAll('.alert .btn-close, .alert .close');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const alert = this.closest('.alert');
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    });
}

/**
 * Scroll Animations
 */
function initScrollAnimations() {
    const elements = document.querySelectorAll('.animate-on-scroll');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animated');
            }
        });
    }, {
        threshold: 0.1
    });
    
    elements.forEach(el => observer.observe(el));
}

/**
 * Utility Functions
 */

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Format date
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', options);
}

// Format category name
function formatCategory(category) {
    return category.replace(/_/g, ' ')
                  .split(' ')
                  .map(word => word.charAt(0).toUpperCase() + word.slice(1))
                  .join(' ');
}

// Show loading spinner
function showLoading(container) {
    container.innerHTML = '<div class="spinner"></div>';
}

// Show success message
function showSuccess(message, container) {
    const alert = `
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            ${escapeHtml(message)}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    container.innerHTML = alert;
}

// Show error message
function showError(message, container) {
    const alert = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            ${escapeHtml(message)}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    container.innerHTML = alert;
}

// AJAX helper function
function ajax(url, options = {}) {
    return fetch(url, {
        method: options.method || 'GET',
        headers: {
            'Content-Type': 'application/json',
            ...options.headers
        },
        body: options.body ? JSON.stringify(options.body) : null
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    });
}

/**
 * Application form submission
 */
function submitApplication(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        submitBtn.disabled = true;
        submitBtn.textContent = 'Submitting...';
        
        fetch('php/submit_application.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message, document.getElementById('messageContainer'));
                form.reset();
                setTimeout(() => {
                    window.location.href = 'my_applications.php';
                }, 2000);
            } else {
                showError(data.message, document.getElementById('messageContainer'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('An error occurred. Please try again.', document.getElementById('messageContainer'));
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });
}

/**
 * Search functionality
 */
function initSearch(searchInputId, resultsContainerId) {
    const searchInput = document.getElementById(searchInputId);
    const resultsContainer = document.getElementById(resultsContainerId);
    
    if (!searchInput || !resultsContainer) return;
    
    let timeout;
    searchInput.addEventListener('input', function() {
        clearTimeout(timeout);
        const query = this.value.trim();
        
        if (query.length < 2) {
            resultsContainer.innerHTML = '';
            return;
        }
        
        timeout = setTimeout(() => {
            performSearch(query, resultsContainer);
        }, 300);
    });
}

/**
 * Perform search
 */
function performSearch(query, container) {
    showLoading(container);
    
    fetch(`php/search.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.results.length > 0) {
                displaySearchResults(data.results, container);
            } else {
                container.innerHTML = '<p class="text-muted">No results found.</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = '<p class="text-danger">Search error occurred.</p>';
        });
}

/**
 * Display search results
 */
function displaySearchResults(results, container) {
    const html = results.map(result => `
        <div class="search-result-item">
            <h6><a href="${result.url}">${escapeHtml(result.title)}</a></h6>
            <p class="small text-muted">${escapeHtml(result.excerpt)}</p>
        </div>
    `).join('');
    
    container.innerHTML = html;
}

// Add lightbox styles dynamically
const lightboxStyles = `
    <style>
        .lightbox-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.95);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            opacity: 1;
            transition: opacity 0.3s ease;
        }
        
        .lightbox-content {
            position: relative;
            max-width: 90%;
            max-height: 90%;
        }
        
        .lightbox-content img {
            max-width: 100%;
            max-height: 80vh;
            object-fit: contain;
        }
        
        .lightbox-close {
            position: absolute;
            top: -40px;
            right: 0;
            font-size: 40px;
            color: white;
            cursor: pointer;
            transition: color 0.3s;
        }
        
        .lightbox-close:hover {
            color: #D4AF37;
        }
        
        .lightbox-title {
            color: white;
            text-align: center;
            margin-top: 1rem;
        }
    </style>
`;

document.head.insertAdjacentHTML('beforeend', lightboxStyles);
