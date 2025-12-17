<?php
require_once __DIR__ . '/../config.php';
$room_id = intval($_GET['room_id'] ?? 1);
$pdo = db();
$stmt = $pdo->prepare('SELECT m.id, m.content, m.created_at, u.nickname, u.id as uid FROM messages m JOIN users u ON m.user_id=u.id WHERE m.room_id = ? ORDER BY m.id DESC LIMIT 200');
$stmt->execute([$room_id]);
$rows = array_reverse($stmt->fetchAll());
header('Content-Type: application/json');
echo json_encode($rows);
