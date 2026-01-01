<?php
require_once __DIR__ . '/includes/db.php'; 

$pdo = get_db_connection(); // get the PDO instance

try {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration_name VARCHAR(255) NOT NULL,
            applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "Migrations table checked/created successfully.\n";
} catch (PDOException $e) {
    die("Failed to create migrations table: " . $e->getMessage() . "\n");
}

try {
    $stmt = $pdo->query("SELECT migration_name FROM migrations");
    $applied = $stmt->fetchAll(PDO::FETCH_COLUMN);
} catch (PDOException $e) {
    die("Failed to fetch applied migrations: " . $e->getMessage() . "\n");
}

$migrationFiles = glob(__DIR__ . '/migrations/*.php');

foreach ($migrationFiles as $file) {
    $fileName = basename($file);

    if (!in_array($fileName, $applied)) {
        echo "Applying migration: $fileName\n";

        try {
            include $file;
        } catch (Exception $e) {
            echo "Migration failed: " . $e->getMessage() . "\n";
            continue;
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO migrations (migration_name) VALUES (:name)");
            $stmt->execute(['name' => $fileName]);
            echo "Migration $fileName applied successfully.\n";
        } catch (PDOException $e) {
            echo "Failed to record migration $fileName: " . $e->getMessage() . "\n";
        }
    } else {
        echo "Migration already applied: $fileName\n";
    }
}

echo "All migrations processed.\n";
