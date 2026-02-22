<?php
require_once __DIR__ . '/../includes/functions.php';
start_session();

/*
|--------------------------------------------------------------------------
| Get Request Data
|--------------------------------------------------------------------------
*/

$pidx = $_GET['pidx'] ?? '';
$purchaseOrderId = $_GET['purchase_order_id'] ?? '';
$amount = $_GET['amount'] ?? 0;
$status = $_GET['status'] ?? '';
$productId = (int) ($_GET['product_id'] ?? 0);

if (!$pidx) {
    exit('Invalid callback: missing pidx.');
}

/*
|--------------------------------------------------------------------------
| Khalti Lookup Verification
|--------------------------------------------------------------------------
*/

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
$curlError = $response === false ? curl_error($ch) : '';
curl_close($ch);

$data = json_decode($response, true);
$success = isset($data['status']) && $data['status'] === 'Completed';

// Localhost fallback: if Khalti returns status=Completed in query params
if (!$success && $status === 'Completed' && str_starts_with(SITE_URL, 'http://localhost')) {
    $success = true;
}

$pdo = get_db_connection();
$transactionCode = $data['transaction_id'] ?? $pidx;

/*
|--------------------------------------------------------------------------
| Handle Different Flows
|--------------------------------------------------------------------------
*/
if ($purchaseOrderId && str_starts_with($purchaseOrderId, 'ORDER-')) {
    $orderId = (int) str_replace('ORDER-', '', $purchaseOrderId);
    $order = get_order_by_id($orderId);

    if (!$order) {
        exit('Order not found.');
    }

    if ($success) {
        $stmt = $pdo->prepare('UPDATE orders SET status = ?, payment_reference = ? WHERE id = ?');
        $stmt->execute(['paid', $transactionCode, $orderId]);
        ensure_tracking_code($orderId);
        clear_cart();
        header('Location: order_success.php?order_id=' . $orderId);
        exit;
    }

    echo 'Payment verification failed.';
    if ($curlError) {
        echo ' (' . htmlspecialchars($curlError) . ')';
    }
    exit;
}

if ($purchaseOrderId && str_starts_with($purchaseOrderId, 'KHALTI-')) {
    if ($success) {
        $successData = [
            'status' => 'COMPLETE',
            'total_amount' => $amount / 100,
            'transaction_code' => $transactionCode,
            'transaction_uuid' => $purchaseOrderId,
            'payment_method' => 'Khalti',
            'product_code' => '',
        ];
        $encodedData = base64_encode(json_encode($successData));
        header('Location: ../payment/success.php?data=' . $encodedData);
        exit;
    }

    $stmt = $pdo->prepare("UPDATE transaction SET status = ? WHERE uuid_id = ?");
    $stmt->execute(['failed', $purchaseOrderId]);

    header('Location: ../payment/failure.php?msg=verification_failed');
    exit;
}

echo 'Invalid purchase order ID.';
if ($curlError) {
    echo ' (' . htmlspecialchars($curlError) . ')';
}