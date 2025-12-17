<?php
require_once __DIR__ . '/../config.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
if (!isset($_SESSION['user_id'])) { http_response_code(401); echo json_encode(['error'=>'auth']); exit(); }
$input = json_decode(file_get_contents('php://input'), true);
$mood = trim($input['mood'] ?? '');
$note = trim($input['note'] ?? '');
if ($mood === '') { http_response_code(400); echo json_encode(['error'=>'no mood']); exit(); }
$pdo = db();
$user_id = $_SESSION['user_id'];
$today = date('Y-m-d');
try {
    $stmt = $pdo->prepare('INSERT INTO moods (user_id,mood,note,created_at) VALUES (?,?,?,?) ON DUPLICATE KEY UPDATE mood=VALUES(mood), note=VALUES(note)');
    $stmt->execute([$user_id,$mood,$note,$today]);
} catch(Exception $e) { http_response_code(500); echo json_encode(['error'=>'db']); exit(); }
$streak = 0;
for ($i=0;$i<365;$i++){
    $d = date('Y-m-d', strtotime("-$i day"));
    $stmt = $pdo->prepare('SELECT id FROM moods WHERE user_id=? AND created_at=?');
    $stmt->execute([$user_id,$d]);
    if ($stmt->fetch()) $streak++; else break;
}
echo json_encode(['ok'=>true,'streak'=>$streak,'message'=>'Mood saved.']);
