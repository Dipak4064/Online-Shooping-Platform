<?php
require_once __DIR__ . '/../includes/functions.php';
require_admin();
start_session();
$user = current_user();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo ADMIN_URL; ?>css/styles.css">
    <style>
        /* Admin Header - Professional Design */
        .admin-header {
            background: linear-gradient(to right, #1e3a5f 0%, #2d4a6f 50%, #1e3a5f 100%) !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
        }

        .admin-header .header-inner {
            padding: 0.5rem 0;
        }

        .admin-header .logo {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .admin-header .logo-icon {
            color: #60a5fa;
            font-size: 1.3rem;
        }

        .admin-header .logo-text {
            color: #ffffff;
        }

        .admin-badge {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #ffffff;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-left: 0.5rem;
        }

        /* Admin Nav - Professional */
        .admin-header .main-nav {
            gap: 0.25rem;
            background: transparent;
            padding: 0;
            border: none;
            border-radius: 0;
        }

        .admin-header .main-nav a {
            position: relative;
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            font-weight: 500;
            font-size: 0.9rem;
            color: #e2e8f0;
            border-radius: 8px;
            transition: all 0.25s ease;
        }

        .admin-header .main-nav a::before {
            display: none;
        }

        .admin-header .main-nav a::after {
            content: '';
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background: #60a5fa;
            border-radius: 2px;
            transition: width 0.25s ease;
        }

        .admin-header .main-nav a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transform: none;
            box-shadow: none;
        }

        .admin-header .main-nav a:hover::after {
            width: 60%;
        }

        /* Mobile Header Bar */
        .mobile-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        @media (min-width: 769px) {
            .mobile-header-bar {
                width: auto;
            }

            .admin-mobile-toggle {
                display: none;
            }
        }

        /* Admin mobile menu toggle */
        .admin-mobile-toggle {
            display: none;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 5px;
            width: 44px;
            height: 44px;
            background: rgba(255, 255, 255, 0.08);
            border: none;
            border-radius: 8px;
            cursor: pointer;
            padding: 10px;
            transition: all 0.25s ease;
            z-index: 1002;
        }

        .admin-mobile-toggle:hover {
            background: rgba(96, 165, 250, 0.2);
        }

        .admin-mobile-toggle .hamburger-line {
            width: 22px;
            height: 2.5px;
            background: #ffffff;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .admin-mobile-toggle.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .admin-mobile-toggle.active .hamburger-line:nth-child(2) {
            opacity: 0;
            transform: translateX(-10px);
        }

        .admin-mobile-toggle.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(5px, -5px);
        }

        /* Admin mobile overlay */
        .admin-menu-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .admin-menu-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Admin user info display */
        .admin-user-display {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            padding: 0.5rem 0.9rem;
            background: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
            color: #ffffff;
            font-size: 0.9rem;
            transition: all 0.25s ease;
        }

        .admin-user-display:hover {
            background: rgba(96, 165, 250, 0.2);
        }

        .admin-user-avatar {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: #ffffff;
            font-size: 0.85rem;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .admin-user-email {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-weight: 500;
        }

        .admin-header .header-actions {
            gap: 0.75rem;
        }

        .admin-header .btn {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: #ffffff;
            border-radius: 8px;
            padding: 0.6rem 1.1rem;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
            transition: all 0.25s ease;
        }

        .admin-header .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        /* Mobile nav header */
        .admin-mobile-nav-header {
            display: none;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 1.5rem;
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-mobile-nav-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #232f3e;
        }

        .admin-mobile-nav-close {
            background: rgba(0, 0, 0, 0.2);
            border: none;
            color: #232f3e;
            font-size: 1.25rem;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .admin-mobile-nav-close:hover {
            background: rgba(0, 0, 0, 0.3);
            transform: rotate(90deg);
        }

        /* Mobile user info section */
        .admin-mobile-user-info {
            display: none;
            align-items: center;
            gap: 1rem;
            padding: 1.25rem 1.5rem;
            background: rgba(255, 255, 255, 0.05);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .admin-mobile-user-info .admin-user-avatar {
            width: 48px;
            height: 48px;
            font-size: 1.1rem;
        }

        .admin-mobile-user-details {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
        }

        .admin-mobile-user-name {
            font-weight: 600;
            color: #ffffff;
            font-size: 1rem;
        }

        .admin-mobile-user-email {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.6);
        }

        /* Nav icon styles */
        .admin-nav-icon {
            display: none;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            font-size: 1rem;
        }

        /* Mobile logout in nav */
        .admin-mobile-logout {
            display: none;
            margin: 1rem 1.5rem;
            padding: 0.9rem 1.25rem;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            border-radius: 12px;
            text-align: center;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .admin-mobile-logout:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(239, 68, 68, 0.3);
            color: #ffffff;
        }

        /* Back to store link */
        .admin-back-link {
            display: none;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.7);
            font-size: 0.9rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
            transition: all 0.3s ease;
        }

        .admin-back-link:hover {
            background: rgba(255, 255, 255, 0.05);
            color: var(--primary);
        }

        /* Dashboard cards mobile */
        .admin-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: var(--surface-strong);
            border: 1px solid var(--border);
            border-radius: 1.25rem;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            transition: all 0.3s ease;
            box-shadow: var(--shadow-light);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--gradient-primary);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-medium);
        }

        .stat-card h3 {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }

        .stat-card p {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--secondary);
            margin: 0;
        }

        @media (max-width: 1024px) {
            .admin-user-email {
                display: none;
            }
        }

        @media (max-width: 768px) {

            /* Clean mobile header - logo left, hamburger right */
            .admin-header .header-inner {
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0.85rem 0;
            }

            .admin-header .logo {
                order: 1;
            }

            .admin-mobile-toggle {
                display: flex;
                order: 2;
                margin-left: auto;
            }

            /* Hide desktop elements on mobile */
            .admin-header .header-actions {
                display: none;
            }

            /* Hide admin badge on mobile for cleaner look */
            .admin-header .admin-badge {
                display: none;
            }

            .admin-header .main-nav {
                position: fixed;
                top: 0;
                right: -100%;
                width: 85%;
                max-width: 300px;
                height: 100vh;
                background: linear-gradient(180deg, #131921 0%, #1a2332 50%, #0f172a 100%);
                flex-direction: column;
                align-items: stretch;
                gap: 0;
                padding: 0;
                z-index: 1001;
                transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: -10px 0 40px rgba(0, 0, 0, 0.5);
                overflow-y: auto;
            }

            .admin-header .main-nav.active {
                right: 0;
            }

            .admin-mobile-nav-header {
                display: flex;
            }

            .admin-mobile-user-info {
                display: flex;
            }

            .admin-nav-icon {
                display: flex;
            }

            .admin-header .main-nav a {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1rem 1.5rem;
                color: rgba(255, 255, 255, 0.9);
                font-size: 1rem;
                font-weight: 500;
                border-radius: 0;
                border-left: 3px solid transparent;
                transition: all 0.3s ease;
            }

            .admin-header .main-nav a:hover {
                background: rgba(255, 255, 255, 0.08);
                color: var(--primary);
                border-left-color: var(--primary);
                transform: none;
                box-shadow: none;
            }

            .admin-header .main-nav a::before {
                display: none;
            }

            .admin-mobile-logout {
                display: block;
            }

            .admin-back-link {
                display: flex;
            }



            body.admin-menu-open {
                overflow: hidden;
            }

            .page-header h1 {
                font-size: 1.5rem;
            }

            .admin-stats-grid,
            .categories-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .stat-card,
            .categories-grid .card {
                padding: 1.25rem;
            }

            .stat-card p,
            .categories-grid .card p {
                font-size: 1.35rem;
            }
        }

        @media (max-width: 480px) {
            .admin-header .main-nav {
                width: 100%;
                max-width: 100%;
            }

            .admin-stats-grid,
            .categories-grid {
                grid-template-columns: 1fr;
            }

            .stat-card p,
            .categories-grid .card p {
                font-size: 1.5rem;
            }

            .page-header h1 {
                font-size: 1.35rem;
            }
        }
    </style>
</head>

<body>
    <header class="site-header admin-header">
        <div class="container header-inner">
            <!-- Mobile Header Bar - Logo and Hamburger in same container -->
            <div class="mobile-header-bar">
                <a href="dashboard.php" class="logo">
                    <span class="logo-icon">⚙️</span>
                    <span class="logo-text">Admin Panel</span>
                    <span class="admin-badge">Admin</span>
                </a>

                <!-- Mobile Menu Toggle -->
                <button class="admin-mobile-toggle" id="adminMobileToggle" aria-label="Toggle menu">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>

            <!-- Mobile Menu Overlay -->
            <div class="admin-menu-overlay" id="adminMenuOverlay"></div>

            <nav class="main-nav" id="adminMainNav">
                <div class="admin-mobile-nav-header">
                    <span class="admin-mobile-nav-title">Admin Menu</span>
                    <button class="admin-mobile-nav-close" id="adminNavClose" aria-label="Close menu">✕</button>
                </div>

                <div class="admin-mobile-user-info">
                    <div class="admin-user-avatar"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
                    <div class="admin-mobile-user-details">
                        <span class="admin-mobile-user-name"><?php echo htmlspecialchars($user['name']); ?></span>
                        <span class="admin-mobile-user-email"><?php echo htmlspecialchars($user['email']); ?></span>
                    </div>
                </div>

                <a href="dashboard.php">
                    <span class="admin-nav-icon">📊</span>
                    <span>Overview</span>
                </a>
                <a href="products.php">
                    <span class="admin-nav-icon">📦</span>
                    <span>Products</span>
                </a>
                <a href="categories.php">
                    <span class="admin-nav-icon">📂</span>
                    <span>Categories</span>
                </a>
                <a href="orders.php">
                    <span class="admin-nav-icon">🛒</span>
                    <span>Orders</span>
                </a>
                <a href="customers.php">
                    <span class="admin-nav-icon">👥</span>
                    <span>Customers</span>
                </a>

                <a href="<?php echo SITE_URL; ?>logout.php" class="admin-mobile-logout">
                    🚪 Logout
                </a>

                <a href="<?php echo SITE_URL; ?>index.php" class="admin-back-link">
                    <span class="admin-nav-icon">🏠</span>
                    <span>Back to Store</span>
                </a>
            </nav>

            <div class="header-actions">
                <div class="admin-user-display">
                    <div class="admin-user-avatar"><?php echo strtoupper(substr($user['name'], 0, 1)); ?></div>
                    <span class="admin-user-email"><?php echo htmlspecialchars($user['email']); ?></span>
                </div>
                <a class="btn" href="<?php echo SITE_URL; ?>logout.php">Logout</a>
            </div>
        </div>
    </header>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const adminMobileToggle = document.getElementById('adminMobileToggle');
            const adminNavClose = document.getElementById('adminNavClose');
            const adminMenuOverlay = document.getElementById('adminMenuOverlay');
            const adminMainNav = document.getElementById('adminMainNav');

            function openAdminMenu() {
                adminMobileToggle?.classList.add('active');
                adminMainNav?.classList.add('active');
                adminMenuOverlay?.classList.add('active');
                document.body.classList.add('admin-menu-open');
            }

            function closeAdminMenu() {
                adminMobileToggle?.classList.remove('active');
                adminMainNav?.classList.remove('active');
                adminMenuOverlay?.classList.remove('active');
                document.body.classList.remove('admin-menu-open');
            }

            adminMobileToggle?.addEventListener('click', () => {
                if (adminMainNav?.classList.contains('active')) {
                    closeAdminMenu();
                } else {
                    openAdminMenu();
                }
            });

            adminNavClose?.addEventListener('click', closeAdminMenu);
            adminMenuOverlay?.addEventListener('click', closeAdminMenu);

            // Close on link click
            adminMainNav?.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth <= 768) {
                        closeAdminMenu();
                    }
                });
            });

            // Close on resize
            window.addEventListener('resize', () => {
                if (window.innerWidth > 768) {
                    closeAdminMenu();
                }
            });

            // Close on ESC
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && adminMainNav?.classList.contains('active')) {
                    closeAdminMenu();
                }
            });
        });
    </script>

    <main class="site-content">
        <div class="container">