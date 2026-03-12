<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$orderId = $_GET['id'] ?? null;

if (!$orderId) {
    http_response_code(400);
    echo 'Order ID missing';
    exit;
}

if (sending_product((int) $orderId)) {
    echo 'success';
} else {
    http_response_code(500);
    echo 'Failed to update order';
}