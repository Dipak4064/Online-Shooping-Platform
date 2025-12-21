<?php
require_once __DIR__ . '/../includes/functions.php';
start_session();

if (current_user()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $user = authenticate_user($email, $password);
    if ($user) {
        $_SESSION['user'] = $user;
        header('Location: index.php');
        exit;
    }

    $error = 'Invalid credentials';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo APP_NAME; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/styles.css">
</head>
<body class="auth-page">

<div class="auth-split-container">
    <!-- Left Side - Form -->
    <div class="auth-form-side">
        <div class="auth-form-wrapper">
            <!-- Logo -->
            <a href="<?php echo BASE_URL; ?>index.php" class="auth-logo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                    <line x1="3" y1="6" x2="21" y2="6"/>
                    <path d="M16 10a4 4 0 0 1-8 0"/>
                </svg>
                <span><?php echo APP_NAME; ?></span>
            </a>

            <!-- Header -->
            <div class="auth-header-modern">
                <h1>Welcome back</h1>
                <p>Enter your credentials to access your account</p>
            </div>

            <!-- Google Login Button -->
            <button type="button" class="btn-google-login">
                <svg width="18" height="18" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                <span>Login with Google</span>
            </button>

            <div class="auth-divider">
                <span>or</span>
            </div>

            <!-- Error Message -->
            <?php if ($error): ?>
                <div class="auth-error-modern"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="post" class="auth-form-modern">
                <div class="form-group-modern">
                    <label>Email*</label>
                    <input type="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="form-group-modern">
                    <label>Password*</label>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn-auth-submit">Sign In</button>
            </form>

            <!-- Footer -->
            <p class="auth-switch-link">
                Don't have an account? <a href="register.php">Sign up</a>
            </p>
        </div>
    </div>

    <!-- Right Side - Image -->
    <div class="auth-image-side">
        <img src="https://images.unsplash.com/photo-1441984904996-e0b6ba687e04?w=800&h=1000&fit=crop" alt="Shopping">
        <div class="auth-image-overlay">
            <div class="auth-image-content">
                <h2>Discover Amazing Products</h2>
                <p>Shop the latest trends with fast delivery and secure payments. Join thousands of happy customers today.</p>
                <div class="auth-badges">
                    <span class="auth-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                            <polyline points="22,4 12,14.01 9,11.01"/>
                        </svg>
                        100% Guarantee
                    </span>
                    <span class="auth-badge">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="1" y="3" width="15" height="13"/>
                            <polygon points="16,8 20,8 23,11 23,16 16,16 16,8"/>
                            <circle cx="5.5" cy="18.5" r="2.5"/>
                            <circle cx="18.5" cy="18.5" r="2.5"/>
                        </svg>
                        Free Delivery
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
