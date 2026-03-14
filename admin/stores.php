<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../includes/functions.php';

$pdo = get_db_connection();
$message = $_GET['msg'] ?? '';
$error = '';
$current_page = basename(__FILE__);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'delete') {
        $deleteId = (int) ($_POST['id'] ?? 0);
        if ($deleteId > 0) {
            $deleted = delete_store($deleteId);
            if ($deleted) {
                header("Location: $current_page?msg=Store Deleted");
                exit;
            } else {
                $error = "Failed to delete store.";
            }
        }
    }

    if ($action === 'create' || $action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $targetUserId = (int) ($_POST['target_user_id'] ?? 0);

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'logo' => $_POST['existing_logo'] ?? ''
        ];

        if (!empty($_FILES['logo_file']['name'])) {
            $uploaded = upload_public_image($_FILES['logo_file'], 'stores');
            if ($uploaded && isset($uploaded['path'])) {
                $data['logo'] = $uploaded['path'];
            }
        }

        try {
            if ($action === 'create') {
                if ($targetUserId <= 0)
                    throw new Exception("Please select a valid user.");
                create_store($targetUserId, $data);
                header("Location: $current_page?msg=Store Created");
            } else {
                update_store($id, $data);
                header("Location: $current_page?msg=Store Updated");
            }
            exit;
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

$stores = $pdo->query("SELECT s.*, u.name as owner_name FROM stores s JOIN users u ON s.user_id = u.id ORDER BY s.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
$available_users = $pdo->query("SELECT id, name FROM users WHERE id NOT IN (SELECT user_id FROM stores) AND role != 'admin' ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);

require_once __DIR__ . '/header.php';
?>

<div class="admin-container">
    <div class="page-header">
        <h1>Store Management</h1>
        <button onclick="openAddModal()" class="btn-primary-modern">Register Store</button>
    </div>

    <?php if ($message): ?>
        <div class="alert success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <div class="table-card">
        <div class="table-responsive">
            <table class="modern-table">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Store / Slug</th>
                        <th>Owner</th>
                        <th style="text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($stores as $s): ?>
                        <tr>
                            <td><img src="../<?php echo $s['logo'] ?: 'assets/no-store.png'; ?>" class="store-img"></td>
                            <td>
                                <div class="s-name"><?php echo htmlspecialchars($s['name']); ?></div>
                                <div class="s-slug">/<?php echo htmlspecialchars($s['slug']); ?></div>
                            </td>
                            <td class="s-owner"><?php echo htmlspecialchars($s['owner_name']); ?></td>
                            <td style="text-align: right;">
                                <button class="action-btn edit"
                                    onclick='openEditModal(<?php echo json_encode($s, JSON_HEX_APOS); ?>)'>Edit</button>
                                <form method="POST" action="<?php echo $current_page; ?>" style="display:inline;"
                                    onsubmit="return confirm('Are you sure?');">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $s['id']; ?>">
                                    <button type="submit" class="action-btn delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div id="storeModal" class="modal-overlay">
    <div class="modal-card">
        <div class="modal-header">
            <h2 id="modalTitle">Register Store</h2>
            <button onclick="closeModal()" class="close-x">&times;</button>
        </div>
        <form method="POST" action="<?php echo $current_page; ?>" enctype="multipart/form-data" id="storeForm">
            <input type="hidden" name="action" id="formAction" value="create">
            <input type="hidden" name="id" id="storeId">
            <input type="hidden" name="existing_logo" id="existingLogo">

            <div class="form-grid">
                <div class="form-group full">
                    <label>Store Name</label>
                    <input type="text" name="name" id="storeName" required>
                </div>
                <div class="form-group">
                    <label>Slug (Auto)</label>
                    <input type="text" name="slug" id="storeSlug" readonly class="readonly">
                </div>
                <div class="form-group" id="ownerBox">
                    <label>Owner</label>
                    <select name="target_user_id" id="ownerSelect">
                        <option value="">Select User</option>
                        <?php foreach ($available_users as $u): ?>
                            <option value="<?php echo $u['id']; ?>"><?php echo htmlspecialchars($u['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="storeEmail">
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" id="storePhone">
                </div>
                <div class="form-group full">
                    <label>Logo</label>
                    <input type="file" name="logo_file">
                </div>
            </div>
            <div class="modal-actions">
                <button type="button" onclick="closeModal()" class="btn-cancel">Cancel</button>
                <button type="submit" class="btn-primary-modern">Save Store</button>
            </div>
        </form>
    </div>
</div>

<style>
    :root {
        --p: #4f46e5;
        --d: #ef4444;
        --t: #1e293b;
        --border: #ddd;
    }

    * {
        box-sizing: border-box;
    }

    .admin-container {
        padding: 40px;
        background: #f8fafc;
        min-height: 100vh;
        font-family: sans-serif;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .btn-primary-modern {
        background: var(--p);
        color: #fff;
        border: none;
        padding: 12px 25px;
        border-radius: 10px;
        font-size: 1.5rem;
        font-weight: 700;
        cursor: pointer;
    }

    .table-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .modern-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 600px;
    }

    .modern-table th,
    .modern-table td {
        padding: 15px 20px;
        text-align: left;
        border-bottom: 1px solid #f1f5f9;
        font-size: 1.25rem;
    }

    .store-img {
        width: 50px;
        height: 50px;
        border-radius: 8px;
        object-fit: cover;
    }

    .action-btn {
        background: none;
        border: none;
        font-size: 1.25rem;
        font-weight: 700;
        cursor: pointer;
        margin-left: 10px;
    }

    .action-btn.edit {
        color: var(--p);
    }

    .action-btn.delete {
        color: var(--d);
    }

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.6);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        padding: 20px;
    }

    .modal-card {
        background: #fff;
        width: 100%;
        max-width: 650px;
        border-radius: 20px;
        padding: 30px;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 15px;
    }

    #modalTitle {
        font-size: 2rem;
        margin: 0;
    }

    .close-x {
        background: none;
        border: none;
        font-size: 2.5rem;
        cursor: pointer;
        color: #64748b;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-group.full {
        grid-column: span 2;
    }

    .form-group label {
        font-size: 1.3rem;
        font-weight: 600;
        color: #475569;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid var(--border);
        border-radius: 10px;
        font-size: 1.2rem;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: var(--p);
    }

    .readonly {
        background: #f1f5f9;
        cursor: not-allowed;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-cancel {
        background: #e2e8f0;
        color: #475569;
        border: none;
        padding: 12px 25px;
        border-radius: 10px;
        font-size: 1.3rem;
        font-weight: 600;
        cursor: pointer;
    }

    .alert {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 20px;
        transition: all 0.5s ease;
        font-size: 1.2rem;
    }

    .alert.success {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .alert.danger {
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
    }

    @media (max-width: 600px) {
        .form-grid {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .form-group.full {
            grid-column: span 1;
        }

        .admin-container {
            padding: 15px;
        }

        .page-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
    }
</style>

<script>
    function openAddModal() {
        document.getElementById('storeForm').reset();
        document.getElementById('modalTitle').innerText = "Register Store";
        document.getElementById('formAction').value = "create";
        document.getElementById('ownerBox').style.display = "block";
        document.getElementById('storeModal').style.display = 'flex';
    }

    function openEditModal(data) {
        document.getElementById('modalTitle').innerText = "Edit Store";
        document.getElementById('formAction').value = "update";
        document.getElementById('storeId').value = data.id;
        document.getElementById('existingLogo').value = data.logo;
        document.getElementById('storeName').value = data.name;
        document.getElementById('storeSlug').value = data.slug;
        document.getElementById('storeEmail').value = data.email;
        document.getElementById('storePhone').value = data.phone;
        document.getElementById('ownerBox').style.display = "none";
        document.getElementById('storeModal').style.display = 'flex';
    }

    function closeModal() { document.getElementById('storeModal').style.display = 'none'; }

    document.getElementById('storeName').addEventListener('input', function () {
        document.getElementById('storeSlug').value = this.value.toLowerCase().trim().replace(/[^\w\s-]/g, '').replace(/[\s_-]+/g, '-');
    });

    document.addEventListener('DOMContentLoaded', () => {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = "0";
                alert.style.transform = "translateY(-10px)";
                setTimeout(() => alert.remove(), 500);
            }, 3000);
        });
    });
</script>

<?php require_once __DIR__ . '/footer.php'; ?>