<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
require_once __DIR__ . '/../includes/header.php';

$user = current_user();
$items = get_wishlist((int) $user['id']);
?>
<section class="page-header">
    <h1>Your Wishlist</h1>
</section>
<?php if (!$items): ?>
    <p>Your wishlist is empty.</p>
<?php else: ?>
    <div class="grid products-grid">
        <?php foreach ($items as $item): ?>
            <div class="card product-card">
                <a href="<?php echo BASE_URL; ?>product.php?slug=<?php echo urlencode($item['slug']); ?>">
                    <img src="<?php echo htmlspecialchars(product_image_url($item)); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>">
                    <h3><?php echo htmlspecialchars($item['name']); ?></h3>
                </a>
                <p class="product-price">Rs. <?php echo number_format($item['price'], 2); ?></p>
                <form method="post" action="<?php echo BASE_URL; ?>cart_actions.php">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <button type="submit" class="btn">Add to cart</button>
                </form>
                <form method="post" action="<?php echo BASE_URL; ?>wishlist_actions.php">
                    <input type="hidden" name="action" value="remove">
                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                    <button type="submit" class="link">Remove</button>
                </form>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php';
