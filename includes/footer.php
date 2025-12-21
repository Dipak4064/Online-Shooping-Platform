</div>
</main>
<footer class="site-footer">
    <div class="container footer-inner">
        <p>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</p>
        <form class="newsletter-form" method="post" action="<?php echo SITE_URL; ?>newsletter.php">
            <label for="newsletter-email">Subscribe for offers</label>
            <input type="email" id="newsletter-email" name="email" placeholder="Enter your email" required>
            <button type="submit">Subscribe</button>
        </form>
    </div>
</footer>
</body>

</html>