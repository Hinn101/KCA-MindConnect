<?php
require_once __DIR__ . '/../config.php';
session_start();
if (!isset($_SESSION['pending_user_id'])) { header('Location: register.php'); exit; }
$errors = [];
$pdo = db();
$user_id = $_SESSION['pending_user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = trim($_POST['code'] ?? '');
    $stmt = $pdo->prepare('SELECT * FROM otp_codes WHERE user_id = ? ORDER BY id DESC LIMIT 1');
    $stmt->execute([$user_id]);
    $otp = $stmt->fetch();
    if (!$otp) { $errors[] = 'No OTP found.'; }
    elseif ($otp['code'] !== $code) { $errors[] = 'Invalid code.'; }
    elseif (strtotime($otp['expires_at']) < time()) { $errors[] = 'Code expired.'; }
    else {
        $pdo->prepare('UPDATE users SET email_verified = 1 WHERE id = ?')->execute([$user_id]);
        unset($_SESSION['pending_user_id']);
        $_SESSION['flash'] = 'Email verified. You can login now.';
        header('Location: login.php'); exit;
    }
}
?><!doctype html><html><head><meta charset='utf-8'/><title>Verify</title><link rel='stylesheet' href='style.css'></head><body>
<form method='post'><h2>Verify Email</h2><?php foreach($errors as $e): ?><div class='err'><?php echo e($e); ?></div><?php endforeach; ?>
<p>Enter the 6-digit code sent to your email.</p>
<input name='code' placeholder='Enter code' required />
<button type='submit'>Verify</button></form></body></html>