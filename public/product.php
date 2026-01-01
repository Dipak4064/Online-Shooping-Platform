<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

// 1. Get and validate ID
$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$productId) {
    header("Location: index.php?error=invalid_id");
    exit;
}

// 2. Fetch the specific product from the 'posts' table
$product = get_post_by_id($productId);

if (!$product) {
    header("Location: index.php?error=not_found");
    exit;
}

/**
 * 3. Fetch Related Products
 * Since your system uses a 'posts' table for user items, we fetch 
 * other posts excluding the current one.
 */
$pdo = get_db_connection();
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id != ? AND deleted_at IS NULL ORDER BY RAND() LIMIT 5");
$stmt->execute([$productId]);
$relatedProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container py-5">

    <div class="product-block">

        <div class="product-image-block">
            <img src="<?= htmlspecialchars($product['image_path']) ?>" alt="<?= htmlspecialchars($product['title']) ?>"
                onerror="this.src='https://via.placeholder.com/600x600?text=No+Image'">
        </div>

        <div class="product-details-block">
            <span class="product-badge">
                <?= htmlspecialchars($product['product_type'] ?? 'General') ?>
            </span>

            <h1 class="product-name">
                <?= htmlspecialchars($product['title']) ?>
            </h1>

            <div class="product-price">
                Rs. <?= number_format($product['price'], 2) ?>
            </div>

            <div class="product-description">
                <?= nl2br(htmlspecialchars($product['body'])) ?>
            </div>

            <div class="product-actions">
                <a href="payment.php?id=<?= $product['id'] ?>" class="btn-primary">
                    Buy Now
                </a>
            </div>
        </div>
    </div>

    <div class="related-products-section">
        <h3 class="section-heading">You May Also Like</h3>

        <div class="related-grid">
            <?php if (empty($relatedProducts)): ?>
                <p>No related products found.</p>
            <?php else: ?>
                <?php foreach ($relatedProducts as $item): ?>
                    <div class="related-card">
                        <a href="product.php?id=<?= $item['id'] ?>" class="card-link">
                            <div class="related-image">
                                <img src="<?= htmlspecialchars($item['image_path']) ?>"
                                    alt="<?= htmlspecialchars($item['title']) ?>"
                                    onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
                            </div>
                            <h6 class="related-title"><?= htmlspecialchars($item['title']) ?></h6>
                            <span class="related-price">Rs. <?= number_format($item['price'], 2) ?></span>
                        </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    * {
        box-sizing: border-box;
        font-family: 'Inter', sans-serif;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .product-block {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 40px;
        background: #fff;
        border-radius: 20px;
        padding: 40px;
        box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        margin-bottom: 60px;
        border: 1px solid #f0f0f0;
    }

    .product-image-block {
        background: #fafafa;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 400px;
    }

    .product-image-block img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
    }

    .product-badge {
        background: #667eea;
        color: #fff;
        padding: 5px 12px;
        border-radius: 5px;
        font-size: 12px;
        font-weight: bold;
        width: fit-content;
    }

    .product-name {
        font-size: 32px;
        margin: 15px 0;
        color: #111827;
    }

    .product-price {
        font-size: 28px;
        color: #059669;
        font-weight: 800;
        margin-bottom: 20px;
    }

    .product-description {
        color: #6b7280;
        line-height: 1.6;
        margin-bottom: 30px;
    }

    .btn-primary {
        background: #000;
        color: #fff;
        padding: 15px 35px;
        border-radius: 10px;
        text-decoration: none;
        font-weight: bold;
    }

    .btn-secondary {
        border: 1px solid #ddd;
        padding: 15px 25px;
        border-radius: 10px;
        text-decoration: none;
        color: #333;
    }

    /* Related Grid */
    .related-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .related-card {
        border: 1px solid #eee;
        padding: 15px;
        border-radius: 12px;
        transition: 0.3s;
    }

    .related-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .related-image {
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
    }

    .related-image img {
        max-width: 100%;
        max-height: 100%;
    }

    .related-title {
        font-size: 14px;
        margin: 5px 0;
        color: #333;
        text-decoration: none;
    }

    .card-link {
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .product-block {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>