<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$user = current_user();
$storeProfile = get_full_store_profile((int) $user['id']);

if (!$storeProfile) {
    header('Location: ' . BASE_URL . 'create_store.php');
    exit;
}

// Shortcut variable for readability
$store = $storeProfile['details'];

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
    <div class="container mt-3">
        <div class="auth-alert alert-success p-3 rounded">
            <?php echo htmlspecialchars($message); ?>
        </div>
    </div>
<?php endif; ?>

<section class="my-store-content">
    <div class="container">
        <div class="store-stats">
            <div class="stat-card" onclick="toggleProductTable()" style="cursor: pointer;">
                <span class="stat-number"><?php echo $storeProfile['count']; ?></span>
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

        <div class="store-details-card mb-4">
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
                    <strong>Phone/Address</strong>
                    <p><?php echo htmlspecialchars($store['phone'] ?: 'N/A'); ?> |
                        <?php echo htmlspecialchars($store['address'] ?: 'N/A'); ?>
                    </p>
                </div>
            </div>
        </div>

        <div id="product-table-section" style="display: none; margin-bottom: 2rem;">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold">Product Inventory</h3>
                <button onclick="toggleProductTable()" class="close-btn-simple">✕ Close</button>
            </div>

            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($storeProfile['products'])): ?>
                        <?php foreach ($storeProfile['products'] as $index => $product): ?>
                            <tr>
                                <td><?php echo $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($product['title']); ?></td>
                                <td><?php echo htmlspecialchars($product['product_type']); ?></td>
                                <td>Rs. <?php echo number_format($product['price'], 2); ?></td>
                                <td class="text-center">
                                    <a href="product_view.php?id=<?= $product['id'] ?>" class="action-link link-view">View</a>
                                    <span class="divider">|</span>
                                    <a href="edit_product.php?id=<?= $product['id'] ?>" class="action-link link-edit">Edit</a>
                                    <span class="divider">|</span>
                                    <a href="delete_product.php?id=<?= $product['id'] ?>" class="action-link link-delete"
                                        onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center py-3">No products found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="store-actions">
            <h2>Quick Actions</h2>
            <div class="actions-grid">
                <a href="<?php echo BASE_URL; ?>post_create.php" class="action-card">
                    <span class="action-icon">📦</span>
                    <span class="action-title">Add Product</span>
                    <span class="action-desc">List a new product for sale</span>
                </a>
                <a href="javascript:void(0)" onclick="toggleProductTable()" class="action-card">
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
    /* Hero & Header */
    .my-store-hero {
        background: linear-gradient(135deg, #e8e8ed 0%, #d8d8e0 100%);
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
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .store-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .store-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        background: rgba(255, 255, 255, 0.7);
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Layout & Cards */
    .my-store-content {
        padding: 2rem 0;
    }

    .store-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: #fff;
        padding: 1.5rem;
        border-radius: 1rem;
        text-align: center;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .stat-number {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        color: #2d2d3a;
    }

    .store-details-card {
        background: #f8f4f9;
        border-radius: 1.25rem;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }

    .store-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    /* Table Styles (Simplified) */
    .table {
        width: 100%;
        border-collapse: collapse;
        background: #fff;
        border: 1px solid #dee2e6;
        margin-top: 10px;
    }

    .table th,
    .table td {
        padding: 0.75rem;
        border: 1px solid #dee2e6;
        text-align: left;
    }

    .thead-dark th {
        color: #fff;
        background-color: #343a40;
        border-color: #454d55;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.05);
    }

    /* Simple Action Links */
    .action-link {
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .action-link:hover {
        text-decoration: underline;
    }

    .link-view {
        color: #007bff;
    }

    .link-edit {
        color: #28a745;
    }

    .link-delete {
        color: #dc3545;
    }

    .divider {
        color: #ccc;
        margin: 0 5px;
    }

    .close-btn-simple {
        background: none;
        border: 1px solid #ccc;
        padding: 4px 10px;
        border-radius: 4px;
        cursor: pointer;
        color: #666;
        font-size: 0.8rem;
    }

    .close-btn-simple:hover {
        background: #f5f5f5;
    }

    /* Action Grid */
    .actions-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .action-card {
        background: #fff;
        border: 1px solid #eee;
        border-radius: 1rem;
        padding: 1.5rem;
        text-decoration: none;
        color: inherit;
        transition: 0.3s;
    }

    .action-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border-color: #4a4a5a;
    }

    .action-icon {
        font-size: 2rem;
        display: block;
        margin-bottom: 0.5rem;
    }

    .action-title {
        font-weight: 600;
        display: block;
    }

    .action-desc {
        font-size: 0.85rem;
        color: #666;
    }

    /* Helpers */
    .mt-3 {
        margin-top: 1rem;
    }

    .mb-4 {
        margin-bottom: 1.5rem;
    }

    .text-center {
        text-align: center;
    }

    .d-flex {
        display: flex;
    }

    .justify-content-between {
        justify-content: space-between;
    }

    .align-items-center {
        align-items: center;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
</style>

<script>
    function toggleProductTable() {
        const table = document.getElementById('product-table-section');
        const isHidden = table.style.display === 'none';
        table.style.display = isHidden ? 'block' : 'none';
        if (isHidden) {
            table.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>