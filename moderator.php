<?php
require_once __DIR__ . '/../config.php';
require_login();
$q = trim($_GET['q'] ?? '');
$results = [];
if ($q !== '') {
    $pdo = db();
    $stmt = $pdo->prepare('SELECT id, admin_number, email, nickname, created_at FROM users WHERE nickname LIKE ? OR admin_number LIKE ? LIMIT 50');
    $like = "%$q%";
    $stmt->execute([$like,$like]);
    $results = $stmt->fetchAll();
}
?><!doctype html><html><head><meta charset='utf-8'/><title>Moderator</title><link rel='stylesheet' href='style.css'></head><body><div class='topbar'><div style='font-weight:bold'>Moderator Panel</div></div><div style='max-width:900px;margin:24px auto'><form method='get'><input name='q' placeholder='Search nickname or admin number' value='<?php echo e($q); ?>'/><button type='submit'>Search</button></form><?php if(!empty($results)): ?><div class='moderator-results'><?php foreach($results as $r): ?><p><?php echo e($r['nickname']); ?> — <code><?php echo e($r['admin_number']); ?></code> — <?php echo e($r['email']); ?> — <?php echo e($r['created_at']); ?></p><?php endforeach; ?></div><?php endif; ?></div></body></html>