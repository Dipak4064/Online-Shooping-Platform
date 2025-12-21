<?php
require_once __DIR__ . '/header.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $thumbnailPath = trim($_POST['thumbnail'] ?? '');
        $uploadAttempt = !empty($_FILES['thumbnail_file']['name']);
        if ($uploadAttempt && ($_FILES['thumbnail_file']['size'] ?? 0) > 5 * 1024 * 1024) {
            $error = 'Product image must be 5MB or smaller.';
        }
        if ($uploadAttempt && !$error) {
            $uploaded = upload_public_image($_FILES['thumbnail_file'], 'products');
            if ($uploaded) {
                $thumbnailPath = $uploaded;
            } else {
                $error = 'Image upload failed. Use JPG, PNG, GIF, or WEBP formats.';
            }
        }
        if (!$error) {
        $result = admin_create_product([
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'short_description' => trim($_POST['short_description'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => (float) ($_POST['price'] ?? 0),
            'stock' => (int) ($_POST['stock'] ?? 0),
            'thumbnail' => $thumbnailPath,
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'is_featured' => !empty($_POST['is_featured']),
            'is_active' => !empty($_POST['is_active']),
        ]);
            $message = $result ? 'Product created.' : 'Could not create product.';
        }
    } elseif ($action === 'update') {
        $id = (int) ($_POST['id'] ?? 0);
        $thumbnailPath = trim($_POST['thumbnail'] ?? '');
        $uploadAttempt = !empty($_FILES['thumbnail_file']['name']);
        if ($uploadAttempt && ($_FILES['thumbnail_file']['size'] ?? 0) > 5 * 1024 * 1024) {
            $error = 'Product image must be 5MB or smaller.';
        }
        if ($uploadAttempt && !$error) {
            $uploaded = upload_public_image($_FILES['thumbnail_file'], 'products');
            if ($uploaded) {
                $thumbnailPath = $uploaded;
            } else {
                $error = 'Image upload failed. Use JPG, PNG, GIF, or WEBP formats.';
            }
        }
        if (!$error) {
        $result = admin_update_product($id, [
            'name' => trim($_POST['name'] ?? ''),
            'slug' => trim($_POST['slug'] ?? ''),
            'short_description' => trim($_POST['short_description'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'price' => (float) ($_POST['price'] ?? 0),
            'stock' => (int) ($_POST['stock'] ?? 0),
            'thumbnail' => $thumbnailPath,
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'is_featured' => !empty($_POST['is_featured']),
            'is_active' => !empty($_POST['is_active']),
        ]);
            $message = $result ? 'Product updated.' : 'Could not update product.';
        }
    } elseif ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $result = admin_delete_product($id);
        $message = $result ? 'Product deleted.' : 'Could not delete product.';
    }
}

$products = admin_get_products();
$categories = admin_get_categories();
$editingId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$editingProduct = $editingId ? get_product_by_id($editingId) : null;
?>
<section class="page-header">
    <h1>Products</h1>
</section>
<?php if ($message): ?>
    <p class="success"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
<?php if ($error): ?>
    <p class="error"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
<div class="grid" style="grid-template-columns: 2fr 1fr; gap: 2rem;">
    <div>
        <table class="orders-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td>Rs. <?php echo number_format($product['price'], 2); ?></td>
                        <td><?php echo $product['stock']; ?></td>
                        <td><?php echo $product['is_active'] ? 'Active' : 'Hidden'; ?></td>
                        <td>
                            <a href="products.php?edit=<?php echo $product['id']; ?>">Edit</a>
                            <form method="post" style="display:inline;" onsubmit="return confirm('Delete product?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
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
            <h2><?php echo $editingProduct ? 'Edit product' : 'Add new product'; ?></h2>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="action" value="<?php echo $editingProduct ? 'update' : 'create'; ?>">
                <?php if ($editingProduct): ?>
                    <input type="hidden" name="id" value="<?php echo $editingProduct['id']; ?>">
                <?php endif; ?>
                <label>Name
                    <input type="text" name="name" value="<?php echo htmlspecialchars($editingProduct['name'] ?? ''); ?>" required>
                </label>
                <label>Slug
                    <input type="text" name="slug" value="<?php echo htmlspecialchars($editingProduct['slug'] ?? ''); ?>" required>
                </label>
                <label>Short description
                    <input type="text" name="short_description" value="<?php echo htmlspecialchars($editingProduct['short_description'] ?? ''); ?>">
                </label>
                <label>Description
                    <textarea name="description" rows="4" style="width:100%; border-radius:0.5rem; border:1px solid var(--border); padding:0.5rem;"><?php echo htmlspecialchars($editingProduct['description'] ?? ''); ?></textarea>
                </label>
                <label>Price
                    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($editingProduct['price'] ?? '0'); ?>" required>
                </label>
                <label>Stock
                    <input type="number" name="stock" value="<?php echo htmlspecialchars($editingProduct['stock'] ?? '0'); ?>" required>
                </label>
                <label>Thumbnail URL
                    <input type="text" name="thumbnail" value="<?php echo htmlspecialchars($editingProduct['thumbnail'] ?? ''); ?>" placeholder="https://...">
                </label>
                <label>Upload image
                    <input type="file" name="thumbnail_file" accept="image/*">
                </label>
                <?php if (!empty($editingProduct['thumbnail'])): ?>
                    <div style="margin-bottom:1rem;">
                        <img src="<?php echo htmlspecialchars(asset_url($editingProduct['thumbnail'], PLACEHOLDER_PRODUCT_IMAGE)); ?>" alt="Current image" style="max-width:100%; border-radius:0.75rem;">
                    </div>
                <?php endif; ?>
                <label>Category
                    <select name="category_id" required style="width:100%; padding:0.65rem; border-radius:0.5rem; border:1px solid var(--border);">
                        <option value="">Select category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" <?php echo isset($editingProduct['category_id']) && (int) $editingProduct['category_id'] === (int) $category['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label>
                    <input type="checkbox" name="is_featured" <?php echo !empty($editingProduct['is_featured']) ? 'checked' : ''; ?>> Featured
                </label>
                <label>
                    <input type="checkbox" name="is_active" <?php echo !isset($editingProduct['is_active']) || $editingProduct['is_active'] ? 'checked' : ''; ?>> Active
                </label>
                <button type="submit" class="btn">Save</button>
            </form>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php';
