<?php
require_once __DIR__ . '/../includes/functions.php';
start_session();

if (current_user()) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name && $email && $password) {
        $created = register_user([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);

        if ($created) {
            send_welcome_email($email, $name);

            $user = authenticate_user($email, $password);
            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: index.php');
                exit;
            }
        } else {
            $error = 'Account already exists.';
        }
    } else {
        $error = 'All fields are required.';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account - <?php echo APP_NAME; ?></title>
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
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" />
                        <line x1="3" y1="6" x2="21" y2="6" />
                        <path d="M16 10a4 4 0 0 1-8 0" />
                    </svg>
                    <span><?php echo APP_NAME; ?></span>
                </a>

                <!-- Header -->
                <div class="auth-header-modern">
                    <h1>Create your account</h1>
                    <p>Let's get started with your 30 days free trial</p>
                </div>

                <!-- Error Message -->
                    <?php if ($error): ?>
                    <div class="auth-error-modern"><?php echo htmlspecialchars($error); ?></div>
                    <?php endif; ?>

                <!-- Register Form -->
                <form method="post" class="auth-form-modern">
                    <div class="form-group-modern">
                        <label>Name*</label>
                        <input type="text" name="name" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group-modern">
                        <label>Email*</label>
                        <input type="email" name="email" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group-modern">
                        <label>Password*</label>
                        <input type="password" name="password" placeholder="Enter your password" required>
                    </div>
                    <label class="auth-checkbox">
                        <input type="checkbox" required>
                        <span>I agree to all Term, Privacy Policy and Fees</span>
                    </label>
                    <button type="submit" class="btn-auth-submit">Sign Up</button>
                </form>

                <!-- Footer -->
                <p class="auth-switch-link">
                    Already have an account? <a href="login.php">Log in</a>
                </p>
            </div>
        </div>

        <!-- Right Side - Image -->
        <div class="auth-image-side">
            <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&h=1000&fit=crop" alt="Furniture">
            <div class="auth-image-overlay">
                <div class="auth-image-content">
                    <h2>Discovering the Best Products for Your Home</h2>
                    <p>Our practice is designing complete environments with exceptional products and place in special
                        situations.</p>
                    <div class="auth-badges">
                        <span class="auth-badge">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                                <polyline points="22,4 12,14.01 9,11.01" />
                            </svg>
                            100% Guarantee
                        </span>
                        <span class="auth-badge">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2">
                                <rect x="1" y="3" width="15" height="13" />
                                <polygon points="16,8 20,8 23,11 23,16 16,16 16,8" />
                                <circle cx="5.5" cy="18.5" r="2.5" />
                                <circle cx="18.5" cy="18.5" r="2.5" />
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