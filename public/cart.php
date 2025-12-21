<?php
require_once __DIR__ . '/../includes/header.php';

$items = get_cart_items();
$totals = cart_totals();
?>
<section class="page-header">
    <h1>Your Cart</h1>
</section>

<?php if (!$items): ?>
    <p>Your cart is empty.</p>
<?php else: ?>
    <form method="post" action="<?php echo BASE_URL; ?>cart_actions.php">
        <input type="hidden" name="action" value="update">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Subtotal</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr>
                        <td>
                            <a href="<?php echo BASE_URL; ?>product.php?slug=<?php echo urlencode($item['slug']); ?>">
                                <?php echo htmlspecialchars($item['name']); ?>
                            </a>
                        </td>
                        <td>Rs. <?php echo number_format($item['price'], 2); ?></td>
                        <td>
                            <input type="number" min="1" name="quantities[<?php echo $item['id']; ?>]" value="<?php echo $item['quantity']; ?>">
                        </td>
                        <td>Rs. <?php echo number_format($item['subtotal'], 2); ?></td>
                        <td>
                            <button class="link" name="remove" value="<?php echo $item['id']; ?>">Remove</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="cart-actions">
            <button type="submit" class="btn">Update Cart</button>
            <a class="btn btn-secondary" href="<?php echo BASE_URL; ?>checkout.php">Checkout</a>
        </div>
    </form>
    <div class="cart-summary">
        <h2>Summary</h2>
        <p>Subtotal: Rs. <?php echo number_format($totals['subtotal'], 2); ?></p>
        <p>Shipping: Rs. <?php echo number_format($totals['shipping'], 2); ?></p>
        <p>Tax: Rs. <?php echo number_format($totals['tax'], 2); ?></p>
        <p><strong>Total: Rs. <?php echo number_format($totals['total'], 2); ?></strong></p>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php';
