<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$user = current_user();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    if (!$name || !$email) {
        $error = 'Name and email are required.';
    } elseif ($password && strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } elseif ($password && $password !== $passwordConfirm) {
        $error = 'Passwords do not match.';
    } else {
        $updated = update_user_profile((int) $user['id'], [
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ]);
        if ($updated) {
            $_SESSION['user']['name'] = $name;
            $_SESSION['user']['email'] = $email;
            $message = 'Profile updated successfully.';
            $user = current_user();
        } else {
            $error = 'Could not update profile. Email may already be in use.';
        }
    }
}

$orders = get_orders_by_user((int) $user['id']);
$products = find_product_by_id((int) $orders[0]['product_id'] ?? 0);
$wishlistCount = count(get_wishlist((int) $user['id']));

$userStore = null;
$isStoreOwner = false;
try {
    $userStore = get_full_store_profile((int) $user['id']);
    if ($userStore) {
        $isStoreOwner = true;
        $_SESSION['user']['role'] = 'store_owner';
        $user['role'] = 'store_owner';
    }
} catch (Exception $e) {
    // Stores table may not exist yet
}

require_once __DIR__ . '/../includes/header.php';
?>
<section class="profile-hero">
    <div class="profile-header">
        <div class="profile-avatar">
            <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
        </div>
        <div class="profile-info">
            <h1><?php echo htmlspecialchars($user['name']); ?></h1>
            <p><?php echo htmlspecialchars($user['email']); ?></p>
            <span class="profile-badge"><?php echo ucfirst(str_replace('_', ' ', $user['role'])); ?></span>
        </div>
        <div class="profile-header-actions">
            <?php if ($isStoreOwner): ?>
                <a href="<?php echo BASE_URL; ?>post_create.php" class="post-product-btn">
                    <span class="post-icon">📦</span>
                    <span>Post Product</span>
                </a>
                <a href="<?php echo BASE_URL; ?>my_store.php" class="create-store-btn">
                    <span class="store-icon">🏪</span>
                    <span>My Store</span>
                </a>
            <?php else: ?>
                <a href="<?php echo BASE_URL; ?>create_store.php" class="create-store-btn">
                    <span class="store-icon">🏪</span>
                    <span>Create Store</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section>

<section class="profile-stats">
    <div class="stat-card">
        <span class="stat-number"><?php echo count($orders); ?></span>
        <span class="stat-label">Orders</span>
    </div>
    <div class="stat-card">
        <span class="stat-number"><?php echo $wishlistCount; ?></span>
        <span class="stat-label">Wishlist Items</span>
    </div>
    <div class="stat-card">
        <span class="stat-number"><?php echo date('M Y', strtotime($user['created_at'] ?? 'now')); ?></span>
        <span class="stat-label">Member Since</span>
    </div>
</section>

<div class="profile-grid">
    <section class="profile-section">
        <h2>Account Settings</h2>
        <?php if ($message): ?>
            <div class="auth-alert"
                style="background: rgba(22, 163, 74, 0.1); color: var(--success); border: 1px solid rgba(22, 163, 74, 0.2);">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="auth-alert auth-alert-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" class="profile-form-modern">
            <div class="form-row">
                <label class="auth-field">
                    <span>Full Name</span>
                    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </label>
                <label class="auth-field">
                    <span>Email Address</span>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                </label>
            </div>
            <div class="form-row">
                <label class="auth-field">
                    <span>New Password</span>
                    <input type="password" name="password" placeholder="Leave blank to keep current">
                </label>
                <label class="auth-field">
                    <span>Confirm Password</span>
                    <input type="password" name="password_confirm" placeholder="Confirm new password">
                </label>
            </div>
            <button type="submit" class="btn">Update Profile</button>
        </form>
    </section>

    <section class="profile-section">
        <h2>Recent Orders</h2>
        <?php if (!$orders): ?>
            <p class="empty-state">No orders yet. <a href="<?php echo BASE_URL; ?>products.php">Start shopping!</a></p>
        <?php else: ?>
            <div class="recent-orders">
                <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                    <div class="order-item">
                        <div class="order-details">
                            <strong><?php echo $products['title']; ?></strong>
                            <span><?php echo date('M d, Y', strtotime($order['created_at'])); ?></span>
                            <p style="color:green;"><?php echo htmlspecialchars($order['message'] ?? ''); ?></p>
                        </div>
                        <div class="order-status">
                            <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                <?php echo ucfirst($order['status']); ?>
                            </span>
                            <span class="order-total">Rs. <?php echo number_format($order['total_amount'], 2); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <a href="<?php echo BASE_URL; ?>orders.php" class="btn btn-outline" style="margin-top: 1rem;">View All
                Orders</a>
        <?php endif; ?>
    </section>
</div>

<section class="profile-section quick-links">
    <h2>Quick Links</h2>
    <div class="quick-links-grid">
        <a href="<?php echo BASE_URL; ?>orders.php" class="quick-link">
            <span class="quick-icon">📦</span>
            <span>My Orders</span>
        </a>
        <a href="<?php echo BASE_URL; ?>wishlist.php" class="quick-link">
            <span class="quick-icon">❤️</span>
            <span>Wishlist</span>
        </a>
        <a href="<?php echo BASE_URL; ?>order_tracking.php" class="quick-link">
            <span class="quick-icon">🚚</span>
            <span>Track Order</span>
        </a>
        <a href="<?php echo BASE_URL; ?>posts.php" class="quick-link">
            <span class="quick-icon">💬</span>
            <span>Community</span>
        </a>
    </div>
</section>
<?php require_once __DIR__ . '/../includes/footer.php';
