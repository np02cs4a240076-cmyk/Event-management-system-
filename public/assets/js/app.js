/**
 * Workshop 8 - College Sports Event Management System
 * Main JavaScript File
 */

// ==================== Document Ready ====================
document.addEventListener('DOMContentLoaded', function () {
    initializeNavigation();
    initializeDropdowns();
    initializeToasts();
    initializeModals();
});

// ==================== Navigation ====================
function initializeNavigation() {
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', function () {
            navMenu.classList.toggle('active');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function (e) {
            if (!navToggle.contains(e.target) && !navMenu.contains(e.target)) {
                navMenu.classList.remove('active');
            }
        });
    }
}

// ==================== Dropdowns ====================
function initializeDropdowns() {
    const userDropdownBtn = document.getElementById('userDropdownBtn');
    const userDropdownMenu = document.getElementById('userDropdownMenu');

    if (userDropdownBtn && userDropdownMenu) {
        userDropdownBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            userDropdownMenu.classList.toggle('active');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!userDropdownBtn.contains(e.target) && !userDropdownMenu.contains(e.target)) {
                userDropdownMenu.classList.remove('active');
            }
        });
    }
}

// ==================== Toast Notifications ====================
function initializeToasts() {
    // Auto-dismiss toasts
    const toasts = document.querySelectorAll('.toast[data-auto-dismiss]');
    toasts.forEach(toast => {
        setTimeout(() => {
            toast.style.animation = 'slideOut 0.3s ease forwards';
            setTimeout(() => toast.remove(), 300);
        }, 5000);
    });
}

function showToast(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
        <span>${escapeHtml(message)}</span>
        <button class="toast-close" onclick="this.parentElement.remove()">
            <i class="fas fa-times"></i>
        </button>
    `;

    container.appendChild(toast);

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease forwards';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
}

// Add slideOut animation
const style = document.createElement('style');
style.textContent = `
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }
`;
document.head.appendChild(style);

// ==================== Modals ====================
function initializeModals() {
    // Close modals on Escape key
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeDeleteModal();
            closeAttendModal();
        }
    });
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

function closeAttendModal() {
    const modal = document.getElementById('attendModal');
    if (modal) {
        modal.classList.remove('active');
    }
}

function openAttendModal(eventId, eventTitle) {
    const modal = document.getElementById('attendModal');
    const eventIdInput = document.getElementById('attendEventId');
    const eventTitleEl = document.getElementById('attendEventTitle');

    if (modal && eventIdInput && eventTitleEl) {
        eventIdInput.value = eventId;
        eventTitleEl.textContent = eventTitle;
        modal.classList.add('active');
    }
}

function confirmDelete(id, title, route = 'sports') {
    const modal = document.getElementById('deleteModal');
    const message = document.getElementById('deleteModalMessage');
    const form = document.getElementById('deleteForm');

    if (modal && message && form) {
        message.textContent = `Are you sure you want to delete "${title}"? This action cannot be undone.`;
        form.action = `index.php?route=${route}.delete&id=${id}`;
        modal.classList.add('active');
    }
}

// ==================== Form Validation ====================
function validateForm(formId, rules) {
    const form = document.getElementById(formId);
    if (!form) return false;

    let isValid = true;
    const errors = {};

    for (const [fieldId, fieldRules] of Object.entries(rules)) {
        const field = document.getElementById(fieldId);
        if (!field) continue;

        const value = field.value.trim();

        // Required validation
        if (fieldRules.required && !value) {
            errors[fieldId] = fieldRules.requiredMessage || 'This field is required';
            isValid = false;
            continue;
        }

        // Email validation
        if (fieldRules.email && value && !isValidEmail(value)) {
            errors[fieldId] = fieldRules.emailMessage || 'Please enter a valid email';
            isValid = false;
            continue;
        }

        // Min length validation
        if (fieldRules.minLength && value.length < fieldRules.minLength) {
            errors[fieldId] = fieldRules.minLengthMessage || `Minimum ${fieldRules.minLength} characters required`;
            isValid = false;
            continue;
        }

        // Match validation
        if (fieldRules.match) {
            const matchField = document.getElementById(fieldRules.match);
            if (matchField && value !== matchField.value) {
                errors[fieldId] = fieldRules.matchMessage || 'Fields do not match';
                isValid = false;
            }
        }
    }

    // Display errors
    for (const [fieldId, error] of Object.entries(errors)) {
        const field = document.getElementById(fieldId);
        if (field) {
            field.classList.add('is-invalid');

            // Add or update error message
            let feedback = field.parentElement.querySelector('.invalid-feedback');
            if (!feedback) {
                feedback = document.createElement('div');
                feedback.className = 'invalid-feedback';
                field.parentElement.appendChild(feedback);
            }
            feedback.textContent = error;
        }
    }

    return isValid;
}

function clearFieldError(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.classList.remove('is-invalid');
        const feedback = field.parentElement.querySelector('.invalid-feedback');
        if (feedback) {
            feedback.remove();
        }
    }
}

// ==================== Utility Functions ====================
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric'
    });
}

function formatTime(timeStr) {
    const [hours, minutes] = timeStr.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const hour12 = hour % 12 || 12;
    return `${hour12}:${minutes} ${ampm}`;
}

// ==================== AJAX Helpers ====================
async function fetchJSON(url, options = {}) {
    try {
        const response = await fetch(url, {
            ...options,
            headers: {
                'Content-Type': 'application/json',
                ...options.headers
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error('Fetch error:', error);
        throw error;
    }
}

async function postForm(url, formData) {
    try {
        const response = await fetch(url, {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        return await response.json();
    } catch (error) {
        console.error('Post error:', error);
        throw error;
    }
}

// ==================== Search Functionality ====================
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// ==================== Loading States ====================
function showLoading(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.classList.add('loading');
        element.innerHTML = `
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <span>Loading...</span>
            </div>
        `;
    }
}

function hideLoading(elementId, content) {
    const element = document.getElementById(elementId);
    if (element) {
        element.classList.remove('loading');
        element.innerHTML = content;
    }
}

// ==================== Confirmation Dialogs ====================
function confirmAction(message, callback) {
    if (confirm(message)) {
        callback();
    }
}

// ==================== Copy to Clipboard ====================
async function copyToClipboard(text) {
    try {
        await navigator.clipboard.writeText(text);
        showToast('Copied to clipboard!', 'success');
    } catch (err) {
        console.error('Failed to copy:', err);
        showToast('Failed to copy', 'error');
    }
}

// ==================== Password Toggle ====================
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-icon');

    if (input && icon) {
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
}

// ==================== Smooth Scroll ====================
function scrollToElement(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// ==================== Local Storage Helpers ====================
function saveToStorage(key, value) {
    try {
        localStorage.setItem(key, JSON.stringify(value));
    } catch (e) {
        console.error('Storage error:', e);
    }
}

function getFromStorage(key, defaultValue = null) {
    try {
        const value = localStorage.getItem(key);
        return value ? JSON.parse(value) : defaultValue;
    } catch (e) {
        console.error('Storage error:', e);
        return defaultValue;
    }
}

function removeFromStorage(key) {
    try {
        localStorage.removeItem(key);
    } catch (e) {
        console.error('Storage error:', e);
    }
}

// ==================== Print Functionality ====================
function printSection(sectionId) {
    const section = document.getElementById(sectionId);
    if (section) {
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
            <head>
                <title>Print</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background: #f5f5f5; }
                </style>
            </head>
            <body>
                ${section.innerHTML}
            </body>
            </html>
        `);
        printWindow.document.close();
        printWindow.print();
    }
}

