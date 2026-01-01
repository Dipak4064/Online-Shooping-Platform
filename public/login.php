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
