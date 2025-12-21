<?php
require_once __DIR__ . '/../includes/functions.php';

start_session();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: cart.php');
    exit;
}

action_router($_POST['action'] ?? '');

function action_router(string $action): void
{
    switch ($action) {
        case 'add':
            handle_add();
            break;
        case 'update':
            handle_update();
            break;
        default:
            header('Location: cart.php');
            exit;
    }
}

function handle_add(): void
{
    $productId = (int) ($_POST['product_id'] ?? 0);
    $quantity = (int) ($_POST['quantity'] ?? 1);

    if ($productId && $quantity > 0) {
        $product = get_product_by_id($productId);
        if ($product && $product['is_active'] && $product['stock'] >= $quantity) {
            add_to_cart($productId, $quantity);
        }
    }

    header('Location: cart.php');
    exit;
}

function handle_update(): void
{
    $removedId = isset($_POST['remove']) ? (int) $_POST['remove'] : 0;
    if ($removedId) {
        remove_from_cart($removedId);
    }

    $quantities = $_POST['quantities'] ?? [];
    foreach ($quantities as $productId => $qty) {
        if ($removedId && (int) $productId === $removedId) {
            continue;
        }
        update_cart_quantity((int) $productId, (int) $qty);
    }

    header('Location: cart.php');
    exit;
}
