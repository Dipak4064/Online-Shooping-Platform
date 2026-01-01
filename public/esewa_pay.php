<?php
require_once __DIR__ . '/../includes/functions.php';

$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$product = get_post_by_id($productId);

if (!$product) {
    die("Product not found.");
}

$amount = $product['price'];
$tax_amount = $amount * 0.13;
$total_amount = $amount + $tax_amount;
$transaction_uuid = time() . "-" . $productId;
$pdo = get_db_connection();
start_session();
$userId = $_SESSION['user']['id'] ?? 1;
try {
    $stmt = $pdo->prepare("INSERT INTO transaction (user_id, payment_channel, product_id, amount, uuid_id,transaction_code, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $userId,
        'esewa',
        $productId,
        $total_amount,
        $transaction_uuid,
        null,
        'pending'
    ]);
} catch (PDOException $e) {
    die("Database Error: Could not initiate transaction. " . $e->getMessage());
}

$product_code = "EPAYTEST";
$secret_key = "8gBm/:&EnhH.1/q";

$signature_str = "total_amount=$total_amount,transaction_uuid=$transaction_uuid,product_code=$product_code";
$signature = base64_encode(hash_hmac('sha256', $signature_str, $secret_key, true));

$success_url = "http://localhost:8000/payment/success.php";
$failure_url = "http://localhost:8000/payment/failure.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Redirecting to eSewa...</title>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: #f8fafc;
        }

        .loader {
            text-align: center;
        }

        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #41a124;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>

    <div class="loader">
        <div class="spinner"></div>
        <p>Connecting to eSewa Secure Payment Gateway...</p>
    </div>

    <form id="esewaForm" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
        <input type="hidden" name="amount" value="<?= $amount ?>">
        <input type="hidden" name="tax_amount" value="<?= $tax_amount ?>">
        <input type="hidden" name="total_amount" value="<?= $total_amount ?>">
        <input type="hidden" name="transaction_uuid" value="<?= $transaction_uuid ?>">
        <input type="hidden" name="product_code" value="<?= $product_code ?>">
        <input type="hidden" name="product_service_charge" value="0">
        <input type="hidden" name="product_delivery_charge" value="0">
        <input type="hidden" name="success_url" value="<?= $success_url ?>">
        <input type="hidden" name="failure_url" value="<?= $failure_url ?>">
        <input type="hidden" name="signed_field_names" value="total_amount,transaction_uuid,product_code">
        <input type="hidden" name="signature" value="<?= $signature ?>">
    </form>

    <script>
        // Automatically submit the form so the user doesn't have to click anything
        window.onload = function () {
            document.getElementById('esewaForm').submit();
        };
    </script>
</body>

</html>