<?php
require_once __DIR__ . '/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $orderId = (int) ($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';
    if ($orderId && $status) {
        $updated = admin_update_order_status($orderId, $status);
        $message = $updated ? 'Order updated.' : 'Could not update order.';
    }
}

$orders = admin_get_orders();
$statuses = ['pending', 'paid', 'processing', 'shipped', 'completed', 'failed', 'cancelled'];
?>
<section class="page-header">
    <h1>Orders</h1>
</section>
<?php if ($message): ?>
    <p class="success"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
<table class="orders-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Status</th>
            <th>Tracking</th>
            <th>Date</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($orders as $order): ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                <td>Rs. <?php echo number_format($order['total_amount'], 2); ?></td>
                <td><?php echo htmlspecialchars($order['status']); ?></td>
                <td><?php echo htmlspecialchars($order['tracking_code'] ?? ''); ?></td>
                <td><?php echo htmlspecialchars($order['created_at']); ?></td>
                <td>
                    <form method="post" style="display:flex; gap:0.5rem; align-items:center;">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                        <select name="status" style="padding:0.5rem; border-radius:0.5rem; border:1px solid var(--border);">
                            <?php foreach ($statuses as $status): ?>
                                <option value="<?php echo $status; ?>" <?php echo $order['status'] === $status ? 'selected' : ''; ?>><?php echo ucfirst($status); ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button class="btn" type="submit">Update</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once __DIR__ . '/footer.php';
