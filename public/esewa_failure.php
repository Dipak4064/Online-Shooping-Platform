<?php
require_once __DIR__ . '/../includes/functions.php';
start_session();

$orderId = (int) ($_GET['order_id'] ?? 0);
$order = $orderId ? get_order_by_id($orderId) : null;

if ($order) {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('UPDATE orders SET status = ? WHERE id = ?');
    $stmt->execute(['failed', $orderId]);
}

require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-header">
    <h1>Payment failed</h1>
</section>
<p>Your payment via eSewa could not be verified. Please try again or contact support.</p>
<a class="btn" href="<?php echo BASE_URL; ?>orders.php">Back to orders</a>
<?php require_once __DIR__ . '/../includes/footer.php';
