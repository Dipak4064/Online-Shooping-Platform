<?php
require_once 'includes/db.php';
$pdo = get_db_connection();

try {
    // 1. Disable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0;");
    
    // 2. Drop the table
    $pdo->exec("DROP TABLE IF EXISTS orders;");
    echo "Table 'orders' dropped successfully.<br>";

    // 3. Create the table (Your new SQL)
    $createSql = "CREATE TABLE orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        b_name VARCHAR(255) NOT NULL,
        product_id INT NOT NULL,
        address TEXT NOT NULL,
        message TEXT NULL,
        total_amount DECIMAL(10, 2) NOT NULL,
        status VARCHAR(20) DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_order_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    ) ENGINE=InnoDB;";
    
    $pdo->exec($createSql);
    echo "New 'orders' table created.<br>";

    // 4. Re-enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;");

} catch (PDOException $e) {
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1;"); // Safety reset
    echo "Migration failed: " . $e->getMessage();
}
?>