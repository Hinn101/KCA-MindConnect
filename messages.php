<?php
require_once __DIR__ . '/../config.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['error'=>'auth']); exit(); }
$input = json_decode(file_get_contents('php://input'), true);
$content = trim($input['content'] ?? '');
$room_id = intval($input['room_id'] ?? 1);
$user_id = $_SESSION['user_id'];
if ($content === '') { http_response_code(400); echo json_encode(['error'=>'empty']); exit(); }
$bad = ['kill','suicide','stupid','hate'];
foreach($bad as $w) { if (stripos($content, $w) !== false) { http_response_code(400); echo json_encode(['error'=>'blocked']); exit(); } }
$pdo = db();
$stmt = $pdo->prepare('INSERT INTO messages (room_id,user_id,content) VALUES (?,?,?)');
$stmt->execute([$room_id, $user_id, $content]);
echo json_encode(['ok'=>true]);
