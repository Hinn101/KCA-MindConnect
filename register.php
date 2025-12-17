<?php
require_once __DIR__ . '/../config.php';
session_start();
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admin = trim($_POST['admin_number'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $nickname = trim($_POST['nickname'] ?? '');
    $password = $_POST['password'] ?? '';
    if ($admin === '' || $email === '' || $nickname === '' || $password === '') {
        $errors[] = 'All fields required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email.';
    } else {
        $pdo = db();
        $stmt = $pdo->prepare('SELECT id FROM users WHERE admin_number = ?');
        $stmt->execute([$admin]);
        if ($stmt->fetch()) {
            $errors[] = 'Admin number already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (admin_number, email, nickname, password_hash) VALUES (?,?,?,?)');
            $stmt->execute([$admin, $email, $nickname, $hash]);
            $user_id = $pdo->lastInsertId();
            $code = strval(random_int(100000, 999999));
            $stmt = $pdo->prepare('INSERT INTO otp_codes (user_id, code, expires_at) VALUES (?,?, DATE_ADD(NOW(), INTERVAL 30 MINUTE))');
            $stmt->execute([$user_id, $code]);
            $subject = 'KCA MindConnect - Your verification code';
            $body = "<p>Your 6-digit verification code is: <b>{$code}</b></p>";
            send_mail($email, $subject, $body);
            $_SESSION['pending_user_id'] = $user_id;
            header('Location: verify.php');
            exit();
        }
    }
}
?><!doctype html><html><head><meta charset='utf-8'/><title>Register</title><link rel='stylesheet' href='style.css'></head><body>
<form method='post'><h2>Register (KCA Students)</h2><?php foreach($errors as $e): ?><div class='err'><?php echo e($e); ?></div><?php endforeach; ?>
<input name='admin_number' placeholder='Administration Number' required />
<input name='email' type='email' placeholder='University Email' required />
<input name='nickname' placeholder='Nickname (public)' required />
<input name='password' type='password' placeholder='Password' required />
<button type='submit'>Create Account</button>
<p style='text-align:center;margin-top:10px'>Already have an account? <a href='login.php'>Login</a></p>
</form></body></html>