<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($postId) {
    if (soft_delete_post($postId, (int)current_user()['id'])) {
        header("Location: my_store.php?status=deleted");
        exit;
    }
}
header("Location: my_store.php?error=delete_failed");
exit;