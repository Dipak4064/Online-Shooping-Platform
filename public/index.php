<?php
require_once __DIR__ . '/../includes/header.php';

$categories = get_categories();
$featured = get_featured_products();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <span class="hero-badge">🔥 Hot Deal in This Week</span>
        <h1 class="hero-title">Roco Wireless<br>Headphone</h1>
        <div class="hero-info">
            <a href="<?php echo BASE_URL; ?>products.php" class="btn-shop-now">
                <span>🛒</span> Shop Now
            </a>
            <div class="hero-reviews">
                <div class="review-avatars">
                    <span class="avatar">👤</span>
                    <span class="avatar">👤</span>
                    <span class="avatar">👤</span>
                </div>
                <div class="review-info">
                    <span class="stars">⭐⭐⭐⭐⭐</span>
                    <span class="count">100+ Reviews</span>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-image">
        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop" alt="Wireless Headphone">
        <div class="hero-price-tag">
            <span class="from">From</span>
            <span class="price">$49.00</span>
        </div>
    </div>
    <div class="hero-side-products">
        <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=120&h=120&fit=crop" alt="Smart Watch">
    </div>
</section>

<!-- Browse by Category -->
<section class="categories-section">
    <div class="section-header-row">
        <div class="section-header">
            <span class="section-badge">📦 Categories</span>
            <h2>Browse by Category</h2>
        </div>
        <div class="section-nav">
            <button class="nav-arrow">←</button>
            <button class="nav-arrow">→</button>
        </div>
    </div>
    <div class="categories-grid">
        <a href="<?php echo BASE_URL; ?>products.php" class="category-item">
            <div class="category-icon">📱</div>
            <span>Phones</span>
        </a>
        <a href="<?php echo BASE_URL; ?>products.php" class="category-item">
            <div class="category-icon">💻</div>
            <span>Computers</span>
        </a>
        <a href="<?php echo BASE_URL; ?>products.php" class="category-item">
            <div class="category-icon">🎧</div>
            <span>Accessories</span>
        </a>
        <a href="<?php echo BASE_URL; ?>products.php" class="category-item">
            <div class="category-icon">💼</div>
            <span>Laptops</span>
        </a>
        <a href="<?php echo BASE_URL; ?>products.php" class="category-item">
            <div class="category-icon">🖥️</div>
            <span>Monitors</span>
        </a>
        <a href="<?php echo BASE_URL; ?>products.php" class="category-item">
            <div class="category-icon">🌐</div>
            <span>Networking</span>
        </a>
        <a href="<?php echo BASE_URL; ?>products.php" class="category-item">
            <div class="category-icon">🎮</div>
            <span>PC Gaming</span>
        </a>
    </div>
</section>

<!-- Featured Banner -->
<section class="featured-banner">
    <div class="banner-content">
        <span class="banner-badge">🎵 Don't Miss!!</span>
        <h2>Enhance Your<br>Music Experience</h2>
        <div class="countdown" id="countdownTimer">
            <div class="countdown-item">
                <span class="number" data-days>15</span>
                <span class="label">Days</span>
            </div>
            <div class="countdown-item">
                <span class="number" data-hours>10</span>
                <span class="label">Hrs</span>
            </div>
            <div class="countdown-item">
                <span class="number" data-minutes>56</span>
                <span class="label">Min</span>
            </div>
            <div class="countdown-item">
                <span class="number" data-seconds>54</span>
                <span class="label">Sec</span>
            </div>
        </div>
        <a href="<?php echo BASE_URL; ?>products.php" class="btn-check-out">Check it Out!</a>
    </div>
    <div class="banner-image">
        <img src="https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=400&h=400&fit=crop" alt="Premium Headphones">
    </div>
</section>

<!-- Explore Products -->
<section class="products-section">
    <div class="section-header-row">
        <div class="section-header">
            <span class="section-badge">🛍️ Our Products</span>
            <h2>Explore our Products</h2>
        </div>
        <div class="section-nav">
            <button class="nav-arrow">←</button>
            <button class="nav-arrow">→</button>
        </div>
    </div>
    <div class="products-grid">
        <?php foreach ($featured as $index => $product): ?>
            <div class="product-card">
                <?php if ($index < 4): ?>
                    <span class="discount-badge">30% Off</span>
                <?php endif; ?>
                <a href="<?php echo BASE_URL; ?>product.php?slug=<?php echo urlencode($product['slug']); ?>" class="product-image-link">
                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars(product_image_url($product)); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>
                </a>
                <div class="product-info">
                    <h3 class="product-name">
                        <a href="<?php echo BASE_URL; ?>product.php?slug=<?php echo urlencode($product['slug']); ?>">
                            <?php echo htmlspecialchars($product['name']); ?>
                        </a>
                    </h3>
                    <div class="product-rating">
                        <span class="stars">⭐⭐⭐⭐⭐</span>
                        <span class="count">(68)</span>
                    </div>
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
    <div class="products-footer">
        <a href="<?php echo BASE_URL; ?>products.php" class="btn-view-all">View All Products</a>
    </div>
</section>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>
