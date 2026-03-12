<?php
// 1. Setup & Error Handling
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/functions.php';

// 2. Data Fetching
$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: 0;
$product = get_product_by_id($productId);

session_start();

if (!$product) {
    // Better user experience than 'die'
    header("Location: my_store.php?error=product_not_found");
    exit;
}

require_once __DIR__ . '/../includes/header.php';
?>
<div class="product-container">

    <div class="breadcrumb">
        <a href="my_store.php">My Store</a>
        <span> / </span>
        <span class="active"><?= htmlspecialchars($product['product_type']); ?></span>
    </div>

    <div class="product-main-card">

        <div class="product-grid">

            <div class="image-side">
                <div class="product-image-wrapper">
                    <?php if (!empty($product['image_path'])): ?>
                        <img src="<?= asset_url($product['image_path']); ?>" class="product-image-managed"
                            alt="<?= htmlspecialchars($product['title']); ?>">
                    <?php else: ?>
                        <div class="no-image-placeholder">
                            <span class="icon">📦</span>
                            <p>No Product Image</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="info-side">

                <div class="category-pill">
                    <?= htmlspecialchars($product['product_type']); ?>
                </div>

                <h1 class="product-title">
                    <?= htmlspecialchars($product['title']); ?>
                </h1>

                <div class="price-box">
                    <span class="price-label">Price</span>
                    <span class="price-value">
                        Rs. <?= number_format($product['price'], 2); ?>
                    </span>
                </div>

                <div class="admin-controls-box">

                    <p class="admin-label">Store Owner Actions</p>

                    <div class="btn-group">
                        <a href="edit_product.php?id=<?= $product['id']; ?>" class="btn-static btn-static-dark">Edit
                            Listing</a>

                        <a href="post_delete.php?id=<?= $product['id']; ?>" class="btn-static btn-static-danger"
                            onclick="return confirm('Delete this product permanently?');">
                            Delete
                        </a>
                    </div>

                    <a href="my_store.php" class="back-link">
                        ← Back to Dashboard
                    </a>

                </div>

            </div>

        </div>

    </div>

    <?php if (!empty($product['body'])): ?>

        <div class="description-card">
            <h4>Product Description</h4>

            <div class="description-text">
                <?= nl2br(htmlspecialchars($product['body'])); ?>
            </div>
        </div>

    <?php endif; ?>

</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
<style>
    .product-container {
        max-width: 1100px;
        margin: auto;
        padding: 40px 20px;
        font-family: Inter, system-ui;
    }

    .breadcrumb {
        margin-bottom: 20px;
        color: #777;
    }

    .breadcrumb a {
        text-decoration: none;
        color: #777;
    }

    .breadcrumb .active {
        font-weight: 700;
        color: #111;
    }

    .product-main-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e0e0e0;
        overflow: hidden;
    }

    .product-grid {
        display: flex;
        flex-wrap: wrap;
    }

    .image-side {
        flex: 1;
        min-width: 300px;
        background: #f1f1f1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-side {
        flex: 1;
        min-width: 300px;
        padding: 40px;
    }

    .product-image-wrapper {
        padding: 30px;
        text-align: center;
    }

    .product-image-managed {
        max-width: 100%;
        max-height: 400px;
        object-fit: contain;
    }

    .no-image-placeholder {
        text-align: center;
        color: #888;
    }

    .no-image-placeholder .icon {
        font-size: 60px;
    }

    .category-pill {
        background: #e7f1ff;
        color: #0d6efd;
        padding: 6px 14px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
        margin-bottom: 10px;
    }

    .product-title {
        font-size: 32px;
        font-weight: 700;
        margin: 10px 0;
    }

    .price-box {
        margin: 20px 0;
    }

    .price-label {
        display: block;
        font-size: 12px;
        color: #888;
        font-weight: 600;
    }

    .price-value {
        font-size: 28px;
        color: #198754;
        font-weight: 700;
    }

    .admin-controls-box {
        margin-top: 30px;
        border-top: 1px solid #eee;
        padding-top: 20px;
    }

    .admin-label {
        font-size: 12px;
        font-weight: 700;
        color: #666;
        margin-bottom: 10px;
    }

    .btn-group {
        display: flex;
        gap: 10px;
    }

    .btn-static {
        padding: 12px 20px;
        text-decoration: none;
        font-weight: 700;
        border-radius: 6px;
    }

    .btn-static-dark {
        background: #212529;
        color: #fff;
    }

    .btn-static-danger {
        border: 1px solid #dc3545;
        color: #dc3545;
    }

    .back-link {
        display: block;
        margin-top: 15px;
        font-size: 14px;
        color: #777;
        text-decoration: none;
    }

    .description-card {
        margin-top: 30px;
        padding: 30px;
        border: 1px solid #eee;
        border-radius: 12px;
    }

    .description-text {
        line-height: 1.7;
        color: #555;
    }

    @media (max-width:768px) {

        .product-grid {
            flex-direction: column;
        }

        .product-title {
            font-size: 24px;
        }

    }
</style>