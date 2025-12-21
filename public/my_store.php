<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$user = current_user();
$store = get_store_by_user((int) $user['id']);

if (!$store) {
    header('Location: ' . BASE_URL . 'create_store.php');
    exit;
}

$message = '';
if (isset($_GET['created'])) {
    $message = 'Congratulations! Your store has been created successfully. You are now a Store Owner!';
}

require_once __DIR__ . '/../includes/header.php';
?>

<section class="my-store-hero">
    <div class="container">
        <div class="store-header">
            <div class="store-logo">
                <?php if ($store['logo']): ?>
                    <img src="<?php echo ROOT_URL . 'public/' . htmlspecialchars($store['logo']); ?>"
                        alt="<?php echo htmlspecialchars($store['name']); ?>">
                <?php else: ?>
                    <span class="store-logo-placeholder">🏪</span>
                <?php endif; ?>
            </div>
            <div class="store-info">
                <h1><?php echo htmlspecialchars($store['name']); ?></h1>
                <p class="store-slug">@<?php echo htmlspecialchars($store['slug']); ?></p>
                <span class="store-badge">Store Owner</span>
            </div>
        </div>
    </div>
</section>

<?php if ($message): ?>
    <div class="container">
        <div class="auth-alert"
            style="background: rgba(22, 163, 74, 0.1); color: var(--success); border: 1px solid rgba(22, 163, 74, 0.2); margin-top: 1rem;">
            <?php echo htmlspecialchars($message); ?>
        </div>
    </div>
<?php endif; ?>

<section class="my-store-content">
    <div class="container">
        <div class="store-stats">
            <div class="stat-card">
                <span class="stat-number">0</span>
                <span class="stat-label">Products</span>
            </div>
            <div class="stat-card">
                <span class="stat-number">0</span>
                <span class="stat-label">Orders</span>
            </div>
            <div class="stat-card">
                <span class="stat-number">0</span>
                <span class="stat-label">Reviews</span>
            </div>
            <div class="stat-card">
                <span class="stat-number"><?php echo date('M Y', strtotime($store['created_at'])); ?></span>
                <span class="stat-label">Member Since</span>
            </div>
        </div>

        <div class="store-details-card">
            <h2>Store Details</h2>
            <div class="store-details-grid">
                <div class="detail-item">
                    <strong>Description</strong>
                    <p><?php echo $store['description'] ? htmlspecialchars($store['description']) : 'No description added'; ?>
                    </p>
                </div>
                <div class="detail-item">
                    <strong>Contact Email</strong>
                    <p><?php echo htmlspecialchars($store['email'] ?: $user['email']); ?></p>
                </div>
                <div class="detail-item">
                    <strong>Phone</strong>
                    <p><?php echo $store['phone'] ? htmlspecialchars($store['phone']) : 'Not provided'; ?></p>
                </div>
                <div class="detail-item">
                    <strong>Address</strong>
                    <p><?php echo $store['address'] ? htmlspecialchars($store['address']) : 'Not provided'; ?></p>
                </div>
            </div>
        </div>

        <div class="post-product-section">
            <div class="post-product-header">
                <span class="post-product-icon">📦</span>
                <h2>Post a New Product</h2>
            </div>
            <form class="post-product-form" action="<?php echo BASE_URL; ?>store_add_product.php" method="get">
                <div class="post-product-input-group">
                    <input type="text" name="product_idea" placeholder="What product do you want to sell today?"
                        class="post-product-input">
                    <button type="submit" class="post-product-btn">Post Product</button>
                </div>
                <div class="post-product-quick-options">
                    <a href="<?php echo BASE_URL; ?>store_add_product.php" class="quick-option">
                        <span>📸</span> Add Photos
                    </a>
                    <a href="<?php echo BASE_URL; ?>store_add_product.php" class="quick-option">
                        <span>🏷️</span> Set Price
                    </a>
                    <a href="<?php echo BASE_URL; ?>store_add_product.php" class="quick-option">
                        <span>📂</span> Choose Category
                    </a>
                </div>
            </form>
        </div>

        <div class="store-actions">
            <h2>Quick Actions</h2>
            <div class="actions-grid">
                <a href="<?php echo BASE_URL; ?>store_add_product.php" class="action-card">
                    <span class="action-icon">📦</span>
                    <span class="action-title">Add Product</span>
                    <span class="action-desc">List a new product for sale</span>
                </a>
                <a href="#" class="action-card">
                    <span class="action-icon">📋</span>
                    <span class="action-title">Manage Products</span>
                    <span class="action-desc">View and edit your products</span>
                </a>
                <a href="#" class="action-card">
                    <span class="action-icon">🛒</span>
                    <span class="action-title">View Orders</span>
                    <span class="action-desc">Check customer orders</span>
                </a>
                <a href="#" class="action-card">
                    <span class="action-icon">⚙️</span>
                    <span class="action-title">Store Settings</span>
                    <span class="action-desc">Update store information</span>
                </a>
            </div>
        </div>
    </div>
