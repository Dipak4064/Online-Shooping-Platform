<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../includes/functions.php';

$pdo = get_db_connection();
$message = $_GET['msg'] ?? ''; 
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create' || $action === 'update') {
        $title = trim($_POST['title'] ?? '');
        $price = (float) ($_POST['price'] ?? 0);
        $type = trim($_POST['product_type'] ?? ''); 
        $body = trim($_POST['body'] ?? '');
        $id = (int) ($_POST['id'] ?? 0);

        $imagePath = $_POST['existing_image'] ?? '';
        
        if (!empty($_FILES['image_file']['name'])) {
            if ($_FILES['image_file']['size'] > 5 * 1024 * 1024) {
                $error = 'Image must be 5MB or smaller.';
            } else {
                $uploaded = upload_public_image($_FILES['image_file'], 'products');
                
                if (is_array($uploaded)) {
                    $imagePath = $uploaded[1] ?? $uploaded['path'] ?? $imagePath;
                } elseif (is_string($uploaded)) {
                    $imagePath = $uploaded;
                } else {
                    $error = 'Image upload failed.';
                }
            }
        }

        if (!$error) {
            if ($action === 'create') {
                $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, price, product_type, body, image_path) VALUES (?, ?, ?, ?, ?, ?)");
                $result = $stmt->execute([$_SESSION['user_id'] ?? 1, $title, $price, $type, $body, $imagePath]);
                if ($result) {
                    header("Location: products.php?msg=Product added successfully");
                    exit;
                }
            } else {
                $stmt = $pdo->prepare("UPDATE posts SET title = ?, price = ?, product_type = ?, body = ?, image_path = ? WHERE id = ?");
                $result = $stmt->execute([$title, $price, $type, $body, $imagePath, $id]);
                if ($result) {
                    header("Location: products.php?msg=Product updated successfully");
                    exit;
                }
            }
        }
    } elseif ($action === 'delete') {
        $id = (int) ($_POST['id'] ?? 0);
        $stmt = $pdo->prepare("UPDATE posts SET deleted_at = NOW() WHERE id = ?");
        $result = $stmt->execute([$id]);
        header("Location: products.php?msg=Product moved to trash");
        exit;
    }
    
}

