<?php
require 'config.php';

try {
    $stmt = $pdo->query("SELECT DATABASE() AS db");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "✅ Connected successfully! Current DB: " . $row['db'];
} catch (Exception $e) {
    echo "❌ Connection failed: " . $e->getMessage();
}