</section>

<style>
    .my-store-hero {
        background: linear-gradient(135deg, #e8e8ed 0%, #d8d8e0 50%, #c8c8d5 100%);
        color: #3d3d4a;
        padding: 3rem 0;
        margin: 1rem;
        border-radius: 1.5rem;
    }

    .store-header {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        padding-left: 2rem;
    }

    .store-logo {
        width: 100px;
        height: 100px;
        border-radius: 1rem;
        background: rgba(255, 255, 255, 0.85);
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 3px solid rgba(255, 255, 255, 0.95);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        flex-shrink: 0;
    }

    .store-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .store-logo-placeholder {
        font-size: 3rem;
    }

    .store-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .store-info h1 {
        margin: 0;
        font-size: 1.75rem;
        color: #2d2d3a;
    }

    .store-slug {
        margin: 0.25rem 0 0.5rem;
        color: #5a5a6a;
    }

    .store-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: rgba(255, 255, 255, 0.7);
        color: #4a4a5a;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        border: 1px solid rgba(0, 0, 0, 0.08);
    }

    .my-store-content {
        padding: 2rem 0;
    }

    .store-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .store-details-card {
        background: linear-gradient(135deg, #f8f4f9 0%, #f0e6f3 50%, #e8dce8 100%);
        border: 1px solid rgba(180, 160, 180, 0.3);
        border-radius: 1.25rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 20px rgba(150, 120, 150, 0.1);
    }

    .store-details-card h2 {
        margin: 0 0 1rem;
        font-size: 1.25rem;
        color: #4a3f4a;
    }

    .store-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .detail-item strong {
        display: block;
        font-size: 0.85rem;
        color: #8a7a8a;
        margin-bottom: 0.25rem;
    }

    .detail-item p {
        margin: 0;
        color: #3d343d;
    }

    .store-actions h2 {
        margin: 0 0 1rem;
        font-size: 1.25rem;
    }

    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .action-card {
        background: var(--surface-strong);
        border: 1px solid var(--border);
        border-radius: 1rem;
        padding: 1.5rem;
        text-decoration: none;
        color: var(--text-primary);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .action-card:hover {
        border-color: var(--primary);
        transform: translateY(-2px);
        box-shadow: var(--shadow-medium);
    }

    .action-icon {
        font-size: 2rem;
    }

    .action-title {
        font-weight: 600;
        font-size: 1.1rem;
    }

    .action-desc {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    /* Post Product Section */
    .post-product-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border: 1px solid #dee2e6;
        border-radius: 1.25rem;
        padding: 1.5rem 1.5rem 1.5rem 2rem;
        margin-bottom: 2rem;
        position: relative;
    }

    .post-product-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .post-product-header h2 {
        margin: 0;
        font-size: 1.25rem;
        color: #2d2d3a;
    }

    .post-product-icon {
        font-size: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .post-product-form {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .post-product-input-group {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: #ffffff;
        border: 2px solid #e0e0e0;
        border-radius: 2rem;
        padding: 0.5rem 0.5rem 0.5rem 1.5rem;
        transition: border-color 0.3s ease;
    }

    .post-product-input-group:focus-within {
        border-color: #6c757d;
    }

    .post-product-input {
        flex: 1;
        border: none;
        outline: none;
        font-size: 1rem;
        background: transparent;
        color: #333;
    }

    .post-product-input::placeholder {
        color: #999;
    }

    .post-product-btn {
        background: linear-gradient(135deg, #4a4a5a 0%, #3d3d4a 100%);
        color: #fff;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 1.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .post-product-btn:hover {
        background: linear-gradient(135deg, #5a5a6a 0%, #4a4a5a 100%);
        transform: scale(1.02);
    }

    .post-product-quick-options {
        display: flex;
        gap: 1rem;
        padding-left: 0.5rem;
    }

    .quick-option {
        display: flex;
        align-items: center;
        gap: 0.35rem;
        color: #5a5a6a;
        text-decoration: none;
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }

    .quick-option:hover {
        background: rgba(0, 0, 0, 0.05);
        color: #3d3d4a;
    }

    @media (max-width: 600px) {
        .store-header {
            flex-direction: column;
            text-align: center;
        }

        .post-product-input-group {
            flex-direction: column;
            border-radius: 1rem;
            padding: 1rem;
        }

        .post-product-input {
            width: 100%;
            text-align: center;
        }

        .post-product-btn {
            width: 100%;
        }

        .post-product-quick-options {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>