$categories = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll(PDO::FETCH_ASSOC);
$products = $pdo->query("
    SELECT posts.*, users.name 
    FROM posts 
    LEFT JOIN users ON posts.user_id = users.id 
    WHERE posts.deleted_at IS NULL 
    ORDER BY posts.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

$editingId = isset($_GET['edit']) ? (int) $_GET['edit'] : 0;
$editItem = null;
if ($editingId) {
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
    $stmt->execute([$editingId]);
    $editItem = $stmt->fetch(PDO::FETCH_ASSOC);
}

require_once __DIR__ . '/header.php'; 
?>

<section class="page-header" style="display: flex; justify-content: space-between; align-items: center; margin: 2rem 0;">
    <h1 style="font-size: 1.85rem; font-weight: 800; color: #1e293b;">Product Inventory</h1>
    <button onclick="document.getElementById('modalOverlay').style.display='flex'" class="btn-primary-modern">+ Add Product</button>
</section>

<?php if (!empty($message)): ?>
    <div id="alert-msg" style="padding: 1rem; background: #dcfce7; color: #15803d; border-radius: 10px; margin-bottom: 2rem; font-weight: 700; transition: opacity 0.5s ease;">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<?php if (!empty($error)): ?>
    <div id="error-msg" style="padding: 1rem; background: #fee2e2; color: #b91c1c; border-radius: 10px; margin-bottom: 2rem; font-weight: 700; transition: opacity 0.5s ease;">
        <?php echo htmlspecialchars($error); ?>
    </div>
<?php endif; ?>

<div class="table-card">
    <table class="modern-table">
        <thead>
            <tr>
                <th>Image</th>
                <th>Name</th>
                <th>Title</th>
                <th>Category</th>
                <th>Price</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $p): ?>
                <tr>
                    <td>
                        <img src="../<?php echo htmlspecialchars($p['image_path'] ?: 'assets/no-image.png'); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px;">
                    </td>
                    <td style="color: #64748b; font-size: 1.1rem;">
                        <?php echo htmlspecialchars($p['name'] ?? 'Unknown User'); ?>
                    </td>              
                    <td style="font-weight: 700;"><?php echo htmlspecialchars($p['title']); ?></td>
                    <td><span style="background: #f1f5f9; padding: 4px 10px; border-radius: 5px; font-size: 1.1rem;"><?php echo htmlspecialchars($p['product_type']); ?></span></td>
                    <td style="color: #27ae60; font-weight: 700;">Rs. <?php echo number_format($p['price'], 2); ?></td>
                    <td style="text-align: right;">
                        <a href="products.php?edit=<?php echo $p['id']; ?>" class="action-btn edit">Edit</a>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('Delete this?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                            <button type="submit" class="action-btn delete">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div id="modalOverlay" class="modal-overlay" style="<?php echo $editItem ? 'display:flex;' : 'display:none;'; ?>">
    <div class="modal-card">
        <div class="modal-header">
            <h2><?php echo $editItem ? 'Edit Product' : 'New Product'; ?></h2>
            <a href="products.php" class="close-icon" style="text-decoration:none;">&times;</a>
        </div>
        <form method="POST" enctype="multipart/form-data" class="modal-form">
            <input type="hidden" name="action" value="<?php echo $editItem ? 'update' : 'create'; ?>">
            <?php if ($editItem): ?>
                <input type="hidden" name="id" value="<?php echo $editItem['id']; ?>">
                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($editItem['image_path']); ?>">
            <?php endif; ?>

            <div class="form-grid">
                <div class="form-group full">
                    <label>Product Title</label>
                    <input type="text" name="title" value="<?php echo htmlspecialchars($editItem['title'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Price (Rs.)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($editItem['price'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="product_type" required 
                            style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; background-color: white;">
                        <option value="">Select Category</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['name']); ?>" 
                                <?php echo (isset($editItem['product_type']) && $editItem['product_type'] == $cat['name']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group full">
                    <label>Description (Body Content)</label>
                    <textarea name="body" rows="4" style="width:100%; border-radius:10px; border:1px solid #cbd5e1; padding:10px;"><?php echo htmlspecialchars($editItem['body'] ?? ''); ?></textarea>
                </div>
                
                <div class="form-group full">
                    <label>Product Image</label>
                    <div id="imagePreviewContainer" style="margin-bottom: 15px; <?php echo !empty($editItem['image_path']) ? '' : 'display:none;'; ?>">
                        <img id="imagePreview" 
                             src="../<?php echo htmlspecialchars($editItem['image_path'] ?? ''); ?>" 
                             style="max-width: 120px; height: auto; border-radius: 10px; border: 2px solid #e2e8f0; display: block;">
                    </div>
                    <input type="file" name="image_file" id="imageInput" accept="image/*">
                </div>
            </div>

            <div class="modal-actions">
                <a href="products.php" class="btn-link" style="text-decoration: none; color: #64748b; font-weight: 700;">Cancel</a>
                <button type="submit" class="btn-primary-modern">Save Product</button>
            </div>
        </form>
    </div>
</div>

<style>
    .table-card { background: white; border-radius: 15px; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05); overflow: hidden; }
    .modern-table { width: 100%; border-collapse: collapse; }
    .modern-table th { background: #f8fafc; padding: 1.5rem; text-align: left; color: #64748b; border-bottom: 2px solid #edf2f7; }
    .modern-table td { padding: 1.2rem 1.5rem; border-bottom: 1px solid #f1f5f9; font-size: 1.2rem; }
    .btn-primary-modern { background: #4f46e5; color: white; border: none; padding: 0.8rem 1.5rem; border-radius: 10px; font-weight: 700; cursor: pointer; font-size:1.25rem}
    .modal-overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.4); display: flex; align-items: center; justify-content: center; z-index: 1000; }
    .modal-card { background: white; width: 90%; max-width: 600px; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 40px rgba(0,0,0,0.1); }
    .modal-header { padding: 1.5rem; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
    .modal-form { padding: 1.5rem; }
    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-group.full { grid-column: span 2; }
    .form-group label { display: block; margin-bottom: 5px; font-weight: 600; font-size: 1.3rem; color: #334155; }
    .form-group input, .form-group textarea { width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 8px; box-sizing: border-box; font-size:1.25rem;}
    .modal-actions { margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 1rem; align-items: center; }
    .action-btn { text-decoration: none; font-weight: 700; margin-left: 10px; font-size: 1.25rem; }
    .action-btn.edit { color: #4f46e5; }
    .action-btn.delete { color: #ef4444; background: none; border: none; cursor: pointer; }
    .close-icon { font-size: 2.5rem; color: #64748b; }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alertMsg = document.getElementById('alert-msg');
        const fadeOut = (el) => {
            if (el) {
                setTimeout(() => {
                    el.style.opacity = '0';
                    setTimeout(() => el.style.display = 'none', 500);
                }, 3000); 
            }
        };
        fadeOut(alertMsg);

        const imageInput = document.getElementById('imageInput');
        const imagePreview = document.getElementById('imagePreview');
        const previewContainer = document.getElementById('imagePreviewContainer');

        if (imageInput) {
            imageInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        previewContainer.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    });
</script>
