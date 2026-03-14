<!-- images chages
index.php
product.php -->
<?php
//for session checking
// require_once __DIR__ . '/../includes/functions.php';
// start_session();
// echo '<pre>';       
// print_r($_SESSION);
// echo '</pre>';

require_once __DIR__ . '/../includes/header.php';
$pdo = get_db_connection();
$stmt = $pdo->query("SELECT * FROM posts WHERE deleted_at IS NULL ORDER BY created_at ASC limit 12");
$all_products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$categories = get_categories();
?>
<div class="container">
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
            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=120&h=120&fit=crop"
                alt="Smart Watch">
        </div>
    </section>

    <!-- Browse by Category -->
    <section class="categories-section">
        <div class="section-header-row">
            <div class="section-header">

                <span class="section-badge">📦 Categories</span>
                <div class="section-header-row-flex">
                    <h2>Browse by Category</h2>
                    <div class="section-nav">
                        <button class="nav-arrow">←</button>
                        <button class="nav-arrow">→</button>
                    </div>
                </div>
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
            <img src="https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=400&h=400&fit=crop"
                alt="Premium Headphones">
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
            <?php
            $limited_products = array_slice($all_products, 0, 9);

            foreach ($limited_products as $index => $product):
                ?>
                <div class="product-card">
                    <a href="product.php?id=<?php echo $product['id']; ?>" class="product-image-link">
                        <div class="product-image">
                            <!-- <img src="<?php echo htmlspecialchars(str_replace('/public', '', $product['image_path'])); ?>" alt="<?php echo htmlspecialchars($product['title']); ?>"> -->
                            <img src="<?php echo htmlspecialchars($product['image_path']); ?>"
                                alt="<?php echo htmlspecialchars($product['title']); ?>">
                        </div>
                    </a>

                    <div class="product-footer">
                        <h3 class="product-name">
                            <a href="product.php?id=<?php echo $product['id']; ?>">
                                <?php echo htmlspecialchars($product['title']); ?>
                            </a>
                        </h3>
                        <div class="price-buy-row">
                            <span class="product-price">Rs. <?php echo number_format($product['price'], 2); ?></span>
                            <a href="payment.php?id=<?php echo $product['id']; ?>" class="btn-buy-now">Buy Now</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="products-footer">
            <a href="<?php echo BASE_URL; ?>products.php" class="btn-view-all">View All Products</a>
        </div>
    </section>
</div>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>

