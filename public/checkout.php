<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
require_once __DIR__ . '/../includes/header.php';

$items = get_cart_items();
$totals = cart_totals();

if (!$items) {
    echo '<p>Your cart is empty. <a href="products.php">Continue shopping</a>.</p>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$user = current_user();
?>
<section class="page-header">
    <h1>Checkout</h1>
</section>

<section class="checkout-grid">
    <form class="checkout-form" method="post" action="<?php echo BASE_URL; ?>checkout_process.php">
        <h2>Shipping information</h2>
        <label>Name
            <input type="text" name="shipping[name]" value="<?php echo htmlspecialchars($user['name']); ?>" required>
        </label>
        <label>Email
            <input type="email" name="shipping[email]" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </label>
        <label>Phone number
            <input type="tel" name="shipping[phone]" required>
        </label>
        <label>Address
            <input type="text" name="shipping[address]" required>
        </label>
        <label>City
            <input type="text" name="shipping[city]" required>
        </label>
        <label>ZIP / Postal code
            <input type="text" name="shipping[zip]" required>
        </label>

        <h2>Payment method</h2>
        <label class="radio">
            <input type="radio" name="payment_method" value="cod" checked>
            Cash on delivery
        </label>
        <label class="radio">
            <input type="radio" name="payment_method" value="esewa">
            eSewa (digital wallet)
        </label>

        <button type="submit" class="btn">Place order</button>
    </form>

    <aside class="order-summary">
        <h2>Order summary</h2>
        <ul>
            <?php foreach ($items as $item): ?>
                <li>
                    <span><?php echo htmlspecialchars($item['name']); ?> (x<?php echo $item['quantity']; ?>)</span>
                    <span>Rs. <?php echo number_format($item['subtotal'], 2); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
        <p>Subtotal: <strong>Rs. <?php echo number_format($totals['subtotal'], 2); ?></strong></p>
        <p>Shipping: <strong>Rs. <?php echo number_format($totals['shipping'], 2); ?></strong></p>
        <p>Tax: <strong>Rs. <?php echo number_format($totals['tax'], 2); ?></strong></p>
        <p class="total">Total: <strong>Rs. <?php echo number_format($totals['total'], 2); ?></strong></p>
    </aside>
</section>
<?php require_once __DIR__ . '/../includes/footer.php';
