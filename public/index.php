<?php
//for session checking
// require_once __DIR__ . '/../includes/functions.php';
// start_session();
// echo '<pre>';       
// print_r($_SESSION);
// echo '</pre>';

require_once __DIR__ . '/../includes/header.php';
$pdo = get_db_connection();
$stmt = $pdo->query("SELECT * FROM posts WHERE deleted_at IS NULL ORDER BY created_at ASC limit 12" );
$all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories = get_categories();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <span class="hero-badge">🔥 Hot Deal in This Week</span>
        <h1 class="hero-title">Grab Amazing<br>Product</h1>
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
        <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=500&h=500&fit=crop"
            alt="Wireless Headphone">
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
        <a href="<?php echo BASE_URL; ?>products.php?search=phone" class="category-item">
            <div class="category-icon">📱</div>
            <span>Phones</span>
        </a>
        <a href="<?php echo BASE_URL; ?>products.php?search=Computers" class="category-item">
            <div class="category-icon">💻</div>
            <span>Computers</span>
        </a>
        <a href="<?php echo BASE_URL; ?>products.php?search=Accessories" class="category-item">
            <div class="category-icon">🎧</div>
            <span>Accessories</span>
        </a>
        <a href="<?php echo BASE_URL; ?>products.php?search=Laptops" class="category-item">
            <div class="category-icon">💼</div>
            <span>Laptops</span>
        </a>
        <a href="<?php echo BASE_URL; ?>products.php?search=Monitors" class="category-item">
            <div class="category-icon">🖥️</div>
            <span>Monitors</span>
        </a>
        <a href="<?php echo BASE_URL; ?>products.php?search=Networking" class="category-item">
            <div class="category-icon">🌐</div>
            <span>Networking</span>
        </a>
        <a href="<?php echo BASE_URL; ?>products.php?search=PC Gaming" class="category-item">
            <div class="category-icon">🎮</div>
            <span>PC Gaming</span>
        </a>
    </div>
</section>

<!-- Featured Banner -->
<!-- <section class="featured-banner">
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
        <img src="https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=400&h=400&fit=crop"
            alt="Premium Headphones">
    </div>
</section> -->

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
        <?php foreach ($all_products as $index => $product): ?>
            <div class="product-card">
                <h3 class="product-name">
                    <a href="product.php?id=<?php echo $product['id']; ?>">
                        <?php echo htmlspecialchars($product['title']); ?>
                    </a>
                </h3>

                <a href="product.php?id=<?php echo $product['id']; ?>" class="product-image-link">
                    <div class="product-image">
                        <img src="<?php echo htmlspecialchars($product['image_path']); ?>"
                            alt="<?php echo htmlspecialchars($product['title']); ?>">
                    </div>
                </a>

                <div class="product-footer">
                    <div class="price-info">
                        <span class="product-price">
                            Rs. <?php echo number_format($product['price'], 2); ?>
                        </span>
                        <span class="category-badge">
                            <?php echo htmlspecialchars($product['product_type']); ?>
                        </span>
                    </div>

                    <a href="checkout.php?id=<?php echo $product['id']; ?>" class="btn-buy-now">Buy Now</a>
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