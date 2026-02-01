<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($title ?? 'College Sports Event Management') ?> - Workshop 8</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="index.php?route=sports.index" class="nav-brand">
                <i class="fas fa-trophy"></i>
                <span>SportHub</span>
            </a>
            
            <button class="nav-toggle" id="navToggle">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="nav-menu" id="navMenu">
                <a href="index.php?route=sports.index" class="nav-link">
                    <i class="fas fa-calendar-alt"></i> Events
                </a>
                
                <?php if ($auth_user): ?>
                    <a href="index.php?route=auth.dashboard" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                <?php endif; ?>
                
                <a href="index.php?route=participants.register" class="nav-link">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            </div>
            
            <div class="nav-auth">
                <?php if ($auth_user): ?>
                    <div class="user-dropdown">
                        <button class="user-btn" id="userDropdownBtn">
                            <div class="user-avatar"><?= strtoupper(substr($auth_user['name'], 0, 1)) ?></div>
                            <span class="user-name"><?= e($auth_user['name']) ?></span>
                            <i class="fas fa-chevron-down"></i>
                        </button>
                        <div class="dropdown-menu" id="userDropdownMenu">
                            <a href="index.php?route=auth.dashboard" class="dropdown-item">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                            <a href="index.php?route=sports.create" class="dropdown-item">
                                <i class="fas fa-plus-circle"></i> Create Event
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="index.php?route=auth.logout" class="dropdown-item text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="index.php?route=auth.login" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                    <a href="index.php?route=auth.register" class="btn btn-primary btn-sm">
                        <i class="fas fa-user-plus"></i> Sign Up
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Toast Notifications -->
    <div class="toast-container" id="toastContainer">
        <?php if ($success): ?>
            <div class="toast toast-success" data-auto-dismiss>
                <i class="fas fa-check-circle"></i>
                <span><?= e($success) ?></span>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>
        
        <?php if ($error): ?>
            <div class="toast toast-error" data-auto-dismiss>
                <i class="fas fa-exclamation-circle"></i>
                <span><?= e($error) ?></span>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        <?php endif; ?>
    </div>

    <!-- Main Content -->
    <main class="main-content">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="footer-logo">
                        <i class="fas fa-trophy"></i>
                        <span>SportHub</span>
                    </div>
                    <p>College Sports Event Management System - Workshop 8</p>
                    <p>Organize, manage, and participate in college sports events.</p>
                </div>
                
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <a href="index.php?route=sports.index">All Events</a>
                    <a href="index.php?route=participants.register">Register</a>
                    <?php if ($auth_user): ?>
                        <a href="index.php?route=sports.create">Create Event</a>
                        <a href="index.php?route=auth.dashboard">Dashboard</a>
                    <?php else: ?>
                        <a href="index.php?route=auth.login">Login</a>
                        <a href="index.php?route=auth.register">Sign Up</a>
                    <?php endif; ?>
                </div>
                
                <div class="footer-links">
                    <h4>Sports</h4>
                    <a href="index.php?route=sports.index">Basketball</a>
                    <a href="index.php?route=sports.index">Football</a>
                    <a href="index.php?route=sports.index">Volleyball</a>
                    <a href="index.php?route=sports.index">Cricket</a>
                </div>
                
                <div class="footer-contact">
                    <h4>Contact</h4>
                    <p><i class="fas fa-envelope"></i> sports@college.edu</p>
                    <p><i class="fas fa-phone"></i> (123) 456-7890</p>
                    <p><i class="fas fa-map-marker-alt"></i> College Campus</p>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> SportHub - Workshop 8. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Delete Confirmation Modal -->
    <div class="modal" id="deleteModal">
        <div class="modal-backdrop" onclick="closeDeleteModal()"></div>
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle text-danger"></i> Confirm Delete</h3>
                <button class="modal-close" onclick="closeDeleteModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <p id="deleteModalMessage">Are you sure you want to delete this item?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeDeleteModal()">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?= e($csrf_token) ?>">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="assets/js/app.js"></script>
</body>
</html>
