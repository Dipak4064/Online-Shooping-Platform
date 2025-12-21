<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: posts.php');
    exit;
}

$postId = (int) ($_POST['post_id'] ?? 0);
$user = current_user();

if ($postId) {
    delete_post($postId, (int) $user['id']);
}

header('Location: posts.php?status=deleted');
