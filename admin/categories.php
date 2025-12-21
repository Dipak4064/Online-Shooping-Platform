<?php
require_once __DIR__ . '/header.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $result = admin_create_category([
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ]);
        $message = $result ? 'Category created.' : 'Could not create category.';
    } elseif ($action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $result = admin_update_category($id, [
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ]);
        $message = $result ? 'Category updated.' : 'Could not update category.';
    } elseif ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $result = admin_delete_category($id);
        $message = $result ? 'Category deleted.' : 'Could not delete category.';
    }
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
?>
<section class="page-header">
    <h1>Categories</h1>
</section>
<?php if ($message): ?>
    <p class="success"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
<div class="grid" style="grid-template-columns: 2fr 1fr; gap: 2rem;">
    <div>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($category['name']); ?></td>
                        <td><?php echo htmlspecialchars($category['slug']); ?></td>
                        <td>
                            <a href="categories.php?edit=<?php echo $category['id']; ?>">Edit</a>
                            <form method="post" style="display:inline;" onsubmit="return confirm('Delete category?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                <button class="link" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div>
        <div class="auth-form">
            <h2><?php echo $editingCategory ? 'Edit category' : 'Add category'; ?></h2>
            <form method="post">
                <input type="hidden" name="action" value="<?php echo $editingCategory ? 'update' : 'create'; ?>">
                <?php if ($editingCategory): ?>
                    <input type="hidden" name="id" value="<?php echo $editingCategory['id']; ?>">
                <?php endif; ?>
                <label>Name
                    <input type="text" name="name" value="<?php echo htmlspecialchars($editingCategory['name'] ?? ''); ?>" required>
                </label>
                <label>Slug
                    <input type="text" name="slug" value="<?php echo htmlspecialchars($editingCategory['slug'] ?? ''); ?>" required>
                </label>
                <label>Description
                    <textarea name="description" rows="4" style="width:100%; border-radius:0.5rem; border:1px solid var(--border); padding:0.5rem;"><?php echo htmlspecialchars($editingCategory['description'] ?? ''); ?></textarea>
                </label>
                <button type="submit" class="btn">Save</button>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php';
