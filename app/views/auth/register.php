<?php
$title = 'Sign Up';
ob_start();
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1>Create Account</h1>
            <p>Join SportHub and start managing events</p>
        </div>
        
        <form action="index.php?route=auth.store" method="POST" class="auth-form" id="registerForm">
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
            
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-user"></i> Full Name
                </label>
                <input type="text" id="name" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" 
                       placeholder="Enter your full name" required autofocus>
                <?php if (isset($errors['name'])): ?>
                    <div class="invalid-feedback"><?= e($errors['name']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input type="email" id="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                       placeholder="Enter your email" required>
                <div class="email-status" id="emailStatus"></div>
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= e($errors['email']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="password" class="form-label">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" id="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                               placeholder="Create a password" required minlength="6">
                        <button type="button" class="password-toggle" onclick="togglePassword('password')">
                            <i class="fas fa-eye" id="password-icon"></i>
                        </button>
                    </div>
                    <?php if (isset($errors['password'])): ?>
                        <div class="invalid-feedback"><?= e($errors['password']) ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password" class="form-label">
                        <i class="fas fa-lock"></i> Confirm Password
                    </label>
                    <div class="password-input-wrapper">
                        <input type="password" id="confirm_password" name="confirm_password" class="form-control <?= isset($errors['confirm_password']) ? 'is-invalid' : '' ?>" 
                               placeholder="Confirm password" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                            <i class="fas fa-eye" id="confirm_password-icon"></i>
                        </button>
                    </div>
                    <?php if (isset($errors['confirm_password'])): ?>
                        <div class="invalid-feedback"><?= e($errors['confirm_password']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="password-strength" id="passwordStrength">
                <div class="strength-bar">
                    <div class="strength-fill" id="strengthFill"></div>
                </div>
                <span class="strength-text" id="strengthText">Password strength</span>
            </div>
            
            <div class="form-group form-check">
                <label class="checkbox-label">
                    <input type="checkbox" name="terms" class="form-checkbox" required>
                    <span class="checkmark"></span>
                    I agree to the Terms & Conditions
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block btn-lg">
                <i class="fas fa-user-plus"></i> Create Account
            </button>
        </form>
        
        <div class="auth-footer">
            <p>Already have an account? <a href="index.php?route=auth.login">Sign in</a></p>
        </div>
    </div>
    
    <div class="auth-sidebar">
        <div class="auth-sidebar-content">
            <h2><i class="fas fa-trophy"></i> SportHub</h2>
            <h3>Join Our Sports Community</h3>
            <ul class="feature-list">
                <li><i class="fas fa-check-circle"></i> Create and manage events</li>
                <li><i class="fas fa-check-circle"></i> Register for competitions</li>
                <li><i class="fas fa-check-circle"></i> Build your team</li>
                <li><i class="fas fa-check-circle"></i> Track your progress</li>
            </ul>
        </div>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-icon');
    
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

// Email uniqueness check
let emailTimeout;
document.getElementById('email').addEventListener('input', function() {
    const email = this.value.trim();
    const statusEl = document.getElementById('emailStatus');
    
    clearTimeout(emailTimeout);
    
    if (!email) {
        statusEl.innerHTML = '';
        return;
    }
    
    emailTimeout = setTimeout(async () => {
        if (!email.includes('@')) {
            statusEl.innerHTML = '';
            return;
        }
        
        statusEl.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Checking...';
        
        try {
            const response = await fetch(`index.php?route=auth.checkEmail&email=${encodeURIComponent(email)}`);
            const data = await response.json();
            
            if (data.available) {
                statusEl.innerHTML = '<span class="text-success"><i class="fas fa-check-circle"></i> Email is available</span>';
            } else {
                statusEl.innerHTML = '<span class="text-danger"><i class="fas fa-times-circle"></i> ' + data.message + '</span>';
            }
        } catch (error) {
            statusEl.innerHTML = '';
        }
    }, 500);
});

// Password strength indicator
document.getElementById('password').addEventListener('input', function() {
    const password = this.value;
    const fill = document.getElementById('strengthFill');
    const text = document.getElementById('strengthText');
    
    let strength = 0;
    
    if (password.length >= 6) strength++;
    if (password.length >= 8) strength++;
    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
    if (/\d/.test(password)) strength++;
    if (/[^a-zA-Z\d]/.test(password)) strength++;
    
    const levels = [
        { width: '0%', color: '#e2e8f0', text: 'Enter a password' },
        { width: '20%', color: '#ef4444', text: 'Very weak' },
        { width: '40%', color: '#f97316', text: 'Weak' },
        { width: '60%', color: '#eab308', text: 'Fair' },
        { width: '80%', color: '#22c55e', text: 'Good' },
        { width: '100%', color: '#10b981', text: 'Strong' }
    ];
    
    fill.style.width = levels[strength].width;
    fill.style.backgroundColor = levels[strength].color;
    text.textContent = levels[strength].text;
});

// Password match validation
document.getElementById('confirm_password').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmPassword = this.value;
    
    if (confirmPassword && password !== confirmPassword) {
        this.classList.add('is-invalid');
    } else {
        this.classList.remove('is-invalid');
    }
});

// Form validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    
    if (password !== confirmPassword) {
        e.preventDefault();
        showToast('Passwords do not match', 'error');
    }
});
</script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/main.php';
?>
