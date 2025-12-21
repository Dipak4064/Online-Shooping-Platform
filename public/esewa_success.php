<?php
require_once __DIR__ . '/../includes/functions.php';
start_session();

$orderId = (int) ($_GET['order_id'] ?? 0);
$order = $orderId ? get_order_by_id($orderId) : null;
$refId = $_GET['refId'] ?? '';
$amount = $_GET['amt'] ?? '';

if (!$order) {
    echo 'Order not found.';
    exit;
}

$pid = $order['payment_token'];
if (!$pid || !$refId || !$amount) {
    echo 'Incomplete response from eSewa.';
    exit;
}

$verificationUrl = ESEWA_ENV === 'live'
    ? 'https://esewa.com.np/epay/transrec'
    : 'https://uat.esewa.com.np/epay/transrec';

$postData = http_build_query([
    'amt' => $amount,
    'rid' => $refId,
    'pid' => $pid,
    'scd' => ESEWA_MERCHANT_CODE,
]);

$ch = curl_init($verificationUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$curlError = $response === false ? curl_error($ch) : '';
curl_close($ch);

$success = stripos((string) $response, 'success') !== false;

if (!$success && !$response && str_starts_with(SITE_URL, 'http://localhost')) {
    $expectedTotal = number_format((float) $order['total_amount'], 2, '.', '');
    if ($expectedTotal === number_format((float) $amount, 2, '.', '')) {
        $success = true;
    }
}

if ($success) {
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('UPDATE orders SET status = ?, payment_reference = ? WHERE id = ?');
    $stmt->execute(['paid', $refId, $orderId]);
    ensure_tracking_code($orderId);
    clear_cart();
    header('Location: order_success.php?order_id=' . $orderId);
    exit;
}

echo 'Payment verification failed.';
if ($curlError) {
    echo ' (' . htmlspecialchars($curlError) . ')';
}
