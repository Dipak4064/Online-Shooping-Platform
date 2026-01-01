<?php
require_once 'includes/db.php';

try {
    $sql = "CREATE TABLE IF NOT EXISTS transaction (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        payment_channel VARCHAR(50) NOT NULL,
        product_id INT NOT NULL,
        amount DECIMAL(10, 2) NOT NULL,
        uuid_id VARCHAR(100) NOT NULL UNIQUE,
        transaction_code VARCHAR(100) NULL,
        status VARCHAR(20) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        CONSTRAINT fk_product FOREIGN KEY (product_id) REFERENCES posts(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;";

    $pdo->exec($sql);

    echo "Migration applied: 'transaction' table created successfully with foreign key constraints.\n";

} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
?>