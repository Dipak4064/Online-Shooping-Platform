<?php
/**
 * Khalti Payment Success Callback
 *
 * After user completes payment on Khalti, they are redirected here.
 * We verify the payment using Khalti's lookup API, then update the order/transaction.
 * 
 * Khalti sends these query params on success:
 *   pidx, transaction_id, tidx, amount, total_amount, mobile, status, purchase_order_id, purchase_order_name
 */
require_once __DIR__ . '/../includes/functions.php';
start_session();

$pidx = $_GET['pidx'] ?? '';
$status = $_GET['status'] ?? '';
$purchaseOrderId = $_GET['purchase_order_id'] ?? '';
$amount = $_GET['amount'] ?? 0;
$productId = (int) ($_GET['product_id'] ?? 0);

if (!$pidx) {
    die('Invalid callback: missing pidx.');
}

// ─── Verify payment via Khalti Lookup API ───────────────────────────
$lookupUrl = KHALTI_ENV === 'live'
    ? 'https://khalti.com/api/v2/epayment/lookup/'
    : 'https://a.khalti.com/api/v2/epayment/lookup/';

$ch = curl_init($lookupUrl);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode(['pidx' => $pidx]),
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: key ' . KHALTI_SECRET_KEY,
        'Content-Type: application/json',
    ],
]);

$response = curl_exec($ch);
$curlError = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($response, true);
$verified = false;

if ($httpCode === 200 && isset($data['status'])) {
    if ($data['status'] === 'Completed') {
        $verified = true;
    }
}

// Localhost fallback: if Khalti returns status=Completed in query params
if (!$verified && $status === 'Completed' && str_starts_with(SITE_URL, 'http://localhost')) {
    $verified = true;
}

$pdo = get_db_connection();

if ($verified) {
    // Update the transaction record
    $transactionCode = $data['transaction_id'] ?? $pidx;

    $stmt = $pdo->prepare("UPDATE transaction SET transaction_code = ?, status = ? WHERE uuid_id = ?");
    $stmt->execute([$transactionCode, 'completed', $purchaseOrderId]);

    // If there's an associated order (checkout flow), update it too
    if ($purchaseOrderId && str_starts_with($purchaseOrderId, 'ORDER-')) {
        $orderId = (int) str_replace('ORDER-', '', $purchaseOrderId);
        $order = get_order_by_id($orderId);
        if ($order) {
            $stmt = $pdo->prepare('UPDATE orders SET status = ?, payment_reference = ? WHERE id = ?');
            $stmt->execute(['paid', $transactionCode, $orderId]);
            ensure_tracking_code($orderId);
            clear_cart();
            header('Location: order_success.php?order_id=' . $orderId);
            exit;
        }
    }

    // Direct product purchase flow (from payment.php)
    require_once __DIR__ . '/../includes/header.php';
    ?>
    <section class="page-header">
        <h1>Payment Successful!</h1>
    </section>
    <div style="max-width:600px;margin:2rem auto;text-align:center;">
        <p style="font-size:1.2rem;color:#16a34a;">✅ Your Khalti payment has been verified successfully.</p>
        <p><strong>Transaction ID:</strong> <?= htmlspecialchars($transactionCode) ?></p>
        <p><strong>Amount Paid:</strong> Rs. <?= number_format(($data['total_amount'] ?? $amount) / 100, 2) ?></p>
        <a class="btn" href="<?= BASE_URL ?>products.php">Continue Shopping</a>
    </div>
    <?php
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

// Payment verification failed
$stmt = $pdo->prepare("UPDATE transaction SET status = ? WHERE uuid_id = ?");
$stmt->execute(['failed', $purchaseOrderId]);

require_once __DIR__ . '/../includes/header.php';
?>
<section class="page-header">
    <h1>Payment Verification Failed</h1>
</section>
<div style="max-width:600px;margin:2rem auto;text-align:center;">
    <p>Your Khalti payment could not be verified. Please try again or contact support.</p>
    <?php if ($curlError): ?>
        <p style="color:#dc2626;">Error: <?= htmlspecialchars($curlError) ?></p>
    <?php endif; ?>
    <a class="btn" href="<?= BASE_URL ?>products.php">Back to Products</a>
</div>
<?php
require_once __DIR__ . '/../includes/footer.php';
