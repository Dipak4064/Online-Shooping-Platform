<?php
require_once __DIR__ . '/header.php';

$customers = admin_get_customers();
?>
<section class="page-header">
    <h1>Customers</h1>
</section>
<table class="orders-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Joined</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($customers as $customer): ?>
            <tr>
                <td><?php echo htmlspecialchars($customer['name']); ?></td>
                <td><?php echo htmlspecialchars($customer['email']); ?></td>
                <td><?php echo htmlspecialchars($customer['created_at']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php require_once __DIR__ . '/footer.php';
