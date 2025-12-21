<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: wishlist.php');
    exit;
}

$user = current_user();
$action = $_POST['action'] ?? '';
$productId = (int) ($_POST['product_id'] ?? 0);

if (!$productId) {
    header('Location: wishlist.php');
    exit;
}

if ($action === 'add') {
    save_wishlist_item((int) $user['id'], $productId);
} elseif ($action === 'remove') {
    remove_wishlist_item((int) $user['id'], $productId);
}

header('Location: wishlist.php');
