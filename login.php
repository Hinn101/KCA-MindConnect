<?php
require_once __DIR__ . '/../config.php';
session_start();
$errors = [];
$flash = $_SESSION['flash'] ?? null; unset($_SESSION['flash']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin = trim($_POST['admin_number'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($admin === '' || $password === '') { $errors[] = 'Required'; }
    else {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT * FROM users WHERE admin_number = ?');
        $stmt->execute([$admin]);
        $u = $stmt->fetch();
        if (!$u) { $errors[] = 'No account.'; }
        elseif (!$u['email_verified']) { $errors[] = 'Email not verified.'; }
        elseif (!password_verify($password, $u['password_hash'])) { $errors[] = 'Invalid credentials.'; }
        else { $_SESSION['user_id'] = $u['id']; $_SESSION['nickname'] = $u['nickname']; header('Location: chat.php'); exit; }
    }
}
?><!doctype html><html><head><meta charset='utf-8'/><title>Login</title><link rel='stylesheet' href='style.css'></head><body>
<form method='post'><h2>Login</h2><?php if($flash): ?><div class='flash'><?php echo e($flash); ?></div><?php endif; ?><?php foreach($errors as $e): ?><div class='err'><?php echo e($e); ?></div><?php endforeach; ?>
<input name='admin_number' placeholder='Administration Number' required />
<input name='password' type='password' placeholder='Password' required />
<button type='submit'>Login</button><p style='text-align:center;margin-top:10px'>No account? <a href='register.php'>Register</a></p></form></body></html>