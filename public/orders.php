<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();
require_once __DIR__ . '/../includes/header.php';

$user = current_user();
$orders = get_orders_by_user((int) $user['id']);
?>
<section class="page-header">
    <h1>Your Orders</h1>
</section>
<?php if (!$orders): ?>
    <p>No orders placed yet.</p>
<?php else: ?>
    <table class="orders-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Status</th>
                <th>Total</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo htmlspecialchars($order['id']); ?></td>
                    <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                    <td><?php echo htmlspecialchars($order['status']); ?></td>
                    <td>Rs. <?php echo number_format($order['total_amount'], 2); ?></td>
                    <td><a href="<?php echo BASE_URL; ?>order_success.php?order_id=<?php echo $order['id']; ?>">View</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php';
