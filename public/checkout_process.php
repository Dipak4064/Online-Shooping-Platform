<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: checkout.php');
    exit;
}

$items = get_cart_items();
if (!$items) {
    header('Location: cart.php');
    exit;
}

$totals = cart_totals();
$shipping = array_map('trim', $_POST['shipping'] ?? []);
$paymentMethod = $_POST['payment_method'] ?? 'cod';

if (!isset($shipping['name'], $shipping['phone'], $shipping['address'], $shipping['city'], $shipping['zip'])) {
    header('Location: checkout.php');
    exit;
}

$user = current_user();

try {
    $orderId = create_order((int) $user['id'], $items, $totals, $shipping, $paymentMethod);
    $trackingCode = ensure_tracking_code($orderId);
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Failed to place order. Please try again.';
    exit;
}

if ($paymentMethod === 'cod') {
    clear_cart();
    header('Location: order_success.php?order_id=' . $orderId);
    exit;
}

if ($paymentMethod === 'esewa') {
    $pid = 'ORDER-' . $orderId;
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('UPDATE orders SET payment_token = ? WHERE id = ?');
    $stmt->execute([$pid, $orderId]);

    $esewaUrl = ESEWA_ENV === 'live'
        ? 'https://esewa.com.np/epay/main'
        : 'https://uat.esewa.com.np/epay/main';

    $su = ESEWA_SUCCESS_URL . '?order_id=' . $orderId;
    $fu = ESEWA_FAILURE_URL . '?order_id=' . $orderId;

    $format = fn(float $amount) => number_format($amount, 2, '.', '');

    echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8"><title>Redirecting to eSewa</title></head><body>';
    echo '<p>Redirecting to eSewa, please wait...</p>';
    echo '<form id="esewaForm" method="post" action="' . htmlspecialchars($esewaUrl) . '">';
    echo '<input type="hidden" name="amt" value="' . $format($totals['subtotal']) . '">';
    echo '<input type="hidden" name="txAmt" value="' . $format($totals['tax']) . '">';
    echo '<input type="hidden" name="psc" value="0">';
    echo '<input type="hidden" name="pdc" value="' . $format($totals['shipping']) . '">';
    echo '<input type="hidden" name="tAmt" value="' . $format($totals['total']) . '">';
    echo '<input type="hidden" name="scd" value="' . htmlspecialchars(ESEWA_MERCHANT_CODE) . '">';
    echo '<input type="hidden" name="pid" value="' . htmlspecialchars($pid) . '">';
    echo '<input type="hidden" name="su" value="' . htmlspecialchars($su) . '">';
    echo '<input type="hidden" name="fu" value="' . htmlspecialchars($fu) . '">';
    echo '</form>';
    echo '<script>document.getElementById("esewaForm").submit();</script>';
    echo '</body></html>';
    exit;
}

if ($paymentMethod === 'khalti') {
    $purchaseOrderId = 'ORDER-' . $orderId;
    $pdo = get_db_connection();
    $stmt = $pdo->prepare('UPDATE orders SET payment_token = ? WHERE id = ?');
    $stmt->execute([$purchaseOrderId, $orderId]);

    // Khalti expects amount in paisa (1 Rs = 100 paisa)
    $amountInPaisa = (int) round($totals['total'] * 100);

    $khaltiUrl = KHALTI_ENV === 'live'
        ? 'https://khalti.com/api/v2/epayment/initiate/'
        : 'https://a.khalti.com/api/v2/epayment/initiate/';

    $payload = json_encode([
        'return_url' => KHALTI_SUCCESS_URL,
        'website_url' => SITE_URL,
        'amount' => $amountInPaisa,
        'purchase_order_id' => $purchaseOrderId,
        'purchase_order_name' => 'Order #' . $orderId,
    ]);

    $ch = curl_init($khaltiUrl);
    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: key ' . KHALTI_SECRET_KEY,
            'Content-Type: application/json',
        ],
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $data = json_decode($response, true);

    if ($httpCode === 200 && !empty($data['payment_url'])) {
        header('Location: ' . $data['payment_url']);
        exit;
    }

    // Fallback: API initiation failed
    echo 'Khalti payment initiation failed. ';
    echo '<pre>' . htmlspecialchars($response) . '</pre>';
    echo '<a href="' . htmlspecialchars(BASE_URL) . 'orders.php">Back to orders</a>';
    exit;
}

header('Location: checkout.php');
