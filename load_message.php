<?php
require_once 'database_connection.php';
header('Content-Type: application/json');

$pdo = DatabaseConfig::connect();

if ($pdo) {
    try {
        $stmt = $pdo->query("SELECT username, message FROM messages ORDER BY created_at ASC");
        $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'messages' => $messages]);
    } catch(PDOException $e) {
        echo json_encode(['status' => 'error', 'messages' => []]);
    }
} else {
    echo json_encode(['status' => 'error', 'messages' => []]);
}
?>
