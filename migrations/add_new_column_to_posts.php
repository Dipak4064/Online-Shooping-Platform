<?php
require_once 'includes/db.php'; 

try {
    $sql = "ALTER TABLE posts 
            ADD COLUMN price DECIMAL(10,2) DEFAULT NULL AFTER user_id,
            ADD COLUMN product_type VARCHAR(100) DEFAULT NULL AFTER title,
            ADD COLUMN deleted_at TIMESTAMP NULL DEFAULT NULL AFTER updated_at;";

    $pdo->exec($sql);
    echo "Migration applied: product_type and deleted_at added to posts table.\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
