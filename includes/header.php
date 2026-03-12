<?php
require_once __DIR__ . '/config.php';
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
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" type="image/x-icon" href="/includes/golden_favicon.ico">
    <script defer src="<?php echo SITE_URL; ?>js/app.js"></script>
</head>

<body>
    <header class="site-header" id="siteHeader">
        <div class="container header-inner">
            <div class="mobile-header-bar">
                <a href="<?php echo SITE_URL; ?>index.php" class="logo" aria-label="<?php echo APP_NAME; ?> Home">
                    <span class="logo-icon">🛒</span>
                    <span class="logo-text"><?php echo APP_NAME; ?></span>
                </a>

                <?php if (!$user): ?>
                    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle navigation menu"
                        aria-expanded="false">
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                        <span class="hamburger-line"></span>
                    </button>
                <?php endif; ?>
            </div>

            <div class="mobile-menu-tab" id="mobileMenuTab">
                <?php if ($user): ?>
                    <div class="mobile-dropdown-content">
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
                            <a href="<?php echo ADMIN_URL; ?>dashboard.php" class="dropdown-item dropdown-item-admin">
                                <span class="dropdown-icon">⚙️</span>
                                <span>Admin Dashboard</span>
                            </a>
                        <?php endif; ?>
                        <a href="<?php echo SITE_URL; ?>logout.php" class="dropdown-item dropdown-item-logout">
                            <span class="dropdown-icon">🚪</span>
                            <span>Sign Out</span>
                        </a>
                    </div>
                <?php else: ?>
                    <div class="mobile-login-signup">
                        <a href="<?php echo SITE_URL; ?>login.php" class="mobile-login">Login</a>
                        <a href="<?php echo SITE_URL; ?>register.php" class="mobile-signup">Sign Up</a>
                    </div>
                <?php endif; ?>
            </div>

            <nav class="main-nav" id="mainNav" role="navigation" aria-label="Main navigation">
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
                <?php endif; ?>
            </div>
        </div>
    </header>
    <script>
        const menuToggle = document.getElementById('mobileMenuToggle');
        const mobileMenuTab = document.getElementById('mobileMenuTab');

        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            if (mobileMenuTab.style.display === 'block') {
                mobileMenuTab.style.display = 'none';
            } else {
                mobileMenuTab.style.display = 'block';
            }
        });
    </script>
    <style>
        /* ==========================================
            DARK MODE STYLES
           ========================================== */

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .site-header.dark {
            background: #1a1a2e;
            border-bottom-color: rgba(255, 255, 255, 0.1);
        }

        .site-header.dark .logo-text {
            color: white;
        }

        .site-header.dark .logo-text span {
            color: #ffffff;
        }

        .site-header.dark .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }

        .site-header.dark .nav-link:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .site-header.dark .nav-link.active {
            color: #ffffff;
        }

        .site-header.dark .icon-btn {
            color: rgba(255, 255, 255, 0.8);
        }

        .site-header.dark .icon-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .site-header.dark .dropdown-btn {
            color: white;
        }

        .site-header.scrolled {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.12);
        }

        .site-header.dark.scrolled {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.4);
        }

        .site-header {
            position: sticky;
            z-index: 999;
            background: #ffffff;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 4px 12px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .site-header.dark {
            background: #1a1a2e;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2), 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.875rem 0;
            gap: 2rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            flex-shrink: 0;
        }

        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #1f2937 0%, #111827 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(31, 41, 55, 0.3);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .logo:hover .logo-icon {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(31, 41, 55, 0.4);
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a1a2e;
            letter-spacing: -0.5px;
        }

        .site-header.dark .logo-text {
            color: #ffffff;
        }

        .main-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex: 1;
            justify-content: center;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.5rem 0;
        }

        .nav-icon {
            display: none;
        }

        .mobile-only {
            display: none !important;
        }

        .desktop-only {
            display: flex;
        }

        .mobile-nav-header,
        .mobile-user-info,
        .mobile-nav-actions,
        .nav-section-label {
            display: none;
        }

        .nav-link.mobile-only {
            display: none;
        }

        .main-nav a,
        .nav-link {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.625rem 1rem;
            font-weight: 700;
            font-size: 1.2rem;
            color: #4a5568;
            border-radius: 8px;
            text-decoration: none;
        }

        .site-header.dark .main-nav a,
        .site-header.dark .nav-link {
            color: #a0aec0;
        }

        .main-nav a:hover,
        .nav-link:hover {
            color: #1a1a2e;
            background: #f7f7f8;
        }

        .site-header.dark .main-nav a:hover,
        .site-header.dark .nav-link:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }

        .main-nav a.active,
        .nav-link.active {
            color: #1f2937;
            background: rgba(31, 41, 55, 0.08);
        }

        .main-nav a.active::after,
        .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 20px;
            height: 3px;
            background: #1f2937;
            border-radius: 3px;
        }

        .nav-dropdown {
            position: relative;
        }

        .nav-dropdown-trigger {
            display: flex;
            align-items: center;
            gap: 0.375rem;
            padding: 0.625rem 1rem;
            font-weight: 500;
            font-size: 0.9375rem;
            color: #4a5568;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
            background: none;
            border: none;
        }

        .site-header.dark .nav-dropdown-trigger {
            color: #a0aec0;
        }

        .nav-dropdown-trigger:hover {
            color: #1a1a2e;
            background: #f7f7f8;
        }

        .site-header.dark .nav-dropdown-trigger:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }

        .nav-dropdown-arrow {
            width: 16px;
            height: 16px;
            transition: transform 0.2s ease;
        }

        .nav-dropdown:hover .nav-dropdown-arrow {
            transform: rotate(180deg);
        }

        .nav-dropdown-menu {
            position: absolute;
            top: calc(100% + 8px);
            left: 50%;
            transform: translateX(-50%) translateY(10px);
            min-width: 220px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.12), 0 0 0 1px rgba(0, 0, 0, 0.05);
            padding: 0.5rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.25s ease;
            z-index: 100;
        }

        .site-header.dark .nav-dropdown-menu {
            background: #252540;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        }

        .nav-dropdown:hover .nav-dropdown-menu {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }

        .header-actions {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }

        .header-action-btn {
            position: relative;
            width: 42px;
            height: 42px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f7f7f8;
            border: none;
            border-radius: 10px;
            color: #4a5568;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .site-header.dark .header-action-btn {
            background: rgba(255, 255, 255, 0.08);
            color: #a0aec0;
        }

        .header-action-btn:hover {
            background: #eeeef0;
            color: #1a1a2e;
            transform: translateY(-2px);
        }

        .site-header.dark .header-action-btn:hover {
            background: rgba(255, 255, 255, 0.12);
            color: #ffffff;
        }

        .header-action-btn svg {
            width: 20px;
            height: 20px;
        }

        .dropdown {
            position: relative;
        }

        .dropbtn {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.375rem;
            padding-right: 0.75rem;
            background: #f7f7f8;
            border: none;
            border-radius: 50px;
            color: #4a5568;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .site-header.dark .dropbtn {
            background: rgba(255, 255, 255, 0.08);
            color: #a0aec0;
        }

        .dropbtn:hover {
            background: #eeeef0;
        }

        .site-header.dark .dropbtn:hover {
            background: rgba(255, 255, 255, 0.12);
        }

        .site-header.dark .header-login {
            color: #a0aec0;
        }

        .site-header.dark .header-login:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.08);
        }

        .site-header.dark .theme-toggle {
            background: rgba(255, 255, 255, 0.08);
            color: #fbbf24;
        }

        .site-header.dark .theme-toggle:hover {
            background: rgba(255, 255, 255, 0.12);
        }


        .site-header.dark .theme-toggle .sun-icon {
            display: none;
        }

        .site-header.dark .theme-toggle .moon-icon {
            display: block;
        }

        .site-header.dark .search-input-wrapper input {
            color: #ffffff;
        }

        .site-header.dark .search-input-wrapper {
            background: rgba(255, 255, 255, 0.08);
        }

        .site-header.dark .dropbtn {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.18) 0%, rgba(255, 255, 255, 0.08) 100%);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff;
        }

        .site-header.dark .dropbtn:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.12) 100%);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .site-header.dark .search-container.active {
            background: #1a1a2e;
        }
        

        .mobile-menu-toggle {
            display: inline-flex;
            flex-direction: column;
            justify-content: space-between;
            width: 30px;
            height: 24px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
            z-index: 1000;
        }

        .mobile-menu-toggle .hamburger-line {
            display: block;
            height: 3px;
            width: 100%;
            background-color: #333;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        /* Hidden by default */
        .mobile-menu-tab {
            display: none;
            position: absolute;
            /* appears under hamburger */
            top: 100%;
            /* right below hamburger */
            right: 0;
            /* align to right side of button */
            width: 200px;
            /* small width */
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
            z-index: 1000;
            padding: 0.5rem 0;
        }

        /* Links inside tab */
        .mobile-menu-tab a {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            font-size: 0.95rem;
            color: #333;
            text-decoration: none;
        }

        .mobile-menu-tab a:hover {
            background-color: #f0f0f0;
        }

        .mobile-menu-toggle.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .mobile-menu-toggle.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-toggle.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        .mobile-menu-toggle {
            display: none;
        }

        /* Show on small screens */
        @media (max-width: 768px) {
            .mobile-menu-toggle {
                display: inline-flex;
                flex-direction: column;
                justify-content: space-between;
                width: 30px;
                height: 24px;
                background: transparent;
                border: none;
                cursor: pointer;
                padding: 0;
                z-index: 1000;
            }
        }

        @media (max-width: 768px) {
            .header-inner {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                padding: 1rem 0;
                gap: 0;
            }

            .mobile-header-bar {
                flex: 1;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0;
            }

            .main-nav {
                display: none;
            }

            .header-actions {
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }
        }
    </style>