<?php
ob_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['customer_id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $msg = "";

    try {
        if ($action === 'add') {
            $success = register_user([
                'name' => $name,
                'email' => $email,
                'password' => $password,
                'role' => 'customer'
            ]);

            $msg = $success ? "Customer added successfully!" : "Error: Email already exists.";

        } elseif ($action === 'edit' && $id) {
            $pdo = get_db_connection();

            $stmt = $pdo->prepare("UPDATE users SET name = ?, email = ? WHERE id = ? AND role = 'customer'");
            $stmt->execute([$name, $email, $id]);

            if (!empty($password)) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                $stmt->execute([$hash, $id]);
            }
            $msg = "Customer updated successfully!";

        } elseif ($action === 'delete' && $id) {
            $pdo = get_db_connection();
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ? AND role = 'customer'");
            $stmt->execute([$id]);
            $msg = "Customer deleted successfully!";
        }
    } catch (Exception $e) {
        $msg = "System Error: " . $e->getMessage();
    }

    header("Location: customers.php?msg=" . urlencode($msg));
    exit;
} else {
    header("Location: customers.php");
    exit;
}
ob_end_flush();