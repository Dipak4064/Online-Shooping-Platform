<?php
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$productId) {
    header("Location: index.php?error=invalid_id");
    exit;
}

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
            <!-- <img src="<?php echo htmlspecialchars(str_replace('/public', '', $product['image_path'])); ?>"
                alt="<?php echo htmlspecialchars($product['title']); ?>"> -->
            <img src="<?php echo htmlspecialchars($product['image_path']); ?>"
                alt="<?php echo htmlspecialchars($product['title']); ?>">

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


            <div class="product-description" id="productDescription"
                style="max-height: 220px; overflow: hidden; position: relative; margin-bottom: 10px; font-size: 1.08rem; line-height: 1.7; color: #000;">
                <?= nl2br(htmlspecialchars($product['body'])) ?>
            </div>
            <div class="product-action-row">
                <button id="toggleDescriptionBtn" class="btn-secondary">View More</button>
                <a href="payment.php?id=<?= $product['id'] ?>" class="btn-primary">Buy Now</a>
            </div>
            <script>
                const desc = document.getElementById('productDescription');
                const btn = document.getElementById('toggleDescriptionBtn');
                let expanded = false;
                btn.addEventListener('click', function () {
                    expanded = !expanded;
                    if (expanded) {
                        desc.style.maxHeight = 'none';
                        btn.textContent = 'View Less';
                    } else {
                        desc.style.maxHeight = '220px';
                        btn.textContent = 'View More';
                        desc.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                });
            </script>
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
                                <!-- <img src="<?php echo htmlspecialchars(str_replace('/public', '', $item['image_path'])); ?>"
                                    alt="<?php echo htmlspecialchars($item['title']); ?>"> -->

                                <img src="<?php echo htmlspecialchars($item['image_path']); ?>"
                                    alt="<?php echo htmlspecialchars($item['title']); ?>">
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
        margin-top:60px;
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

    .related-products-section {
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        padding: 30px;
        background: #fff;
    }

    .section-heading {
        margin-bottom: 20px;
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
        background: #f5f5f5;
        border-radius: 1rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 200px;
        overflow: hidden;
    }

    .related-image img {
        width: 100%;
        height: 100%;
        object-fit: contain;
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

    #toggleDescriptionBtn.btn-secondary {
        border: none !important;
        padding: 0.5em 1.2em !important;
        border-radius: 6px !important;
        background: #f3f4f6 !important;
        color: #232f3e !important;
        font-weight: 600;
        font-size: 1rem;
        box-shadow: none !important;
        margin-bottom: 18px;
        transition: background 0.2s;
    }

    #toggleDescriptionBtn.btn-secondary:hover {
        background: #e5e7eb !important;
        color: #000 !important;
    }

    .product-action-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 18px;
        gap: 18px;
    }

    #productDescription {
        background: none !important;
        padding: 0 !important;
    }
</style>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>