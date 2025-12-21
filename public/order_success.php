<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
require_once __DIR__ . '/../includes/header.php';

$orderId = (int) ($_GET['order_id'] ?? 0);
$order = $orderId ? get_order_by_id($orderId) : null;

if (!$order || (int) $order['user_id'] !== (int) current_user()['id']) {
    echo '<p>Order not found.</p>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$items = get_order_items($orderId);
?>
<section class="page-header">
    <h1>Thank you for your order!</h1>
</section>
<div class="order-detail">
    <p>Order ID: <?php echo htmlspecialchars($order['id']); ?></p>
    <p>Status: <?php echo htmlspecialchars($order['status']); ?></p>
    <p>Tracking code: <?php echo htmlspecialchars($order['tracking_code']); ?></p>
    <h2>Items</h2>
    <ul>
        <?php foreach ($items as $item): ?>
            <li><?php echo htmlspecialchars($item['name']); ?> x<?php echo $item['quantity']; ?> - Rs. <?php echo number_format($item['total_price'], 2); ?></li>
        <?php endforeach; ?>
    </ul>
    <p>Total paid: Rs. <?php echo number_format($order['total_amount'], 2); ?></p>
    <a class="btn" href="<?php echo BASE_URL; ?>orders.php">View all orders</a>
</div>
<?php require_once __DIR__ . '/../includes/footer.php';
