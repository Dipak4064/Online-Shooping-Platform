<?php
require_once __DIR__ . '/../includes/header.php';

$categories = get_categories();

$filters = [
    'search' => $_GET['search'] ?? '',
    'min_price' => $_GET['min_price'] ?? '',
    'max_price' => $_GET['max_price'] ?? '',
];

$selectedCategory = null;
if (!empty($_GET['category'])) {
    $category = get_category_by_slug($_GET['category']);
    if ($category) {
        $filters['category_id'] = $category['id'];
        $selectedCategory = $category;
    }
}

$products = get_products($filters);
?>

<!-- Collection Hero Banner -->
<section class="collection-hero">
    <div class="collection-hero-content">
        <span class="collection-label">• Collections</span>
        <h1>Explore The Various Collection</h1>
        <p>Don't miss out on shopping collection from us! You'll not be let down.</p>
    </div>
</section>

<!-- Breadcrumb -->
<div class="collection-breadcrumb">
    <a href="<?php echo BASE_URL; ?>index.php">Home</a>
    <span>›</span>
    <span><?php echo $selectedCategory ? htmlspecialchars($selectedCategory['name']) : 'All Products'; ?></span>
</div>

<div class="collection-container">
    <!-- Sidebar Filters -->
    <aside class="collection-sidebar">
        <form method="get" class="sidebar-filters">
            <!-- Search -->
            <div class="filter-search">
                <input type="text" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($filters['search']); ?>">
            </div>

            <!-- Brand/Category Filter -->
            <div class="filter-group">
                <div class="filter-header">
                    <h3>Category</h3>
                    <a href="<?php echo BASE_URL; ?>products.php" class="filter-reset">Reset</a>
                </div>
                <div class="filter-options">
                    <?php foreach ($categories as $cat): ?>
                    <label class="filter-checkbox">
                        <input type="radio" name="category" value="<?php echo htmlspecialchars($cat['slug']); ?>" 
                            <?php echo (!empty($_GET['category']) && $_GET['category'] === $cat['slug']) ? 'checked' : ''; ?>>
                        <span class="checkmark"></span>
                        <span class="filter-label"><?php echo htmlspecialchars($cat['name']); ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
                <button type="button" class="show-more-btn">Show more</button>
            </div>

            <!-- Price Filter -->
            <div class="filter-group">
                <div class="filter-header">
                    <h3>Price</h3>
                    <span class="filter-reset" onclick="document.querySelector('input[name=min_price]').value=''; document.querySelector('input[name=max_price]').value='';">Reset</span>
                </div>
                <div class="price-inputs">
                    <input type="number" name="min_price" placeholder="Min" value="<?php echo htmlspecialchars($filters['min_price']); ?>">
                    <span class="price-separator">-</span>
                    <input type="number" name="max_price" placeholder="Max" value="<?php echo htmlspecialchars($filters['max_price']); ?>">
                </div>
                <div class="price-range-visual">
                    <div class="price-range-track">
                        <div class="price-range-fill"></div>
                    </div>
                    <div class="price-labels">
                        <span>Rs. 0</span>
                        <span>Rs. 50,000+</span>
                    </div>
                </div>
            </div>

            <button type="submit" class="apply-filters-btn">Apply Filters</button>
        </form>
    </aside>

    <!-- Products Grid -->
    <main class="collection-main">
        <div class="collection-header">
            <h2><?php echo $selectedCategory ? htmlspecialchars($selectedCategory['name']) : 'All Products'; ?></h2>
            <span class="product-count"><?php echo count($products); ?> Products</span>
        </div>

        <?php if (!$products): ?>
            <div class="no-products">
                <p>No products found matching your criteria.</p>
                <a href="<?php echo BASE_URL; ?>products.php" class="btn">View All Products</a>
            </div>
        <?php else: ?>
            <div class="collection-grid">
                <?php foreach ($products as $product): ?>
                <div class="collection-product-card">
                    <a href="<?php echo BASE_URL; ?>product.php?slug=<?php echo urlencode($product['slug']); ?>" class="product-image-link">
                        <div class="product-image-wrapper">
                            <img src="<?php echo htmlspecialchars(product_image_url($product)); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                        </div>
                    </a>
                    <div class="product-details">
                        <h3 class="product-title">
                            <a href="<?php echo BASE_URL; ?>product.php?slug=<?php echo urlencode($product['slug']); ?>">
                                <?php echo htmlspecialchars($product['name']); ?>
                            </a>
                        </h3>
                        <p class="product-description">
                            <?php echo htmlspecialchars($product['short_description'] ?? 'Premium quality product'); ?>
                        </p>
                        <div class="product-footer">
                            <form method="post" action="<?php echo BASE_URL; ?>wishlist_actions.php" class="wishlist-form">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="wishlist-btn" title="Add to Wishlist">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                                    </svg>
                                </button>
                            </form>
                            <?php if (($product['stock'] ?? 0) > 0): ?>
                            <form method="post" action="<?php echo BASE_URL; ?>cart_actions.php" class="cart-form">
                                <input type="hidden" name="action" value="add">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" class="cart-btn" title="Add to Cart">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M6 6h15l-1.5 9h-12z"/>
                                        <circle cx="9" cy="20" r="1"/>
                                        <circle cx="18" cy="20" r="1"/>
                                    </svg>
                                </button>
                            </form>
                            <?php else: ?>
                            <span class="out-of-stock-badge">Out of Stock</span>
                            <?php endif; ?>
                            <span class="product-price">Rs. <?php echo number_format($product['price'], 0); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>

