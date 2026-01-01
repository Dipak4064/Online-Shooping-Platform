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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container py-5 product-view-page">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="my_store.php" class="text-decoration-none text-muted">My Store</a></li>
            <li class="breadcrumb-item active fw-bold text-dark"><?= htmlspecialchars($product['product_type']); ?></li>
        </ol>
    </nav>

    <div class="product-main-card shadow-sm border-0">
        <div class="row g-0">
            <div class="col-md-6 bg-image-container">
                <div class="product-image-wrapper">
                    <?php if (!empty($product['image_path'])): ?>
                        <img src="<?= asset_url($product['image_path']); ?>" class="product-image-managed"
                            alt="<?= htmlspecialchars($product['title']); ?>">
                    <?php else: ?>
                        <div class="no-image-placeholder text-center text-muted">
                            <span class="d-block mb-2" style="font-size: 4rem;">📦</span>
                            <p class="small fw-bold text-uppercase m-0">No Product Image</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6 p-4 p-lg-5 d-flex flex-column justify-content-center bg-white">
                <div class="category-pill mb-3"><?= htmlspecialchars($product['product_type']); ?></div>

                <h1 class="product-title mb-3"><?= htmlspecialchars($product['title']); ?></h1>

                <div class="price-box mb-4">
                    <span class="price-label small text-muted text-uppercase fw-semibold">Price</span>
                    <span class="price-value text-success h2 fw-bold">Rs.
                        <?= number_format($product['price'], 2); ?></span>
                </div>

                <div class="admin-controls-box pt-4 border-top">
                    <p class="admin-label small fw-bold text-secondary text-uppercase mb-3">Store Owner Actions</p>
                    <div class="d-flex gap-2">
                        <a href="edit_product.php?id=<?= $product['id']; ?>"
                            class="btn-static btn-static-dark flex-grow-1">Edit Listing</a>
                        <a href="post_delete.php?id=<?= $product['id']; ?>" class="btn-static btn-static-danger px-4"
                            onclick="return confirm('Delete this product permanently?');">Delete</a>
                    </div>
                    <a href="my_store.php"
                        class="back-link mt-3 d-inline-block text-muted text-decoration-none small">← Back to
                        Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($product['body'])): ?>
        <div class="description-card shadow-sm mt-4 p-4 p-lg-5 bg-white">
            <h4 class="fw-bold mb-3 border-bottom pb-2">Product Description</h4>
            <div class="description-text text-secondary lead-small">
                <?= nl2br(htmlspecialchars($product['body'])); ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<style>
    /* 1. Base Layout Styling */
    .product-view-page {
        font-family: 'Inter', system-ui, sans-serif;
        background-color: #f8f9fa;
    }

    .product-main-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #e0e0e0;
    }

    /* 2. Image Management (Optimized) */
    .bg-image-container {
        background-color: #f1f1f1;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 450px;
    }

    .product-image-wrapper {
        padding: 30px;
        width: 100%;
        height: 100%;
        text-align: center;
    }

    .product-image-managed {
        max-width: 100%;
        max-height: 400px;
        object-fit: contain;
        display: inline-block;
        /* No transitions here for static feel */
    }

    /* 3. Typography & UI Components */
    .category-pill {
        background: #e7f1ff;
        color: #0d6efd;
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        width: fit-content;
    }

    .product-title {
        font-size: 2.25rem;
        color: #1a1a1a;
        font-weight: 700;
    }

    .description-card {
        border-radius: 12px;
        border: 1px solid #eee;
    }

    .description-text {
        line-height: 1.7;
        font-size: 1.05rem;
    }

    /* 4. TOTAL STATIC BUTTONS (Optimized CSS) */
    .btn-static {
        padding: 14px 24px;
        font-weight: 700;
        text-align: center;
        text-decoration: none !important;
        display: inline-block;
        border-radius: 6px;
        transition: none !important;
        box-shadow: none !important;
        border: 1px solid transparent;
    }

    /* Dark Button */
    .btn-static-dark {
        background-color: #212529 !important;
        color: #fff !important;
    }

    .btn-static-dark:hover,
    .btn-static-dark:active {
        background-color: #212529 !important;
        color: #fff !important;
        transform: none !important;
    }

    /* Danger Button */
    .btn-static-danger {
        background-color: transparent !important;
        color: #dc3545 !important;
        border: 1px solid #dc3545 !important;
    }

    .btn-static-danger:hover,
    .btn-static-danger:active {
        background-color: transparent !important;
        color: #dc3545 !important;
    }

    /* 5. Responsiveness */
    @media (max-width: 768px) {
        .product-title {
            font-size: 1.75rem;
        }

        .bg-image-container {
            min-height: 300px;
        }

        .product-image-managed {
            max-height: 300px;
        }
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>