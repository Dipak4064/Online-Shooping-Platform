<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/functions.php';

$pdo = get_db_connection();
$message = '';

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'created')
        $message = 'Category created successfully.';
    if ($_GET['msg'] === 'updated')
        $message = 'Category updated successfully.';
    if ($_GET['msg'] === 'deleted')
        $message = 'Category deleted successfully.';
    if ($_GET['msg'] === 'error')
        $message = 'Could not perform the action.';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $success = false;

    if ($action === 'create') {
        $success = admin_create_category([
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ]);
        if ($success) {
            header("Location: categories.php?msg=created");
            exit;
        }
    } elseif ($action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $success = admin_update_category($id, [
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ]);
        if ($success) {
            header("Location: categories.php?msg=updated");
            exit;
        }
    } elseif ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $success = admin_delete_category($id);
        if ($success) {
            header("Location: categories.php?msg=deleted");
            exit;
        }
    }

    header("Location: categories.php?msg=error");
    exit;
}

$categories = admin_get_categories();
$editingId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$editingCategory = null;

if ($editingId) {
    foreach ($categories as $category) {
        if ((int) $category['id'] === $editingId) {
            $editingCategory = $category;
            break;
        }
    }
}

require_once __DIR__ . '/header.php';
?>

<section class="page-header"  style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; margin-top:40px;">
    <h1 style="font-size: 2rem; margin: 0; font-weight: 800; color: #1e293b;">Categories</h1>
    <button id="openModalBtn" class="btn-primary-modern">+ Add New Category</button>
</section>

<?php if ($message): ?>
    <div id="status-alert"
        class="alert <?php echo strpos($message, 'successfully') !== false ? 'alert-success' : 'alert-error'; ?>">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="full-width-container">
    <div class="table-card">
        <table class="modern-table">
            <thead>
                <tr>
                    <th>Category Name</th>
                    <th>Slug / URL Identifier</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td>
                            <span class="category-name-text"><?php echo htmlspecialchars($category['name']); ?></span>
                        </td>
                        <td>
                            <code class="slug-badge"><?php echo htmlspecialchars($category['slug']); ?></code>
                        </td>
                        <td style="text-align: right;">
                            <a href="categories.php?edit=<?php echo $category['id']; ?>" class="action-btn edit">Edit</a>
                            <form method="post" style="display:inline;" onsubmit="return confirm('Delete category?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                <button class="action-btn delete" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="modalOverlay" class="modal-overlay"
    style="<?php echo $editingCategory ? 'display: flex;' : 'display: none;'; ?>">
    <div class="modal-card">
        <div class="modal-header">
            <h2><?php echo $editingCategory ? 'Edit Category' : 'Add New Category'; ?></h2>
            <button id="closeModalBtn" class="close-icon">&times;</button>
        </div>
        <form method="post" class="modal-form">
            <input type="hidden" name="action" value="<?php echo $editingCategory ? 'update' : 'create'; ?>">
            <?php if ($editingCategory): ?>
                <input type="hidden" name="id" value="<?php echo $editingCategory['id']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" id="catName"
                    value="<?php echo htmlspecialchars($editingCategory['name'] ?? ''); ?>" required
                    placeholder="e.g. Electronics">
            </div>

            <div class="form-group">
                <label>Slug</label>
                <input type="text" name="slug" id="catSlug"
                    value="<?php echo htmlspecialchars($editingCategory['slug'] ?? ''); ?>" required
                    placeholder="electronics">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4"
                    placeholder="Briefly describe this category..."><?php echo htmlspecialchars($editingCategory['description'] ?? ''); ?></textarea>
            </div>

            <div class="modal-actions">
                <button type="button" class="btn-link" id="cancelBtn">Cancel</button>
                <button type="submit" class="btn-primary-modern">Save Category</button>
            </div>
        </form>
    </div>
</div>

<style>
    .full-width-container {
        width: 100%;
        margin-top: 1rem;
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
        padding: 1.25rem 1.5rem;
        text-align: left;
        font-size: 1.1rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #64748b;
        border-bottom: 2px solid #edf2f7;
    }

    .modern-table td {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .category-name-text {
        font-weight: 700;
        color: #1e293b;
        font-size: 1.25rem;
    }

    .slug-badge {
        background: #f1f5f9;
        color: #4f46e5;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 1.1rem;
    }

    .btn-primary-modern {
        background: #4f46e5;
        color: white;
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        border: none;
        font-size: 1.25rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-primary-modern:hover {
        transform: translateY(-2px);
        background: #4338ca;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .action-btn {
        text-decoration: none;
        font-weight: 600;
        font-size: 1.2rem;
        margin-left: 1rem;
    }

    .action-btn.edit {
        color: #4f46e5;
    }

    .action-btn.delete {
        color: #ef4444;
        background: none;
        border: none;
        cursor: pointer;
    }

    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(15, 23, 42, 0.5);
        backdrop-filter: blur(4px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 9999;
        padding: 1rem;
    }

    .modal-card {
        background: white;
        width: 100%;
        max-width: 500px;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .modal-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header h2 {
        font-size: 1.25rem;
        margin: 0;
        color: #1e293b;
    }

    .close-icon {
        background: none;
        border: none;
        font-size: 1.5rem;
        cursor: pointer;
        color: #94a3b8;
    }

    .modal-form {
        padding: 1.5rem 2rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: #475569;
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-family: inherit;
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    .form-group input:focus {
        border-color: #4f46e5;
        outline: none;
    }

    .modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 2rem;
        align-items: center;
    }

    .btn-link {
        background: none;
        border: none;
        color: #64748b;
        font-weight: 600;
        cursor: pointer;
        font-size: 0.95rem;
    }

    .alert {
        padding: 1rem 1.5rem;
        border-radius: 10px;
        margin-bottom: 1.5rem;
        font-weight: 600;
        transition: opacity 0.5s ease;
    }

    .alert-success {
        background: #dcfce7;
        color: #166534;
        border-left: 4px solid #22c55e;
    }

    .alert-error {
        background: #fee2e2;
        color: #991b1b;
        border-left: 4px solid #ef4444;
    }
</style>

<script>
    const modal = document.getElementById('modalOverlay');
    const openBtn = document.getElementById('openModalBtn');
    const cancelBtn = document.getElementById('cancelBtn');
    const closeIcon = document.getElementById('closeModalBtn');

    openBtn.onclick = () => {
        modal.style.display = 'flex';
    };

    const hideModal = () => {
        modal.style.display = 'none';
        if (window.location.search.includes('edit=')) {
            window.location.href = 'categories.php';
        }
    };

    cancelBtn.onclick = hideModal;
    closeIcon.onclick = hideModal;

    window.onclick = (e) => {
        if (e.target == modal) hideModal();
    }

    document.addEventListener('DOMContentLoaded', () => {
        const alert = document.getElementById('status-alert');
        if (alert) {
            setTimeout(() => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            }, 3000);
        }

        const nameInput = document.getElementById('catName');
        const slugInput = document.getElementById('catSlug');
        if (nameInput && slugInput) {
            nameInput.addEventListener('input', () => {
                const slugValue = nameInput.value
                    .toLowerCase()
                    .replace(/[^\w ]+/g, '')
                    .replace(/ +/g, '-');
                slugInput.value = slugValue;
            });
        }
    });
</script>

