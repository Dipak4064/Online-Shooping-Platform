<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$error = '';
$postId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$postId) {
    header("Location: my_store.php");
    exit;
}

$post = get_post_by_id($postId);

if (!$post || (int) $post['user_id'] !== (int) current_user()['id']) {
    header("Location: my_store.php?error=unauthorized");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $price = trim($_POST['price'] ?? '');
    $product_type = $_POST['product_type'] ?? '';

    if (empty($title) || empty($price) || empty($product_type) || empty($body)) {
        $error = 'All fields are required.';
    }

    $imagePath = $post['image_path']; // Default to old image
    if (empty($error) && !empty($_FILES['image']['name'])) {
        $uploadResult = upload_public_image($_FILES['image'], 'posts');
        if ($uploadResult['success']) {
            $imagePath = $uploadResult['path'];
        } else {
            $error = $uploadResult['error'];
        }
    }

    if (empty($error)) {
        $updateData = [
            'title' => $title,
            'body' => $body,
            'price' => $price,
            'product_type' => $product_type,
            'image_path' => $imagePath
        ];

        if (update_post($postId, $updateData)) {
            header("Location: product_view.php?id=$postId&status=updated");
            exit;
        }
        $error = 'Could not update product.';
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="edit-page-container">
    <div class="edit-card shadow-sm">
        <a href="product_view.php?id=<?= $postId ?>" class="close-icon">&times;</a>

        <div class="edit-header">
            <h1>Edit Product</h1>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger mx-5" style="font-size: 1.2rem;"><?= htmlspecialchars($error); ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="edit-form">
            <div class="input-group-section">
                <label class="section-label">Product Image</label>
                <div class="image-upload-flex">
                    <div class="image-preview-box" id="imagePreview">
                        <img src="<?= asset_url($post['image_path']) ?>" alt="Preview">
                    </div>
                    <div class="upload-controls">
                        <label for="imageInput" class="btn-outline">Choose New Photo</label>
                        <input type="file" name="image" id="imageInput" accept="image/*" hidden>
                        <span class="file-info" id="fileName">Current: <?= basename($post['image_path']) ?></span>
                    </div>
                </div>
            </div>

            <div class="input-group-section">
                <label class="section-label">Product Title</label>
                <input type="text" name="title" class="full-input" value="<?= htmlspecialchars($post['title']) ?>"
                    required>
            </div>

            <div class="input-group-section">
                <label class="section-label">Category</label>
                <select name="product_type" class="full-input" required>
                    <option value="electronics" <?= $post['product_type'] == 'electronics' ? 'selected' : '' ?>>Electronics
                    </option>
                    <option value="apparel" <?= $post['product_type'] == 'apparel' ? 'selected' : '' ?>>Apparel</option>
                    <option value="home" <?= $post['product_type'] == 'home' ? 'selected' : '' ?>>Home</option>
                </select>
            </div>

            <div class="input-group-section">
                <label class="section-label">Price (Rs.)</label>
                <input type="number" name="price" step="0.01" class="full-input"
                    value="<?= htmlspecialchars($post['price']) ?>" required>
            </div>

            <div class="input-group-section">
                <label class="section-label">Full Description</label>
                <textarea name="body" rows="6" class="full-input"
                    required><?= htmlspecialchars($post['body']) ?></textarea>
            </div>

            <div class="form-footer">
                <div class="main-actions">
                    <button type="submit" class="btn-save">Save Changes</button>
                    <a href="my_store.php" class="btn-cancel">Cancel</a>
                </div>

                <a href="delete_product.php?id=<?= $postId ?>" class="btn-delete"
                    onclick="return confirm('Are you sure you want to delete this listing?')">
                    Delete Listing
                </a>
            </div>
        </form>
    </div>
</div>

<style>
    .edit-page-container {
        background-color: #f8faff;
        min-height: 100vh;
        padding: 60px 20px;
        display: flex;
        justify-content: center;
        /* Base font size for the container */
        font-size: 1.1rem;
    }

    .edit-card {
        background: #fff;
        width: 100%;
        max-width: 900px;
        /* Slightly wider to accommodate larger text */
        border-radius: 16px;
        position: relative;
        padding-bottom: 40px;
        border: 1px solid #eee;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .close-icon {
        position: absolute;
        top: 25px;
        right: 30px;
        font-size: 2.5rem;
        /* Larger close icon */
        color: #b0c4de;
        text-decoration: none;
        line-height: 1;
    }

    .edit-header {
        padding: 50px 60px 30px 60px;
    }

    .edit-header h1 {
        font-size: 2.2rem;
        /* Big Header */
        font-weight: 800;
        color: #1a1a1a;
        margin-bottom: 10px;
    }

    .edit-header p {
        color: #666;
        font-size: 1.2rem;
        /* Sub-header larger */
    }

    .edit-form {
        padding: 0 60px;
        display: flex;
        flex-direction: column;
        gap: 30px;
        /* More spacing between groups */
    }

    .section-label {
        display: block;
        font-size: 1.1rem;
        /* Label size */
        font-weight: 700;
        color: #444;
        margin-bottom: 12px;
    }

    .full-input {
        width: 100%;
        padding: 16px 20px;
        /* Thicker inputs */
        border: 2px solid #e1e8f0;
        /* Thicker border for better visibility */
        border-radius: 10px;
        font-size: 1.2rem;
        /* Big readable input text */
        color: #333;
        transition: border-color 0.2s;
    }

    .full-input:focus {
        border-color: #000;
        outline: none;
    }

    textarea.full-input {
        line-height: 1.6;
    }

    .image-upload-flex {
        display: flex;
        align-items: center;
        gap: 30px;
    }

    .image-preview-box {
        width: 150px;
        /* Larger preview */
        height: 150px;
        border: 2px dashed #cbd5e0;
        border-radius: 12px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #fafafa;
    }

    .image-preview-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .btn-outline {
        border: 2px solid #000;
        /* High contrast border */
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        display: inline-block;
        transition: all 0.2s;
    }

    .btn-outline:hover {
        background: #f0f0f0;
    }

    .file-info {
        display: block;
        font-size: 1rem;
        color: #666;
        margin-top: 10px;
    }

    .form-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 40px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }

    .main-actions {
        display: flex;
        gap: 15px;
    }

    .btn-save {
        background: #000;
        color: #fff;
        border: none;
        padding: 16px 35px;
        /* Larger button */
        border-radius: 10px;
        font-size: 1.2rem;
        font-weight: 700;
        cursor: pointer;
        transition: transform 0.1s;
    }

    .btn-save:active {
        transform: scale(0.98);
    }

    .btn-cancel {
        background: #f0f4f8;
        color: #444;
        text-decoration: none;
        padding: 16px 35px;
        border-radius: 10px;
        font-size: 1.2rem;
        font-weight: 700;
        display: inline-block;
    }

    .btn-delete {
        color: white;
        font-size: 1.1rem;
        font-weight: 700;
        text-decoration: none;
        background-color: #ff3b30;
        padding: 16px 25px;
        border-radius: 10px;
        transition: background-color 0.2s;
    }

    .btn-delete:hover {
        background-color: #d32f2f;
        color: white;
    }

    /* Responsive adjustments */
    @media (max-width: 600px) {

        .edit-header,
        .edit-form {
            padding-left: 20px;
            padding-right: 20px;
        }

        .form-footer {
            flex-direction: column;
            gap: 20px;
            align-items: flex-start;
        }
    }
</style>

<script>
    document.getElementById('imageInput').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            document.getElementById('fileName').textContent = "Selected: " + file.name;
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('imagePreview').innerHTML = `<img src="${e.target.result}">`;
            };
            reader.readAsDataURL(file);
        }
    });
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>