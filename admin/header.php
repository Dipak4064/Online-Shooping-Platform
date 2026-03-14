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
        :root {
            --primary-color: #2c3e50;
            --accent-color: #3498db;
            --bg-light: #f4f7f6;
            --white: #ffffff;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            --border-color: #e2e8f0;
            --text-muted: #64748b;
        }

        body {
            background-color: var(--bg-light);
            margin: 0;
            font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: var(--primary-color);
            font-size: 1rem;
        }

        .container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .admin-header {
            background: var(--white) !important;
            box-shadow: var(--shadow) !important;
            border-bottom: 1px solid var(--border-color);
            width: 100%;
        }

        a span {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 90px;
        }

        .mobile-header-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .admin-header .logo {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 1.75rem;
            text-decoration: none;
            white-space: nowrap;
        }

        .admin-header .logo-text {
            color: var(--primary-color);
            font-size: 2rem;
            font-weight: 700;
        }

        .admin-badge {
            background: #ebf4ff;
            color: var(--accent-color);
            font-size: 1.25rem;
            font-weight: 700;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            text-transform: uppercase;
            border: 1px solid #d1e9ff;
        }

        .admin-header .main-nav {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .admin-header .main-nav a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 1rem;
            font-weight: 500;
            padding: 0.5rem 0;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .admin-header .main-nav a:hover {
            color: var(--accent-color);
        }

        .admin-nav-icon {
            font-size: 1.5rem;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .admin-header .btn {
            background: var(--primary-color);
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            text-decoration: none;
            font-size: 1.25rem;
            font-weight: 600;
            transition: background 0.2s;
        }

        .admin-header .btn:hover {
            background: #1a252f;
        }

        .admin-mobile-toggle,
        .admin-mobile-nav-header,
        .admin-mobile-user-info,
        .admin-mobile-logout,
        .admin-back-link {
            display: none;
        }

        @media (max-width: 992px) {
            .admin-header .main-nav {
                gap: 1rem;
            }

            .admin-user-email {
                display: none;
            }
        }

        @media (max-width: 1500px) {
            .admin-header .main-nav {
                display: none;
            }

            .mobile-header-bar {
                width: 100%;
            }

            .header-actions {
                display: none;
            }

            .admin-mobile-toggle {
                display: flex;
                flex-direction: column;
                gap: 4px;
                background: none;
                border: 1px solid var(--border-color);
                padding: 8px;
                border-radius: 6px;
                cursor: pointer;
                justify-content: space-between;
            }

            .hamburger-line {
                width: 20px;
                height: 2px;
                background: var(--primary-color);
            }

            .admin-header .main-nav.active {
                display: flex;
                flex-direction: column;
                position: fixed;
                top: 0;
                right: 0;
                width: 280px;
                height: 100vh;
                background: white;
                z-index: 1001;
                padding: 2rem;
                box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1);
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
    <header class="site-header admin-header">
        <div class="container header-inner">
            <div class="mobile-header-bar">
                <a href="dashboard.php" class="logo">
                    <span class="logo-icon">⚙️</span>
                    <span class="logo-text">Admin Panel</span>
                    <span class="admin-badge">Admin</span>
                </a>

                <button class="admin-mobile-toggle" id="adminMobileToggle" aria-label="Toggle menu">
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                    <span class="hamburger-line"></span>
                </button>
            </div>

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


                <a href="stores.php">
                    <span class="admin-nav-icon">🏪</span>
                    <span>Store</span>
                </a>

                <a href="<?php echo SITE_URL; ?>index.php" class="admin-back-link">
                    <span class="admin-nav-icon">🏠</span>
                    <span>Back to Store</span>
                </a>
            </nav>

            <div class="header-actions">
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