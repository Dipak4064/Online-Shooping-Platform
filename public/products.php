<?php
require_once __DIR__ . '/../includes/header.php';

$pdo = get_db_connection();

/* ----------------------------
   SEARCH FILTER
----------------------------- */
$search = $_GET['search'] ?? '';

$sql = "SELECT * FROM posts WHERE deleted_at IS NULL";
$params = [];

if (!empty($search)) {
    $sql .= " AND (title LIKE ? OR body LIKE ?)";
    $params[] = "%{$search}%";
    $params[] = "%{$search}%";
}

$sql .= " ORDER BY created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="collection-hero">
    <div class="collection-hero-content">
        <span class="collection-label">Collections</span>
        <h1>Explore Our Products</h1>
        <p>Find the best items for your needs. Quality and variety guaranteed.</p>
    </div>
</section>

<div class="collection-breadcrumb">
    <a href="<?php echo BASE_URL; ?>index.php">Home</a>
    <span>›</span>
    <span>All Products</span>
</div>

<div class="collection-container">

    <div class="search-bar-section">
        <form method="get" class="inline-search-form">
            <div class="filter-search">
                <input type="text" name="search" placeholder="Search products by title..."
                    value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <button type="submit" class="apply-filters-btn">
                Search
            </button>
        </form>
    </div>

    <main class="collection-main">
        <div class="collection-header">
            <h2>
                <?php echo !empty($search) ? "Results for '" . htmlspecialchars($search) . "'" : "All Products"; ?>
            </h2>
            <span class="product-count">
                <?php echo count($products); ?> Products
            </span>
        </div>

        <?php if (count($products) > 0): ?>
            <div class="collection-grid">
                <?php foreach ($products as $product): ?>
                    <div class="collection-product-card">

                        <a href="<?php echo BASE_URL; ?>product.php?id=<?php echo $product['id']; ?>"
                            class="product-image-link">
                            <div class="product-image-wrapper">
                                <img src="<?php echo htmlspecialchars($product['image_path']); ?>"
                                    alt="<?php echo htmlspecialchars($product['title']); ?>">
                            </div>
                        </a>

                        <div class="product-details">
                            <h3 class="product-title">
                                <a href="<?php echo BASE_URL; ?>product.php?id=<?php echo $product['id']; ?>">
                                    <?php echo htmlspecialchars($product['title']); ?>
                                </a>
                            </h3>

                            <p class="product-description">
                                <?php echo htmlspecialchars(substr($product['body'], 0, 90)); ?>...
                            </p>

                            <div class="product-footer">
                                <span class="product-price">
                                    Rs. <?php echo number_format($product['price'], 0); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-products">
                <div class="no-products-content">
                    <h3>No products found</h3>
                    <p>We couldn't find anything matching "<?php echo htmlspecialchars($search); ?>".</p>
                    <a href="products.php" class="clear-search-link">View All Products</a>
                </div>
            </div>
        <?php endif; ?>
    </main>
</div>

<style>
    /* Hero */
    .collection-hero {
        background: linear-gradient(135deg, #f0f4f8, #d9e2ec);
        padding: 2rem 1rem;
        border-radius: 1rem;
        text-align: center;
        margin: 1rem;
    }

    .collection-hero h1 {
        font-size: 2.5rem;
        color: #1a202c;
        margin-bottom: 0.5rem;
    }

    /* Breadcrumb */
    .collection-breadcrumb {
        padding: 1rem 2rem;
        font-size: 0.85rem;
        color: #888;
    }

    .collection-breadcrumb a {
        color: #555;
        text-decoration: none;
    }

    /* One Column Layout */
    .collection-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem 3rem;
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .search-bar-section {
        width: 100%;
        margin: 0 auto;
    }

    .inline-search-form {
        display: flex;
        gap: 0.5rem;
        padding: 0.5rem;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }

    .filter-search {
        flex: 1;
    }

    .filter-search input {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        border: 1px solid transparent;
        outline: none;
        font-size: 1rem;
    }

    .apply-filters-btn {
        padding: 0.75rem 1.5rem;
        background: #1a73e8;
        color: #fff;
        border: none;
        border-radius: 0.5rem;
        cursor: pointer;
        font-weight: 600;
        transition: background 0.2s;
    }

    .apply-filters-btn:hover {
        background: #1557b0;
    }

    /* Products Main */
    .collection-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid #eee;
        padding-bottom: 1rem;
    }

    .collection-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 2rem;
    }

    .collection-product-card {
        background: #fff;
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid #eee;
        transition: transform .3s, box-shadow .3s;
        display: flex;
        flex-direction: column;
    }

    .collection-product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
    }

    .product-image-wrapper {
        aspect-ratio: 1 / 1;
        overflow: hidden;
        background: #f7fafc;
    }

    .product-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .product-details {
        padding: 1.25rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .product-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .product-title a {
        text-decoration: none;
        color: #2d3748;
    }

    .product-description {
        font-size: 0.9rem;
        color: #718096;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    .product-footer {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .product-price {
        font-weight: 700;
        color: #1a73e8;
        font-size: 1.1rem;
    }

    /* No Products State */
    .no-products {
        padding: 5rem 1rem;
        text-align: center;
        background: #f8fafc;
        border-radius: 1rem;
        border: 2px dashed #e2e8f0;
    }

    .clear-search-link {
        display: inline-block;
        margin-top: 1rem;
        color: #1a73e8;
        text-decoration: none;
        font-weight: 600;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .inline-search-form {
            flex-direction: column;
        }

        .collection-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .collection-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>