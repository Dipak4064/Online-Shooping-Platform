<?php
require_once __DIR__ . '/../includes/header.php';

$slug = $_GET['slug'] ?? '';
$product = $slug ? get_product_by_slug($slug) : null;

if (!$product) {
    http_response_code(404);
    echo '<p>Product not found.</p>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$related = get_related_products((int) $product['category_id'], (int) $product['id']);
$product_image = product_image_url($product);
?>

<!-- Breadcrumb -->
<nav class="breadcrumb-nav">
    <a href="<?php echo BASE_URL; ?>index.php">← Home</a>
    <span>/</span>
    <span>Product details</span>
</nav>

<!-- Product Detail Section -->
<section class="product-detail-modern">
    <!-- Product Gallery -->
    <div class="product-gallery-modern">
        <div class="main-image">
            <img src="<?php echo htmlspecialchars($product_image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" id="mainProductImage">
        </div>
        <div class="thumbnail-strip">
            <div class="thumbnail active" onclick="changeMainImage(this)">
                <img src="<?php echo htmlspecialchars($product_image); ?>" alt="View 1">
            </div>
            <div class="thumbnail" onclick="changeMainImage(this)">
                <img src="<?php echo htmlspecialchars($product_image); ?>" alt="View 2">
            </div>
            <div class="thumbnail" onclick="changeMainImage(this)">
                <img src="<?php echo htmlspecialchars($product_image); ?>" alt="View 3">
            </div>
        </div>
    </div>

    <!-- Product Info -->
    <div class="product-info-modern">
        <span class="product-category-badge"><?php echo htmlspecialchars($product['category_name']); ?></span>
        <h1 class="product-title-modern"><?php echo htmlspecialchars($product['name']); ?></h1>
        <p class="product-price-modern">$<?php echo number_format($product['price'], 2); ?></p>
        
        <div class="delivery-notice">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <polyline points="12,6 12,12 16,14"/>
            </svg>
            <span>Order in <strong>02:30:25</strong> to get next day delivery</span>
        </div>

        <!-- Size Selector -->
        <div class="size-selector">
            <label>Select Size</label>
            <div class="size-options">
                <button class="size-btn active">S</button>
                <button class="size-btn">M</button>
                <button class="size-btn">L</button>
                <button class="size-btn disabled">XL</button>
                <button class="size-btn">XXL</button>
            </div>
        </div>

        <!-- Add to Cart -->
        <?php if ($product['stock'] > 0): ?>
            <div class="add-to-cart-modern">
                <form method="post" action="<?php echo BASE_URL; ?>cart_actions.php" class="cart-form-modern">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn-add-to-cart-modern">Add to Cart</button>
                </form>
                <?php if ($user): ?>
                    <form method="post" action="<?php echo BASE_URL; ?>wishlist_actions.php">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                        <button type="submit" class="btn-wishlist-modern">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/>
                            </svg>
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="out-of-stock-modern">
                <span>Out of Stock</span>
            </div>
        <?php endif; ?>

        <!-- Accordion Sections -->
        <div class="product-accordions">
            <!-- Description & Fit -->
            <div class="accordion-item">
                <button class="accordion-header" onclick="toggleAccordion(this)">
                    <span>Description & Fit</span>
                    <svg class="accordion-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6,9 12,15 18,9"/>
                    </svg>
                </button>
                <div class="accordion-content active">
                    <p><?php echo nl2br(htmlspecialchars($product['description'] ?: 'Premium quality product with excellent craftsmanship. Designed for comfort and style, featuring high-quality materials and attention to detail.')); ?></p>
                </div>
            </div>

            <!-- Shipping -->
            <div class="accordion-item">
                <button class="accordion-header" onclick="toggleAccordion(this)">
                    <span>Shipping</span>
                    <svg class="accordion-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6,9 12,15 18,9"/>
                    </svg>
                </button>
                <div class="accordion-content">
                    <div class="shipping-grid">
                        <div class="shipping-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/>
                                <path d="M12 6v6l4 2"/>
                            </svg>
                            <div>
                                <span class="label">Discount</span>
                                <span class="value">Disc 50%</span>
                            </div>
                        </div>
                        <div class="shipping-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="1" y="3" width="15" height="13"/>
                                <polygon points="16,8 20,8 23,11 23,16 16,16 16,8"/>
                                <circle cx="5.5" cy="18.5" r="2.5"/>
                                <circle cx="18.5" cy="18.5" r="2.5"/>
                            </svg>
                            <div>
                                <span class="label">Package</span>
                                <span class="value">Regular Package</span>
                            </div>
                        </div>
                        <div class="shipping-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                <line x1="16" y1="2" x2="16" y2="6"/>
                                <line x1="8" y1="2" x2="8" y2="6"/>
                                <line x1="3" y1="10" x2="21" y2="10"/>
                            </svg>
                            <div>
                                <span class="label">Delivery Time</span>
                                <span class="value">3-4 Working Days</span>
                            </div>
                        </div>
                        <div class="shipping-item">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                                <polyline points="22,4 12,14.01 9,11.01"/>
                            </svg>
                            <div>
                                <span class="label">Estimation Arrive</span>
                                <span class="value">10 - 12 October 2024</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Rating & Reviews Section -->
<section class="reviews-section-modern">
    <h2 class="section-title-modern">Rating & Reviews</h2>
    <div class="reviews-container">
        <!-- Rating Summary -->
        <div class="rating-summary">
            <div class="rating-big">
                <span class="rating-number">4.5</span>
                <span class="rating-max">/5</span>
            </div>
            <p class="review-count">(50 New Reviews)</p>
            <div class="rating-bars">
                <div class="rating-bar-item">
                    <span class="star-label">⭐ 5</span>
                    <div class="bar"><div class="bar-fill" style="width: 70%;"></div></div>
                </div>
                <div class="rating-bar-item">
                    <span class="star-label">⭐ 4</span>
                    <div class="bar"><div class="bar-fill" style="width: 20%;"></div></div>
                </div>
                <div class="rating-bar-item">
                    <span class="star-label">⭐ 3</span>
                    <div class="bar"><div class="bar-fill" style="width: 5%;"></div></div>
                </div>
                <div class="rating-bar-item">
                    <span class="star-label">⭐ 2</span>
                    <div class="bar"><div class="bar-fill" style="width: 3%;"></div></div>
                </div>
                <div class="rating-bar-item">
                    <span class="star-label">⭐ 1</span>
                    <div class="bar"><div class="bar-fill" style="width: 2%;"></div></div>
                </div>
            </div>
        </div>

        <!-- Review Card -->
        <div class="review-card-modern">
            <div class="review-header">
                <span class="reviewer-name">Alex Mathio</span>
                <div class="review-stars">⭐⭐⭐⭐⭐</div>
                <span class="review-date">13 Oct 2024</span>
            </div>
            <p class="review-text">"Great product quality and fast delivery. The material feels premium and the fit is perfect. Highly recommend for anyone looking for comfortable everyday wear."</p>
            <div class="reviewer-avatar">
                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=50&h=50&fit=crop&crop=face" alt="Reviewer">
            </div>
            <button class="review-nav-btn">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9,18 15,12 9,6"/>
                </svg>
            </button>
        </div>
    </div>
</section>

<!-- Related Products Section -->
<section class="related-section-modern">
    <h2 class="section-title-modern centered">You might also like</h2>
    <div class="related-grid-modern">
        <?php foreach ($related as $item): ?>
            <a href="<?php echo BASE_URL; ?>product.php?slug=<?php echo urlencode($item['slug']); ?>" class="related-card-modern">
                <div class="related-image">
                    <img src="<?php echo htmlspecialchars(product_image_url($item)); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                </div>
                <div class="related-info">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                    <div class="related-rating">
                        <span class="stars">⭐⭐⭐⭐⭐</span>
                        <span class="rating-value">4.0/5</span>
                    </div>
                    <div class="related-price">
                        <span class="current">$<?php echo number_format($item['price'], 2); ?></span>
                        <?php if (rand(0, 1)): ?>
                            <span class="original">$<?php echo number_format($item['price'] * 1.2, 2); ?></span>
                            <span class="discount-tag">-20%</span>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<script>
function changeMainImage(thumbnail) {
    const mainImage = document.getElementById('mainProductImage');
    const img = thumbnail.querySelector('img');
    mainImage.src = img.src;
    
    // Update active state
    document.querySelectorAll('.thumbnail').forEach(t => t.classList.remove('active'));
    thumbnail.classList.add('active');
}

function toggleAccordion(button) {
    const content = button.nextElementSibling;
    const icon = button.querySelector('.accordion-icon');
    
    content.classList.toggle('active');
    icon.style.transform = content.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0)';
}

// Size selector
document.querySelectorAll('.size-btn:not(.disabled)').forEach(btn => {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
