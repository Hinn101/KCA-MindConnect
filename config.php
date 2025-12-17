<?php
// config.php - KCA MindConnect
$DB_HOST = getenv('DB_HOST') ?: 'localhost';
$DB_NAME = getenv('DB_NAME') ?: 'kca_mindconnect';
$DB_USER = getenv('DB_USER') ?: 'root';
$DB_PASS = getenv('DB_PASS') ?: '';
// Email (PHPMailer SMTP) - defaults for Gmail
$mailHost = getenv('MAIL_HOST') ?: 'smtp.gmail.com';
$mailUsername = getenv('MAIL_USERNAME') ?: 'your-email@gmail.com';
$mailPassword = getenv('MAIL_PASSWORD') ?: 'your-app-password';
$mailPort = getenv('MAIL_PORT') ?: 587;
$mailFrom = getenv('MAIL_FROM') ?: 'no-reply@kca-mindconnect.local';
$mailFromName = getenv('MAIL_FROM_NAME') ?: 'KCA MindConnect';

<?php
$host = "localhost";          // XAMPP MySQL server
$dbname = "kca_mindconnect";  // database you created
$username = "root";           // default XAMPP username
$password = "";               // leave empty (default)

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

function e($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function require_login() {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: /public/login.php'); exit();
    }
}
function send_mail($to, $subject, $body) {
    global $mailHost, $mailUsername, $mailPassword, $mailPort, $mailFrom, $mailFromName;
    if (file_exists(__DIR__ . '/vendor/autoload.php')) {
        try {
            require __DIR__ . '/vendor/autoload.php';
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $mailHost;
            $mail->SMTPAuth = true;
            $mail->Username = $mailUsername;
            $mail->Password = $mailPassword;
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $mailPort;
            $mail->setFrom($mailFrom, $mailFromName);
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log('PHPMailer error: ' . $e->getMessage());
        }
    }
    $headers = "From: {$mailFromName} <{$mailFrom}>
Content-Type: text/html; charset=UTF-8
";
    return mail($to, $subject, $body, $headers);
}
?>