<style>
    /* =========================================
   eTrade Homepage Styles
   ========================================= */
    .container {
        width: min(1400px, 94vw);
        margin: 0 auto;
    }

    .section-header-row-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100% !important;
        gap: 2rem;
    }

    .section-header-row-flex h2 {
        margin: 0;
    }

    /* Hero Section */
    .hero-section {
        display: grid;
        grid-template-columns: 1fr 1fr auto;
        gap: 2rem;
        align-items: center;
        padding: 3rem 5%;
        min-height: 70vh;
        background: linear-gradient(135deg, #f8f9ff 0%, #eff6ff 50%, #e0f2fe 100%);
        position: relative;
        overflow: hidden;
    }

    body.dark-mode .hero-section {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f3460 100%);
    }

    .hero-section::before {
        content: '';
        position: absolute;
        width: 600px;
        height: 600px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.08) 0%, transparent 70%);
        top: -200px;
        right: -100px;
        border-radius: 50%;
    }

    .hero-content {
        z-index: 2;
    }

    .hero-content .hero-badge {
        display: inline-block;
        background: #1f2937;
        color: white;
        padding: 0.5rem 1.25rem;
        border-radius: 999px;
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        box-shadow: 0 4px 15px rgba(31, 41, 55, 0.3);
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1.15;
        margin: 0 0 2rem;
        color: var(--text-primary);
    }

    body.dark-mode .hero-title {
        color: white;
    }

    .hero-info {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .btn-shop-now {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        background: #1f2937;
        color: white;
        border-radius: 999px;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        box-shadow: 0 4px 20px rgba(31, 41, 55, 0.3);
        transition: all 0.3s ease;
    }

    .btn-shop-now:hover {
        background: #111827;
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(31, 41, 55, 0.4);
        color: white;
    }

    .hero-reviews {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .review-avatars {
        display: flex;
    }

    .review-avatars .avatar {
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #e0f2fe, #bae6fd);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-left: -10px;
        border: 2px solid white;
        font-size: 0.9rem;
    }

    .review-avatars .avatar:first-child {
        margin-left: 0;
    }

    body.dark-mode .review-avatars .avatar {
        background: linear-gradient(135deg, #1e3a5f, #0f3460);
        border-color: #1e293b;
    }

    .review-info {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .review-info .stars {
        font-size: 0.85rem;
        letter-spacing: 2px;
    }

    .review-info .count {
        font-size: 0.85rem;
        color: var(--muted);
    }

    .hero-image {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .hero-image>img {
        max-width: 400px;
        max-height: 400px;
        object-fit: contain;
        filter: drop-shadow(0 30px 60px rgba(0, 0, 0, 0.15));
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0);
        }

        50% {
            transform: translateY(-20px);
        }
    }

    .hero-price-tag {
        position: absolute;
        bottom: 10%;
        right: 0;
        background: white;
        padding: 1rem 1.5rem;
        border-radius: 1rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    body.dark-mode .hero-price-tag {
        background: #1e293b;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    }

    .hero-price-tag .from {
        font-size: 1rem;
        color: #1f2937;
    }

    .hero-price-tag .price {
        font-size: 1.75rem;
        font-weight: 800;
        color: #1f2937;
    }

    body.dark-mode .price {
        color: white;
    }

    body.dark-mode .hero-price-tag .from {
        color: #9ca3af;
    }

    .hero-side-products {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .hero-side-products img {
        width: 100px;
        height: 100px;
        object-fit: cover;
        border-radius: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .hero-side-products img:hover {
        transform: scale(1.1);
    }

    /* Categories Section */
    .categories-section {
        padding: 4rem 5%;
        background: var(--surface);
    }

    body.dark-mode .categories-section {
        background: #0f172a;
    }

    .section-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 2rem;
    }

    .section-header .section-badge {
        display: inline-block;
        background: rgba(31, 41, 55, 0.1);
        color: #1f2937;
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .section-header h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary);
    }

    body.dark-mode .section-header h2 {
        color: white;
    }

    .section-nav {
        display: flex;
        gap: 0.5rem;
    }

    .section-nav .nav-arrow {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid #e5e7eb;
        background: white;
        color: #1f2937;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    body.dark-mode .section-nav .nav-arrow {
        background: #1e293b;
        border-color: rgba(255, 255, 255, 0.1);
        color: white;
    }

    .section-nav .nav-arrow:hover {
        background: #1f2937;
        border-color: #1f2937;
        color: white;
    }

    .categories-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1.5rem;
    }

    .categories-grid .category-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        padding: 1.5rem 1rem;
        background: white;
        border-radius: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        border: 2px solid transparent;
    }

    body.dark-mode .categories-grid .category-item {
        background: #1e293b;
    }

    .categories-grid .category-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(31, 41, 55, 0.15);
        border-color: #1f2937;
    }

    .categories-grid .category-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #eff6ff, #dbeafe);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        transition: all 0.3s ease;
    }

    body.dark-mode .categories-grid .category-icon {
        background: linear-gradient(135deg, #0f3460, #1e3a5f);
    }

    .categories-grid .category-item:hover .category-icon {
        background: #1f2937;
    }

    .categories-grid .category-item span {
        font-weight: 600;
        font-size: 0.9rem;
        color: var(--text-primary);
    }

    body.dark-mode .categories-grid .category-item span {
        color: white;
    }

    /* Featured Banner */
    .featured-banner {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 3rem;
        align-items: center;
        padding: 4rem 5%;
        background: linear-gradient(135deg, #1e3a5f 0%, #0f3460 100%);
        position: relative;
        overflow: hidden;
    }

    .featured-banner::before {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(59, 130, 246, 0.2) 0%, transparent 70%);
        top: -150px;
        left: -150px;
        border-radius: 50%;
    }

    .banner-content {
        z-index: 2;
    }

    .banner-badge {
        display: inline-block;
        background: rgba(255, 255, 255, 0.15);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 999px;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }

    .banner-content h2 {
        font-size: 2.5rem;
        font-weight: 800;
        color: white;
        margin: 0 0 1.5rem;
        line-height: 1.2;
    }

    .countdown {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .countdown-item {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 1rem;
        padding: 1rem 1.25rem;
        text-align: center;
        min-width: 70px;
    }

    .countdown-item .number {
        display: block;
        font-size: 1.75rem;
        font-weight: 800;
        color: white;
    }

    .countdown-item .label {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .btn-check-out {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        background: white;
        color: #1e3a5f;
        border-radius: 999px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-check-out:hover {
        background: #1f2937;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(31, 41, 55, 0.4);
    }

    .banner-image {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .banner-image img {
        max-width: 350px;
        filter: drop-shadow(0 30px 60px rgba(0, 0, 0, 0.3));
        animation: float 6s ease-in-out infinite;
    }

    /* Products Section */
    .products-section {
        padding: 4rem 5%;
        background: var(--surface);
    }

    body.dark-mode .products-section {
        background: #0f172a;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .products-grid .product-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e5e7eb;
        position: relative;
    }

    body.dark-mode .products-grid .product-card {
        background: #1e293b;
        border-color: rgba(255, 255, 255, 0.1);
    }

    .products-grid .product-card:hover {
        border-color: #3b82f6;
    }

    body.dark-mode .products-grid .product-card:hover {
        border-color: #3b82f6;
    }

    .products-grid .discount-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #ef4444;
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        z-index: 2;
    }

    .products-grid .product-image {
        aspect-ratio: 1/1;
        overflow: hidden;
        background: #f8f8f8;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .products-grid .product-image .category-badge {
        position: absolute;
        top: 10px;
        left: 10px;
        background: #1f2937;
        color: white;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 500;
    }

    body.dark-mode .products-grid .product-image {
        background: #0f172a;
    }

    .products-grid .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .products-grid .product-info {
        padding: 1rem;
    }

    .products-grid .product-name {
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0;
        line-height: 1.4;
    }

    .products-grid .product-name a {
        color: #2d3748;
        text-decoration: none;
    }

    body.dark-mode .products-grid .product-name a {
        color: white;
    }

    .products-grid .product-name a:hover {
        color: #3b82f6;
    }

    .products-grid .product-rating {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .products-grid .product-rating .stars {
        font-size: 0.8rem;
        letter-spacing: 1px;
    }

    .products-grid .product-rating .count {
        color: #6b7280;
        font-size: 0.8rem;
    }

    .products-grid .product-footer {
        padding: 10px;
        border-top: 1px solid #eee;
    }

    body.dark-mode .products-grid .product-footer {
        border-top-color: rgba(255, 255, 255, 0.1);
    }

    .products-grid .product-name {
        font-size: 0.9rem;
        font-weight: 600;
        margin: 0 0 8px 0;
        padding: 0;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .products-grid .product-name a {
        color: #2d3748;
        text-decoration: none;
    }

    .products-grid .product-name a:hover {
        color: #3b82f6;
    }

    body.dark-mode .products-grid .product-name a {
        color: white;
    }

    .products-grid .price-buy-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 0.5rem;
    }

    .products-grid .price-buy-row .product-price {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1a73e8;
        background: none;
        padding: 0;
        margin: 0;
    }

    body.dark-mode .products-grid .price-buy-row .product-price {
        color: white;
    }

    .products-grid .wishlist-form,
    .products-grid .cart-form {
        display: inline-flex;
        margin: 0;
        padding: 0;
    }

    .products-grid .wishlist-btn,
    .products-grid .cart-btn {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 1px solid #e0e0e0;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #666;
    }

    body.dark-mode .products-grid .wishlist-btn,
    body.dark-mode .products-grid .cart-btn {
        background: #1e293b;
        border-color: rgba(255, 255, 255, 0.2);
        color: rgba(255, 255, 255, 0.7);
    }

    .products-grid .wishlist-btn:hover {
        background: #fff0f0;
        border-color: #ffcccc;
        color: #e74c3c;
    }

    .products-grid .cart-btn:hover {
        background: #1f2937;
        border-color: #1f2937;
        color: #fff;
    }

    body.dark-mode .products-grid .cart-btn:hover {
        background: white;
        border-color: white;
        color: #1f2937;
    }

    .products-grid .product-price {
        font-size: 0.95rem;
        font-weight: 700;
        color: #1a73e8;
    }

    body.dark-mode .products-grid .product-price {
        color: #60a5fa;
    }

    .products-grid .btn-buy-now {
        padding: 8px 16px;
        background: #000;
        color: white;
        border: none;
        border-radius: 4px;
        font-size: 0.9rem;
        font-weight: 600;
        text-decoration: none;
        display: inline-block;
        cursor: pointer;
    }

    .products-grid .btn-buy-now:hover {
        background: #3b82f6;
    }

    body.dark-mode .products-grid .btn-buy-now {
        background: #3b82f6;
    }

    body.dark-mode .products-grid .btn-buy-now:hover {
        background: #60a5fa;
    }

    .products-grid .product-image-link {
        text-decoration: none;
        display: block;
    }

    .btn-add-cart {
        width: 100%;
        padding: 0.75rem;
        background: #1f2937;
        color: white;
        border: none;
        border-radius: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-add-cart:hover {
        background: #111827;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(31, 41, 55, 0.3);
    }

    .products-grid .out-of-stock-badge {
        font-size: 0.75rem;
        color: #999;
        background: #f5f5f5;
        padding: 0.3rem 0.6rem;
        border-radius: 0.25rem;
    }

    body.dark-mode .products-grid .out-of-stock-badge {
        background: rgba(255, 255, 255, 0.1);
    }

    .products-footer {
        display: flex;
        justify-content: center;
    }

    .btn-view-all {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 1rem 2.5rem;
        background: white;
        color: #1f2937;
        border: 2px solid #e5e7eb;
        border-radius: 999px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    body.dark-mode .btn-view-all {
        background: #1e293b;
        color: white;
        border-color: rgba(255, 255, 255, 0.2);
    }

    .btn-view-all:hover {
        border-color: #1f2937;
        color: #1f2937;
        transform: translateY(-2px);
    }

    /* Responsive Styles for eTrade */
    @media (max-width: 1200px) {
        .products-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .categories-grid {
            grid-template-columns: repeat(4, 1fr);
        }

        .hero-section {
            grid-template-columns: 1fr 1fr;
        }

        .hero-side-products {
            display: none;
        }
    }

    @media (max-width: 992px) {
        .hero-section {
            grid-template-columns: 1fr;
            text-align: center;
            padding: 2rem 5%;
            min-height: auto;
        }

        .hero-content {
            order: 2;
        }

        .hero-info {
            justify-content: center;
            flex-wrap: wrap;
        }

        .hero-image {
            order: 1;
        }

        .hero-image>img {
            max-width: 300px;
        }

        .hero-title {
            font-size: 2.5rem;
        }

        .featured-banner {
            grid-template-columns: 1fr;
            text-align: center;
        }

        .banner-image {
            order: 1;
        }

        .banner-content {
            order: 2;
        }

        .countdown {
            justify-content: center;
        }

        .products-grid {
            grid-template-columns: repeat(2, 1fr);
        }

        .categories-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }

        .banner-content h2 {
            font-size: 1.75rem;
        }

        .categories-grid {
            grid-template-columns: repeat(4, 1fr);
            gap: 0.75rem;
        }

        .categories-grid .category-item {
            padding: 1rem 0.5rem;
        }

        .categories-grid .category-icon {
            width: 45px;
            height: 45px;
            font-size: 1.25rem;
        }

        .categories-grid .category-item span {
            font-size: 0.75rem;
        }

        .section-header h2 {
            font-size: 1.5rem;
        }

        .products-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
    }

    @media (max-width: 576px) {
        .products-grid {
            grid-template-columns: 1fr;
        }

        .categories-grid {
            grid-template-columns: repeat(3, 1fr);
        }

        .countdown-item {
            padding: 0.75rem;
            min-width: 55px;
        }

        .countdown-item .number {
            font-size: 1.25rem;
        }

        .hero-info {
            flex-direction: column;
            gap: 1.5rem;
        }

        .hero-price-tag {
            display: none;
        }
    }
</style>