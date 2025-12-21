<?php
require_once __DIR__ . '/functions.php';
start_session();
$user = current_user();
$cartItems = get_cart_items();
$cartCount = array_sum(array_column($cartItems, 'quantity'));
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>css/styles.css">
    <script defer src="<?php echo SITE_URL; ?>js/app.js"></script>
</head>

<body>
    <header class="site-header" id="siteHeader">
        <div class="container header-inner">
            <!-- Mobile Header Bar - Logo and Hamburger in same container -->
            <div class="mobile-header-bar">
                <a href="<?php echo SITE_URL; ?>index.php" class="logo" aria-label="<?php echo APP_NAME; ?> Home">
                    <span class="logo-icon">🛒</span>
                    <span class="logo-text"><?php echo APP_NAME; ?></span>
                </a>

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle navigation menu"
                    aria-expanded="false">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>

            <!-- Mobile Menu Overlay -->
            <div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

            <!-- Navigation (Desktop + Mobile) -->
            <nav class="main-nav" id="mainNav" role="navigation" aria-label="Main navigation">
                <div class="mobile-nav-header">
                    <span class="mobile-nav-title"><?php echo APP_NAME; ?></span>
                    <button class="mobile-nav-close" id="mobileNavClose" aria-label="Close menu">✕</button>
                </div>

                <?php if ($user): ?>
                    <div class="mobile-user-info">
                        <div class="mobile-user-avatar"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
                        <div class="mobile-user-details">
                            <span class="mobile-user-name"><?php echo htmlspecialchars($user['name']); ?></span>
                            <span class="mobile-user-email"><?php echo htmlspecialchars($user['email']); ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="nav-links">
                    <a href="<?php echo SITE_URL; ?>index.php" class="nav-link">Home</a>
                    <a href="<?php echo SITE_URL; ?>products.php" class="nav-link">Collection</a>
                    <a href="<?php echo SITE_URL; ?>about.php" class="nav-link">About</a>

                    <?php if ($user): ?>
                        <a href="<?php echo SITE_URL; ?>cart.php" class="nav-link mobile-only">Cart
                            (<?php echo $cartCount; ?>)</a>
                        <a href="<?php echo SITE_URL; ?>orders.php" class="nav-link mobile-only">My Orders</a>
                        <?php if (is_admin()): ?>
                            <a href="<?php echo ADMIN_URL; ?>dashboard.php" class="nav-link mobile-only">Admin</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </nav>

            <div class="header-actions">
                <!-- Theme Toggle -->
                <button class="theme-toggle" id="themeToggle" aria-label="Toggle dark mode">
                    <svg class="sun-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg class="moon-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                <?php if ($user): ?>
                    <div class="dropdown">
                        <button class="dropbtn" aria-haspopup="true" aria-expanded="false">
                            <span class="dropbtn-avatar"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></span>
                            <span class="dropbtn-name"><?php echo htmlspecialchars($user['name']); ?></span>
                            <svg class="dropbtn-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="dropdown-content">
                            <div class="dropdown-header">
                                <div class="dropdown-avatar"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
                                <div class="dropdown-user-info">
                                    <span class="dropdown-user-name"><?php echo htmlspecialchars($user['name']); ?></span>
                                    <span class="dropdown-user-email"><?php echo htmlspecialchars($user['email']); ?></span>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo SITE_URL; ?>profile.php" class="dropdown-item">
                                <span class="dropdown-icon">👤</span>
                                <span>My Profile</span>
                            </a>
                            <a href="<?php echo SITE_URL; ?>orders.php" class="dropdown-item">
                                <span class="dropdown-icon">📦</span>
                                <span>My Orders</span>
                            </a>
                            <a href="<?php echo SITE_URL; ?>wishlist.php" class="dropdown-item">
                                <span class="dropdown-icon">❤️</span>
                                <span>Wishlist</span>
                            </a>
                            <?php if (is_admin()): ?>
                                <div class="dropdown-divider"></div>
                                <a href="<?php echo ADMIN_URL; ?>dashboard.php" class="dropdown-item dropdown-item-admin">
                                    <span class="dropdown-icon">⚙️</span>
                                    <span>Admin Dashboard</span>
                                </a>
                            <?php endif; ?>
                            <div class="dropdown-divider"></div>
                            <a href="<?php echo SITE_URL; ?>logout.php" class="dropdown-item dropdown-item-logout">
                                <span class="dropdown-icon">🚪</span>
                                <span>Sign Out</span>
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?php echo SITE_URL; ?>login.php" class="header-login">Login</a>
                    <a class="header-signup" href="<?php echo SITE_URL; ?>register.php">Sign Up</a>
                    <!-- Mobile login icon for non-logged in users -->
                    <a href="<?php echo SITE_URL; ?>login.php" class="mobile-login-icon" aria-label="Login">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <main class="site-content">
        <div class="container">