<?php
require_once __DIR__ . '/../includes/header.php';
?>

<!-- About Hero Section -->
<section class="about-hero-light">
    <div class="about-hero-content">
        <span class="about-badge">✨ Our Story</span>
        <h1>About <?php echo APP_NAME; ?></h1>
        <p>Your trusted destination for quality products and exceptional shopping experience since 2020</p>
    </div>
</section>

<!-- Our Story Section -->
<section class="about-story-section">
    <div class="about-grid">
        <div class="about-image">
            <img src="https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?w=600&h=450&fit=crop" alt="Shopping Experience">
            <div class="about-image-badge">
                <span class="years">5+</span>
                <span class="text">Years of Trust</span>
            </div>
        </div>
        <div class="about-text">
            <span class="about-label">Who We Are</span>
            <h2>We're Passionate About Delivering Quality</h2>
            <p>At <?php echo APP_NAME; ?>, we believe that online shopping should be simple, enjoyable, and reliable. Founded with a mission to bring the best products directly to your doorstep, we've grown from a small startup to a trusted e-commerce destination serving thousands of happy customers.</p>
            <p>Our team carefully curates each product in our collection, ensuring that you get only the best quality at competitive prices. We partner with trusted brands and suppliers to bring you authentic products every time.</p>
            
            <div class="about-features">
                <div class="about-feature">
                    <span class="feature-icon">✓</span>
                    <span>100% Authentic Products</span>
                </div>
                <div class="about-feature">
                    <span class="feature-icon">✓</span>
                    <span>Secure Payment Options</span>
                </div>
                <div class="about-feature">
                    <span class="feature-icon">✓</span>
                    <span>Fast & Free Shipping</span>
                </div>
                <div class="about-feature">
                    <span class="feature-icon">✓</span>
                    <span>24/7 Customer Support</span>
                </div>
            </div>
            
            <a href="<?php echo BASE_URL; ?>products.php" class="btn-shop-now-about">Shop Now →</a>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section class="why-choose-section">
    <div class="section-header-center">
        <span class="section-badge">💡 Why Choose Us</span>
        <h2>What Makes Us Different</h2>
        <p>We go above and beyond to ensure your shopping experience is nothing short of exceptional</p>
    </div>
    
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon-box">
                <span>🚚</span>
            </div>
            <h3>Free & Fast Delivery</h3>
            <p>Enjoy free shipping on orders over $50. Most orders are delivered within 2-3 business days.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon-box">
                <span>🔒</span>
            </div>
            <h3>Secure Payments</h3>
            <p>Shop with confidence using our secure payment gateway. We support eSewa, cards, and more.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon-box">
                <span>↩️</span>
            </div>
            <h3>Easy Returns</h3>
            <p>Not satisfied? Return any item within 30 days for a full refund. No questions asked.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon-box">
                <span>💬</span>
            </div>
            <h3>24/7 Support</h3>
            <p>Our friendly customer support team is always ready to help you with any questions.</p>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section-light">
    <div class="stats-grid-light">
        <div class="stat-item-light">
            <span class="stat-number-light">10K+</span>
            <span class="stat-label-light">Happy Customers</span>
        </div>
        <div class="stat-item-light">
            <span class="stat-number-light">5K+</span>
            <span class="stat-label-light">Products Available</span>
        </div>
        <div class="stat-item-light">
            <span class="stat-number-light">50+</span>
            <span class="stat-label-light">Brand Partners</span>
        </div>
        <div class="stat-item-light">
            <span class="stat-number-light">99%</span>
            <span class="stat-label-light">Satisfaction Rate</span>
        </div>
    </div>
</section>

<!-- Our Promise Section -->
<section class="promise-section">
    <div class="promise-grid">
        <div class="promise-content">
            <span class="about-label">Our Promise</span>
            <h2>Shopping Made Simple & Secure</h2>
            <p>We understand that trust is earned. That's why we're committed to transparency in everything we do – from honest product descriptions to clear pricing with no hidden fees.</p>
            <p>Every purchase is protected by our buyer guarantee. If something goes wrong, we'll make it right.</p>
            
            <div class="promise-list">
                <div class="promise-item">
                    <span class="promise-check">✓</span>
                    <div>
                        <strong>Quality Guarantee</strong>
                        <p>Every product is inspected before shipping</p>
                    </div>
                </div>
                <div class="promise-item">
                    <span class="promise-check">✓</span>
                    <div>
                        <strong>Best Price Promise</strong>
                        <p>Competitive prices with regular discounts</p>
                    </div>
                </div>
                <div class="promise-item">
                    <span class="promise-check">✓</span>
                    <div>
                        <strong>Secure Checkout</strong>
                        <p>Your data is protected with SSL encryption</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="promise-image">
            <img src="https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=600&h=500&fit=crop" alt="Secure Shopping">
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="about-cta-light">
    <div class="cta-content-light">
        <h2>Ready to Start Shopping?</h2>
        <p>Join thousands of satisfied customers and discover amazing products at unbeatable prices.</p>
        <div class="cta-buttons">
            <a href="<?php echo BASE_URL; ?>products.php" class="btn-cta-primary">Browse Products</a>
            <a href="/public/create_store.php" class="btn-cta-secondary">Create Account</a>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
<!-- <section class="about-cta-banner">
    <div class="cta-overlay"></div>
    <div class="cta-content">
        <span class="cta-subtitle">Shop With Us</span>
        <h2>We Are Always Ready To<br>Serve You Better</h2>
        <a href="<?php echo BASE_URL; ?>products.php" class="btn-cta-start">Get Started</a>
    </div>
</section> -->

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
