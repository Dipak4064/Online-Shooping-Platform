<?php
require_once __DIR__ . '/../includes/header.php';

$posts = get_posts();
$user = current_user();
$flash = '';

if (isset($_GET['status'])) {
    $map = [
        'created' => 'Post published successfully.',
        'updated' => 'Post updated successfully.',
        'deleted' => 'Post removed.',
    ];
    $status = $_GET['status'];
    if (isset($map[$status])) {
        $flash = $map[$status];
    }
}
?>
<section class="posts-header">
    <div>
        <h1>Community posts</h1>
        <p>Share your hauls, reviews, and shopping tips with the community.</p>
    </div>
    <?php if ($user): ?>
        <a class="btn" href="<?php echo BASE_URL; ?>post_create.php">Create post</a>
    <?php else: ?>
        <a class="btn btn-outline" href="<?php echo BASE_URL; ?>login.php">Sign in to post</a>
    <?php endif; ?>
</section>
<?php if ($flash): ?>
    <p class="success"><?php echo htmlspecialchars($flash); ?></p>
<?php endif; ?>
<?php if (!$posts): ?>
    <p>No community posts yet. Be the first to share!</p>
<?php else: ?>
    <div class="posts-grid">
        <?php foreach ($posts as $post): ?>
            <article class="post-card">
                <?php if (!empty($post['image_path'])): ?>
                    <img src="<?php echo htmlspecialchars(asset_url($post['image_path'])); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                <?php endif; ?>
                <div class="post-card-body">
                    <h3><?php echo htmlspecialchars($post['title']); ?></h3>
                    <p class="post-meta">By <?php echo htmlspecialchars($post['author_name']); ?> • <?php echo date('M d, Y', strtotime($post['created_at'])); ?></p>
                    <p><?php echo htmlspecialchars(mb_strlen(strip_tags($post['body'])) > 160 ? mb_substr(strip_tags($post['body']), 0, 160) . '…' : strip_tags($post['body'])); ?></p>
                    <?php if ($user && (int) $user['id'] === (int) $post['user_id']): ?>
                        <div class="post-card-actions">
                            <a class="btn-outline" href="<?php echo BASE_URL; ?>post_edit.php?id=<?php echo $post['id']; ?>">Edit</a>
                            <form method="post" action="<?php echo BASE_URL; ?>post_delete.php" onsubmit="return confirm('Delete this post?');">
                                <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                                <button class="link" type="submit">Delete</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php require_once __DIR__ . '/../includes/footer.php';
