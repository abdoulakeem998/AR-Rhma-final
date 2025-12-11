/**
 * AR-RAHMA WEBSITE - MAIN JAVASCRIPT
 */

document.addEventListener('DOMContentLoaded', function() {
    initNavigation();
    initForms();
    initAlerts();
    initActivityModals();
});

// Navigation
function initNavigation() {
    const currentPage = window.location.pathname.split('/').pop() || 'index.php';
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href === currentPage || (currentPage === '' && href === 'index.php')) {
            link.classList.add('active');
        }
    });
}

// Form Validation
function initForms() {
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
    
    // Password strength
    const passwordInput = document.getElementById('password');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const strength = calculatePasswordStrength(this.value);
            updatePasswordStrength(strength);
        });
    }
    
    // Confirm password
    const confirmPassword = document.getElementById('confirm_password');
    if (confirmPassword && passwordInput) {
        confirmPassword.addEventListener('input', function() {
            if (this.value !== passwordInput.value) {
                this.setCustomValidity('Passwords do not match');
            } else {
                this.setCustomValidity('');
            }
        });
    }
}

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

function updatePasswordStrength(strength) {
    const indicator = document.getElementById('passwordStrength');
    if (!indicator) return;
    
    const levels = ['Very Weak', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
    const colors = ['#dc3545', '#fd7e14', '#ffc107', '#20c997', '#28a745', '#007bff'];
    
    indicator.textContent = levels[strength] || 'Very Weak';
    indicator.style.color = colors[strength] || colors[0];
}

// Auto-dismiss alerts
function initAlerts() {
    const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
}

// Activity Modals
function initActivityModals() {
    const viewButtons = document.querySelectorAll('[data-activity-id]');
    
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const activityId = this.getAttribute('data-activity-id');
            loadActivityDetails(activityId);
        });
    });
}

function loadActivityDetails(id) {
    const modalBody = document.getElementById('activityModalBody');
    if (!modalBody) return;
    
    modalBody.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div></div>';
    
    fetch(`includes/get_activity.php?id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.activity) {
                displayActivityDetails(data.activity);
            } else {
                modalBody.innerHTML = '<p class="text-danger">Failed to load activity details.</p>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalBody.innerHTML = '<p class="text-danger">An error occurred.</p>';
        });
}

function displayActivityDetails(activity) {
    const modalBody = document.getElementById('activityModalBody');
    const modalTitle = document.getElementById('activityModalTitle');
    
    if (modalTitle) modalTitle.textContent = activity.title;
    
    const html = `
        <div class="row">
            <div class="col-md-6">
                <img src="${activity.image_url || 'assets/images/placeholder.jpg'}" 
                     alt="${escapeHtml(activity.title)}" 
                     class="img-fluid rounded">
            </div>
            <div class="col-md-6">
                <h4>${escapeHtml(activity.title)}</h4>
                <p><strong>Date:</strong> ${formatDate(activity.activity_date)}</p>
                <p><strong>Location:</strong> ${escapeHtml(activity.location)}</p>
                ${activity.beneficiaries ? `<p><strong>Beneficiaries:</strong> ${activity.beneficiaries}</p>` : ''}
            </div>
            <div class="col-12 mt-3">
                <h5>Description</h5>
                <p>${escapeHtml(activity.description)}</p>
            </div>
        </div>
    `;
    
    modalBody.innerHTML = html;
}

// Utility Functions
function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, m => map[m]);
}

function formatDate(dateString) {
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-US', options);
}

// Delete confirmation
function confirmDelete(message) {
    return confirm(message || 'Are you sure you want to delete this item?');
}

// Show loading
function showLoading(container) {
    if (container) {
        container.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"></div></div>';
    }
}

// Image preview
function previewImage(input, previewId) {
    const file = input.files[0];
    const preview = document.getElementById(previewId);
    
    if (file && preview) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}
