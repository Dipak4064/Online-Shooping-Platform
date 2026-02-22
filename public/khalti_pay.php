<?php
/**
 * Khalti Payment Initiation
 * 
 * Uses Khalti e-Payment Gateway API v2 (epayment).
 * Test credentials from: https://test-admin.khalti.com/
 * Docs: https://docs.khalti.com/khalti-epayment/
 */
require_once __DIR__ . '/../includes/functions.php';
start_session();

$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$product = get_post_by_id($productId);

if (!$product) {
    die("Product not found.");
}

$amount = $product['price'];
$tax_amount = $amount * 0.13;
$total_amount = $amount + $tax_amount;

// Khalti expects amount in paisa (1 Rs = 100 paisa)
$amount_in_paisa = (int) round($total_amount * 100);

$transaction_uuid = 'KHALTI-' . time() . '-' . $productId;

$pdo = get_db_connection();
$userId = $_SESSION['user']['id'] ?? 1;

try {
    $stmt = $pdo->prepare("INSERT INTO transaction (user_id, payment_channel, product_id, amount, uuid_id, transaction_code, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $userId,
        'khalti',
        $productId,
        $total_amount,
        $transaction_uuid,
        null,
        'pending'
    ]);
} catch (PDOException $e) {
    die("Database Error: Could not initiate transaction. " . $e->getMessage());
}

// Khalti API endpoint (test vs live)
$khaltiUrl = KHALTI_ENV === 'live'
    ? 'https://khalti.com/api/v2/epayment/initiate/'
    : 'https://a.khalti.com/api/v2/epayment/initiate/';

$payload = json_encode([
    'return_url' => KHALTI_SUCCESS_URL . '?product_id=' . $productId,
    'website_url' => SITE_URL,
    'amount' => $amount_in_paisa,
    'purchase_order_id' => $transaction_uuid,
    'purchase_order_name' => $product['title'],
    'customer_info' => [
        'name' => $_SESSION['user']['name'] ?? 'Customer',
        'email' => $_SESSION['user']['email'] ?? '',
    ],
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
$curlError = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($curlError) {
    die("cURL Error: " . htmlspecialchars($curlError));
}

$data = json_decode($response, true);

if ($httpCode === 200 && !empty($data['payment_url'])) {
    // Redirect user to Khalti payment page
    header('Location: ' . $data['payment_url']);
    exit;
}

// If running on localhost and API call fails, show debug info
echo '<h3>Khalti Payment Initiation Failed</h3>';
echo '<p>HTTP ' . $httpCode . '</p>';
echo '<pre>' . htmlspecialchars($response) . '</pre>';
echo '<a href="' . htmlspecialchars(BASE_URL) . 'products.php">Back to products</a>';