<style>
/* Collection Hero */
.collection-hero {
    background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
    padding: 3rem 2rem;
    margin: 1rem;
    border-radius: 1.5rem;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    min-height: 200px;
    position: relative;
    overflow: hidden;
}

.collection-hero::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    width: 50%;
    height: 100%;
    background: linear-gradient(135deg, #d0d0d0 0%, #e0e0e0 100%);
    border-radius: 1.5rem 0 0 1.5rem;
}

.collection-hero-content {
    position: relative;
    z-index: 1;
    text-align: right;
    max-width: 400px;
    padding-right: 2rem;
}

.collection-label {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 0.5rem;
    display: block;
}

.collection-hero h1 {
    font-size: 1.75rem;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 0.75rem;
    line-height: 1.3;
}

.collection-hero p {
    font-size: 0.9rem;
    color: #666;
    margin: 0;
}

/* Breadcrumb */
.collection-breadcrumb {
    padding: 1rem 2rem;
    font-size: 0.85rem;
    color: #888;
}

.collection-breadcrumb a {
    color: #666;
    text-decoration: none;
}

.collection-breadcrumb a:hover {
    color: #333;
}

.collection-breadcrumb span {
    margin: 0 0.5rem;
}

/* Collection Container */
.collection-container {
    display: grid;
    grid-template-columns: 280px 1fr;
    gap: 2rem;
    padding: 0 2rem 3rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* Sidebar */
.collection-sidebar {
    position: sticky;
    top: 100px;
    height: fit-content;
}

.sidebar-filters {
    background: #fff;
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    border: 1px solid #eee;
}

.filter-search input {
    width: 100%;
    padding: 0.75rem 1rem;
    border: 1px solid #e0e0e0;
    border-radius: 0.5rem;
    font-size: 0.9rem;
    margin-bottom: 1.5rem;
}

.filter-group {
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #eee;
}

.filter-group:last-of-type {
    border-bottom: none;
}

.filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.filter-header h3 {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.filter-reset {
    font-size: 0.8rem;
    color: #888;
    text-decoration: none;
    cursor: pointer;
}

.filter-reset:hover {
    color: #333;
}

.filter-options {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.filter-checkbox {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    cursor: pointer;
    font-size: 0.9rem;
    color: #444;
}

.filter-checkbox input {
    width: 18px;
    height: 18px;
    accent-color: #1a1a1a;
}

.filter-label {
    flex: 1;
}

.show-more-btn {
    background: #f5f5f5;
    border: none;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    font-size: 0.85rem;
    color: #555;
    cursor: pointer;
    margin-top: 0.75rem;
    width: 100%;
}

.show-more-btn:hover {
    background: #eee;
}

.price-inputs {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.price-inputs input {
    flex: 1;
    padding: 0.6rem;
    border: 1px solid #e0e0e0;
    border-radius: 0.5rem;
    font-size: 0.85rem;
    text-align: center;
}

.price-separator {
    color: #999;
}

.price-range-visual {
    margin-top: 0.5rem;
}

.price-range-track {
    height: 4px;
    background: #e0e0e0;
    border-radius: 2px;
    position: relative;
}

.price-range-fill {
    position: absolute;
    left: 10%;
    right: 30%;
    height: 100%;
    background: #1a1a1a;
    border-radius: 2px;
}

.price-labels {
    display: flex;
    justify-content: space-between;
    margin-top: 0.5rem;
    font-size: 0.75rem;
    color: #888;
}

.apply-filters-btn {
    width: 100%;
    padding: 0.85rem;
    background: #1a1a1a;
    color: #fff;
    border: none;
    border-radius: 0.5rem;
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    margin-top: 1rem;
    transition: background 0.3s ease;
}

.apply-filters-btn:hover {
    background: #333;
}

/* Collection Main */
.collection-main {
    min-height: 500px;
}

.collection-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.collection-header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0;
}

.product-count {
    font-size: 0.9rem;
    color: #888;
}

/* Products Grid */
.collection-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

/* Product Card */
.collection-product-card {
    background: #fff;
    border-radius: 1rem;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.collection-product-card:hover {
    box-shadow: 0 10px 40px rgba(0,0,0,0.08);
    transform: translateY(-4px);
}

.product-image-wrapper {
    aspect-ratio: 1/1;
    overflow: hidden;
    background: #f8f8f8;
}

.product-image-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}

.collection-product-card:hover .product-image-wrapper img {
    transform: scale(1.05);
}

.product-details {
    padding: 1rem 1.25rem 1.25rem;
}

.product-title {
    font-size: 1rem;
    font-weight: 600;
    margin: 0 0 0.35rem;
    color: #1a1a1a;
}

.product-title a {
    color: inherit;
    text-decoration: none;
}

.product-title a:hover {
    color: #555;
}

.product-description {
    font-size: 0.8rem;
    color: #888;
    margin: 0 0 0.75rem;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-footer {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.wishlist-form,
.cart-form {
    display: inline-flex;
    margin: 0;
    padding: 0;
}

.product-price {
    font-size: 0.95rem;
    font-weight: 600;
    color: #1a1a1a;
    background: #f0f0f0;
    padding: 0.4rem 0.75rem;
    border-radius: 0.5rem;
    margin-left: auto;
}

.wishlist-btn,
.cart-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    border: 1px solid #e0e0e0;
    background: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #666;
}

.wishlist-btn:hover {
    background: #fff0f0;
    border-color: #ffcccc;
    color: #e74c3c;
}

.cart-btn:hover {
    background: #1a1a1a;
    border-color: #1a1a1a;
    color: #fff;
}

.out-of-stock-badge {
    font-size: 0.75rem;
    color: #999;
    background: #f5f5f5;
    padding: 0.3rem 0.6rem;
    border-radius: 0.25rem;
}

.no-products {
    text-align: center;
    padding: 4rem 2rem;
    background: #f8f8f8;
    border-radius: 1rem;
}

.no-products p {
    color: #666;
    margin-bottom: 1.5rem;
}

/* Responsive */
@media (max-width: 1024px) {
    .collection-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .collection-container {
        grid-template-columns: 1fr;
        padding: 0 1rem 2rem;
    }
    
    .collection-sidebar {
        position: static;
        order: -1;
    }
    
    .collection-hero {
        padding: 2rem 1.5rem;
        margin: 0.5rem;
    }
    
    .collection-hero-content {
        text-align: center;
        padding: 0;
        max-width: 100%;
    }
    
    .collection-hero::before {
        display: none;
    }
    
    .collection-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
}

@media (max-width: 480px) {
    .collection-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