// ==================== Date/Time Utilities ====================
function getRelativeTime(date) {
    const now = new Date();
    const diff = date - now;
    const absDiff = Math.abs(diff);

    const seconds = Math.floor(absDiff / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);

    if (days > 0) {
        return diff > 0 ? `in ${days} day(s)` : `${days} day(s) ago`;
    }
    if (hours > 0) {
        return diff > 0 ? `in ${hours} hour(s)` : `${hours} hour(s) ago`;
    }
    if (minutes > 0) {
        return diff > 0 ? `in ${minutes} minute(s)` : `${minutes} minute(s) ago`;
    }
    return diff > 0 ? 'in a moment' : 'just now';
}

// ==================== Form Auto-save ====================
function enableAutoSave(formId, storageKey) {
    const form = document.getElementById(formId);
    if (!form) return;

    // Restore saved data
    const savedData = getFromStorage(storageKey);
    if (savedData) {
        for (const [name, value] of Object.entries(savedData)) {
            const field = form.elements[name];
            if (field && field.type !== 'hidden' && field.name !== 'csrf_token') {
                field.value = value;
            }
        }
    }

    // Save on input
    form.addEventListener('input', debounce(() => {
        const formData = new FormData(form);
        const data = {};
        for (const [key, value] of formData.entries()) {
            if (key !== 'csrf_token') {
                data[key] = value;
            }
        }
        saveToStorage(storageKey, data);
    }, 500));

    // Clear on submit
    form.addEventListener('submit', () => {
        removeFromStorage(storageKey);
    });
}

// ==================== Character Counter ====================
function enableCharCounter(inputId, counterId, maxLength) {
    const input = document.getElementById(inputId);
    const counter = document.getElementById(counterId);

    if (input && counter) {
        const updateCounter = () => {
            const remaining = maxLength - input.value.length;
            counter.textContent = `${remaining} characters remaining`;
            counter.classList.toggle('text-danger', remaining < 20);
        };

        input.addEventListener('input', updateCounter);
        updateCounter();
    }
}

// ==================== Export Functions ====================
window.showToast = showToast;
window.closeDeleteModal = closeDeleteModal;
window.closeAttendModal = closeAttendModal;
window.openAttendModal = openAttendModal;
window.confirmDelete = confirmDelete;
window.togglePassword = togglePassword;
window.copyToClipboard = copyToClipboard;
