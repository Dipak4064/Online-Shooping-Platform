</div>
</main>
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <!-- Brand Column -->
            <div class="footer-col footer-brand">
                <a href="<?php echo SITE_URL; ?>index.php" class="footer-logo">
                    <span class="logo-icon">🛒</span>
                    <span class="logo-text"><?php echo APP_NAME; ?></span>
                </a>
                <p class="footer-tagline">Your one-stop destination for quality products at great prices.</p>
                <div class="footer-social">
                    <a href="#" class="social-link" aria-label="Facebook">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                        </svg>
                    </a>
                    <a href="#" class="social-link" aria-label="Instagram">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5" />
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5" />
                        </svg>
                    </a>
                    <a href="#" class="social-link" aria-label="Twitter">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                        </svg>
                    </a>
                    <a href="#" class="social-link" aria-label="YouTube">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="footer-col">
                <h4 class="footer-heading">Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo SITE_URL; ?>index.php">Home</a></li>
                    <li><a href="<?php echo SITE_URL; ?>products.php">All Products</a></li>
                    <!-- <li><a href="<?php echo SITE_URL; ?>posts.php">Marketplace</a></li> -->
                    <li><a href="<?php echo SITE_URL; ?>about.php">About Us</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="footer-col">
                <h4 class="footer-heading">Customer Service</h4>
                <ul class="footer-links">
                    <li><a href="<?php echo SITE_URL; ?>orders.php">Track Order</a></li>
                    <li><a href="<?php echo SITE_URL; ?>wishlist.php">Wishlist</a></li>
                    <!-- <li><a href="<?php echo SITE_URL; ?>cart.php">Shopping Cart</a></li> -->
                    <li><a href="<?php echo SITE_URL; ?>profile.php">My Account</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="footer-col footer-newsletter">
                <h4 class="footer-heading">Stay Updated</h4>
                <p class="newsletter-text">Subscribe to get exclusive offers and updates.</p>
                <form class="newsletter-form" method="post" action="<?php echo SITE_URL; ?>newsletter.php">
                    <div class="newsletter-input-group">
                        <input type="email" name="email" placeholder="Enter your email" required>
                        <button type="submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <line x1="22" y1="2" x2="11" y2="13" />
                                <polygon points="22 2 15 22 11 13 2 9 22 2" />
                            </svg>
                        </button>
                    </div>
                </form>
                <div class="payment-methods">
                    <span class="payment-label">We Accept</span>
                    <div class="payment-icons">
                        <span class="payment-icon">💳</span>
                        <span class="payment-icon">🏧</span>
                        <span class="payment-icon">📱</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</p>
            <div class="footer-bottom-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Refund Policy</a>
            </div>
        </div>
    </div>
</footer>

<style>
    .site-footer {
        background: linear-gradient(180deg, #1f2937 0%, #111827 100%);
        color: #e5e7eb;
        padding: 60px 0 0;
        margin-top: 60px;
    }

    .footer-grid {
        display: grid;
        grid-template-columns: 1.5fr 1fr 1fr 1.5fr;
        gap: 40px;
        padding-bottom: 40px;
        border-bottom: 1px solid #374151;
    }

    .footer-col {
        display: flex;
        flex-direction: column;
    }

    .footer-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: #fff;
        font-size: 24px;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .footer-logo .logo-icon {
        font-size: 28px;
    }

    .footer-tagline {
        color: #999;
        font-size: 1rem;
        line-height: 1.6;
        margin-bottom: 20px;
    }

    .footer-social {
        display: flex;
        gap: 12px;
    }

    .social-link {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #374151;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        transition: all 0.3s ease;
    }

    .social-link:hover {
        background: #ff9900;
        color: #fff;
        transform: translateY(-3px);
    }

    .footer-heading {
        color: #fff;
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 20px;
        position: relative;
    }

    .footer-links {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .footer-links li {
        margin-bottom: 12px;
    }

    .footer-links a {
        color: #999;
        text-decoration: none;
        font-size: 1rem;
        transition: color 0.3s ease;
    }

    .footer-links a:hover {
        color: #ff9900;
    }

    .newsletter-text {
        color: #999;
        font-size: 1rem;
        margin-bottom: 15px;
    }

    .newsletter-form .newsletter-input-group {
        display: flex;
        background: #374151;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #4b5563;
    }

    .newsletter-form input {
        flex: 1;
        padding: 12px 15px;
        border: none;
        background: transparent;
        color: #fff;
        font-size: 1rem;
        outline: none;
    }

    .newsletter-form input::placeholder {
        color: #777;
    }

    .newsletter-form button {
        padding: 12px 18px;
        background: #ff9900;
        border: none;
        color: #fff;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .newsletter-form button:hover {
        background: #e47911;
    }

    .payment-methods {
        margin-top: 20px;
    }

    .payment-label {
        color: #999;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 8px;
    }

    .payment-icons {
        display: flex;
        gap: 10px;
    }

    .payment-icon {
        font-size: 24px;
    }

    .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 25px 0;
        flex-wrap: wrap;
        gap: 15px;
    }

    .footer-bottom p {
        color: #999;
        font-size: 1rem;
        margin: 0;
    }

    .footer-bottom-links {
        display: flex;
        gap: 25px;
    }

    .footer-bottom-links a {
        color: #999;
        text-decoration: none;
        font-size: 1rem;
        transition: color 0.3s ease;
    }

    .footer-bottom-links a:hover {
        color: #ff9900;
    }

    @media (max-width: 992px) {
        .footer-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .site-footer {
            padding: 40px 0 0;
        }

        .footer-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .footer-bottom {
            flex-direction: column;
            text-align: center;
        }

        .footer-bottom-links {
            justify-content: center;
        }
    }
</style>
</body>

</html>