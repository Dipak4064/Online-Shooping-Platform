<?php
require_once __DIR__ . '/../includes/header.php';

$result = null;
$code = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['tracking_code'] ?? '');
    if ($code) {
        $result = track_order($code);
    }
}
?>
<section class="page-header">
    <h1>Track your order</h1>
</section>
<form class="tracking-form" method="post">
    <input type="text" name="tracking_code" value="<?php echo htmlspecialchars($code); ?>" placeholder="Enter tracking code" required>
    <button type="submit" class="btn">Track</button>
</form>
<?php if ($result): ?>
    <div class="tracking-result">
        <p>Status: <strong><?php echo htmlspecialchars($result['status']); ?></strong></p>
        <p>Placed on: <?php echo htmlspecialchars($result['created_at']); ?></p>
        <p>Total: Rs. <?php echo number_format($result['total_amount'], 2); ?></p>
    </div>
<?php elseif ($result === null && !$code): ?>
    <p>Enter your tracking code to see the latest status.</p>
<?php elseif ($code): ?>
    <p>No order found for this tracking code.</p>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php';
