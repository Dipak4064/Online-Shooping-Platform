<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

$postId = (int) ($_GET['id'] ?? $_POST['id'] ?? 0);
$post = $postId ? get_post_by_id($postId) : null;
$user = current_user();

if (!$post || (int) $post['user_id'] !== (int) $user['id']) {
    http_response_code(404);
    require_once __DIR__ . '/../includes/header.php';
    echo '<p>Post not found.</p>';
    require_once __DIR__ . '/../includes/footer.php';
    exit;
}

$error = '';
$title = $post['title'];
$body = $post['body'];
$imagePath = $post['image_path'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $body = trim($_POST['body'] ?? '');
    $imagePath = $post['image_path'];

    if (!$title || !$body) {
        $error = 'Title and content are required.';
    }

    $removeImage = !empty($_POST['remove_image']);
    $fileProvided = !empty($_FILES['image']['name']);

    if ($fileProvided && ($_FILES['image']['size'] ?? 0) > 5 * 1024 * 1024) {
        $error = 'Images must be 5MB or smaller.';
    }

    if (!$error && $fileProvided) {
        $uploaded = upload_public_image($_FILES['image'], 'posts');
        if ($uploaded) {
            $imagePath = $uploaded;
        } else {
            $error = 'Image upload failed. Use JPG, PNG, GIF, or WEBP formats.';
        }
    }

    if (!$error && $removeImage) {
        $imagePath = null;
    }

    if (!$error) {
        $updated = update_post($postId, (int) $user['id'], [
            'title' => $title,
            'body' => $body,
            'image_path' => $imagePath,
        ]);

        if ($updated) {
            header('Location: posts.php?status=updated');
            exit;
        }

        $error = 'Could not update the post. Try again.';
    }
}

$post['image_path'] = $imagePath;

require_once __DIR__ . '/../includes/header.php';
?>
<section class="post-editor">
    <div class="post-editor-header">
        <h1>Edit your post</h1>
        <p>Keep the community updated with the latest details.</p>
    </div>
    <?php if ($error): ?>
        <div class="auth-alert auth-alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data" class="post-editor-form">
        <input type="hidden" name="id" value="<?php echo $postId; ?>">
        <label class="auth-field">
            <span>Title</span>
            <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>
        </label>
        <label class="auth-field">
            <span>Content</span>
            <textarea name="body" rows="8" required><?php echo htmlspecialchars($body); ?></textarea>
        </label>
        <label class="auth-field">
            <span>Feature image (optional)</span>
            <input type="file" name="image" accept="image/*">
        </label>
        <?php if (!empty($post['image_path'])): ?>
            <div class="post-current-image">
                <img src="<?php echo htmlspecialchars(asset_url($post['image_path'])); ?>" alt="Current image">
                <label class="remove-image">
                    <input type="checkbox" name="remove_image"> Remove image
                </label>
            </div>
        <?php endif; ?>
        <button type="submit" class="btn btn-full">Save changes</button>
    </form>
</section>
<?php require_once __DIR__ . '/../includes/footer.php';
