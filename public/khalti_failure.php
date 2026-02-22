<?php
/**
 * Khalti Payment Failure / Cancellation Callback
 *
 * User is redirected here if they cancel or the payment fails on Khalti's side.
 */
require_once __DIR__ . '/../includes/functions.php';
start_session();

$purchaseOrderId = $_GET['purchase_order_id'] ?? '';

$pdo = get_db_connection();

// Update transaction status to failed
if ($purchaseOrderId) {
    $stmt = $pdo->prepare("UPDATE transaction SET status = ? WHERE uuid_id = ?");
    $stmt->execute(['failed', $purchaseOrderId]);
}

// If this was from the checkout flow, mark the order as failed too
if ($purchaseOrderId && str_starts_with($purchaseOrderId, 'ORDER-')) {
    $orderId = (int) str_replace('ORDER-', '', $purchaseOrderId);
    $order = get_order_by_id($orderId);
    if ($order) {
        $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
        $stmt->execute(['failed', $orderId]);
    }
}

require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-header">
    <h1>Payment Failed</h1>
</section>
<div style="max-width:600px;margin:2rem auto;text-align:center;">
    <p>Your payment via Khalti could not be completed. You may have cancelled the payment or an error occurred.</p>
    <p>Please try again or choose a different payment method.</p>
    <a class="btn" href="<?= BASE_URL ?>orders.php">Back to Orders</a>
    <a class="btn" href="<?= BASE_URL ?>products.php" style="margin-left:0.5rem;">Continue Shopping</a>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
