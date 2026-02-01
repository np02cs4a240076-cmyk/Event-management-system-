<?php
$title = 'Login';
ob_start();
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <div class="auth-icon">
                <i class="fas fa-user-lock"></i>
            </div>
            <h1>Welcome Back</h1>
            <p>Sign in to your account to continue</p>
        </div>
        
        <form action="index.php?route=auth.authenticate" method="POST" class="auth-form" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
            
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i> Email Address
                </label>
                <input type="email" id="email" name="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : '' ?>" 
                       placeholder="Enter your email" required autofocus>
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?= e($errors['email']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock"></i> Password
                </label>
                <div class="password-input-wrapper">
                    <input type="password" id="password" name="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : '' ?>" 
                           placeholder="Enter your password" required>
                    <button type="button" class="password-toggle" onclick="togglePassword('password')">
                        <i class="fas fa-eye" id="password-icon"></i>
                    </button>
                </div>
                <?php if (isset($errors['password'])): ?>
                    <div class="invalid-feedback"><?= e($errors['password']) ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group form-check">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" class="form-checkbox">
                    <span class="checkmark"></span>
                    Remember me
                </label>
            </div>
            
            <button type="submit" class="btn btn-primary btn-block btn-lg">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>
        
        <div class="auth-footer">
            <p>Don't have an account? <a href="index.php?route=auth.register">Create one</a></p>
        </div>
        
        <div class="auth-divider">
            <span>OR</span>
        </div>
        
        <a href="index.php?route=sports.index" class="btn btn-outline btn-block">
            <i class="fas fa-arrow-left"></i> Browse Events Without Login
        </a>
    </div>
    
    <div class="auth-sidebar">
        <div class="auth-sidebar-content">
            <h2><i class="fas fa-trophy"></i> SportHub</h2>
            <h3>College Sports Event Management</h3>
            <ul class="feature-list">
                <li><i class="fas fa-check-circle"></i> Organize sports events</li>
                <li><i class="fas fa-check-circle"></i> Register players & teams</li>
                <li><i class="fas fa-check-circle"></i> Track upcoming matches</li>
                <li><i class="fas fa-check-circle"></i> Manage participants</li>
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
</script>

<?php
$content = ob_get_clean();
include dirname(__DIR__) . '/layouts/main.php';
?>
