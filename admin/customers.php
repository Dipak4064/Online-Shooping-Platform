<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/functions.php';

$pdo = get_db_connection();
$customers = $pdo->query("SELECT * FROM users WHERE role = 'customer' ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$message = $_GET['msg'] ?? '';

require_once __DIR__ . '/header.php';
?>

<section class="page-header"
    style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; margin-top: 2rem;">
    <h1 style="font-size: 2.5rem; margin: 0; font-weight: 800; color: #1e293b;">Customer Management</h1>
    <button onclick="openCustomerModal('add')" class="btn-primary-modern">
        + Add Customer
    </button>
</section>

<?php if ($message): ?>
    <div id="statusAlert"
        class="alert-box <?php echo (strpos($message, 'Error') !== false) ? 'alert-error' : 'alert-success'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="full-width-container">
    <div class="table-card">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Email Address</th>
                    <th>Joined Date</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($customers)): ?>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <div class="avatar-placeholder"><?php echo strtoupper(substr($customer['name'], 0, 1)); ?>
                                    </div>
                                    <span style="font-weight: 700; color: #1e293b; font-size: 1.25rem;">
                                        <?php echo htmlspecialchars($customer['name']); ?>
                                    </span>
                                </div>
                            </td>
                            <td><span
                                    style="color: #4f46e5; font-weight: 600; font-size: 1.25rem;"><?php echo htmlspecialchars($customer['email']); ?></span>
                            </td>
                            <td style="font-size: 1.25rem;"><?php echo date('M d, Y', strtotime($customer['created_at'])); ?></td>
                            <td style="text-align: right;">
                                <div style="display: flex; justify-content: flex-end; gap: 0.5rem;">
                                    <button onclick='openCustomerModal("edit", <?php echo json_encode($customer); ?>)'
                                        class="action-btn edit-btn">Edit / Security</button>
                                    <form method="POST" action="process_customer.php"
                                        onsubmit="return confirm('Delete this customer?');" style="display:inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="customer_id" value="<?php echo $customer['id']; ?>">
                                        <button type="submit" class="action-btn delete-btn">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem; color: #64748b;">No customers found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="customerModal" class="modal-overlay">
    <div class="modal-content">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 id="modalTitle" style="margin: 0; font-weight: 800; color: #1e293b;">Add Customer</h2>
            <span onclick="closeModal()" style="cursor: pointer; font-size: 2rem; color: #64748b;">&times;</span>
        </div>
        <form action="process_customer.php" method="POST">
            <input type="hidden" name="action" id="formAction" value="add">
            <input type="hidden" name="customer_id" id="customerId">

            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" id="customerName" required class="modal-input">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" id="customerEmail" required class="modal-input">
            </div>

            <div class="form-group">
                <label id="passLabel">Password</label>
                <input type="password" name="password" id="customerPassword" class="modal-input">
                <small id="passHint" style="display:none; color: #64748b; margin-top: 5px; display: block;">Leave blank
                    to keep existing password</small>
            </div>

            <button type="submit" class="btn-primary-modern" style="width: 100%; margin-top: 1rem;">Save Data</button>
        </form>
    </div>
</div>

<style>
    .alert-box {
        padding: 1.25rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        font-weight: 700;
        border-left: 5px solid;
        transition: opacity 0.5s ease;
    }

    .alert-success {
        background: #dcfce7;
        color: #166534;
        border-color: #22c55e;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border-color: #ef4444;
    }

    .modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.4);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        padding: 2.5rem;
        border-radius: 20px;
        width: 95%;
        max-width: 450px;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 700;
        color: #475569;
        font-size: 1.25rem;
    }

    .modal-input {
        width: 100%;
        padding: 0.8rem;
        border-radius: 10px;
        border: 2px solid #e2e8f0;
        font-size: 1.25rem;
        outline: none;
        box-sizing: border-box;
    }

    .table-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        border: 1px solid #edf2f7;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
    }

    .modern-table th {
        background: #f8fafc;
        padding: 1.2rem;
        text-align: left;
        color: #64748b;
        border-bottom: 2px solid #edf2f7;
        text-transform: uppercase;
        font-size: 1.25rem;
    }

    .modern-table td {
        padding: 1.25rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .action-btn {
        padding: 0.5rem 0.8rem;
        border-radius: 8px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        font-size: 1.25rem;
    }

    .edit-btn {
        background: #e0e7ff;
        color: #3730a3;
    }

    .delete-btn {
        background: #fee2e2;
        color: #991b1b;
    }

    .btn-primary-modern {
        background: #4f46e5;
        color: white;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1.25rem;
        padding: 0.8rem 1.5rem;
        border: none;
        cursor: pointer;
    }

    .avatar-placeholder {
        width: 35px;
        height: 35px;
        background: #f1f5f9;
        color: #4f46e5;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-weight: 800;
    }
</style>

<script>
    function openCustomerModal(mode, data = null) {
        const modal = document.getElementById('customerModal');
        const passInput = document.getElementById('customerPassword');
        const hint = document.getElementById('passHint');
        const label = document.getElementById('passLabel');

        modal.style.display = 'flex';

        if (mode === 'edit' && data) {
            document.getElementById('modalTitle').innerText = 'Edit Customer';
            document.getElementById('formAction').value = 'edit';
            document.getElementById('customerId').value = data.id;
            document.getElementById('customerName').value = data.name;
            document.getElementById('customerEmail').value = data.email;
            passInput.required = false;
            label.innerText = "Change Password";
            hint.style.display = 'block';
        } else {
            document.getElementById('modalTitle').innerText = 'Add New Customer';
            document.getElementById('formAction').value = 'add';
            document.getElementById('customerId').value = '';
            document.getElementById('customerName').value = '';
            document.getElementById('customerEmail').value = '';
            passInput.required = true;
            label.innerText = "Password";
            hint.style.display = 'none';
        }
    }

    function closeModal() { document.getElementById('customerModal').style.display = 'none'; }

    window.onload = function () {
        const alert = document.getElementById('statusAlert');
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.style.display = 'none', 500);
            }, 3000);
        }
    }
</script>