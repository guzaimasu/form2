<?php
require_once 'database_connection.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $message = trim($_POST['message']);
    
    if (!empty($message)) {
        $pdo = DatabaseConfig::connect();
        
        if ($pdo) {
            $user_id = bin2hex(random_bytes(5));
            $adjectives = ['Creative', 'Logical', 'Analytical', 'Digital', 'Tech'];
            $nouns = ['Scholar', 'Learner', 'Coder', 'Student', 'Developer'];
            $username = $adjectives[array_rand($adjectives)] . $nouns[array_rand($nouns)] . rand(100, 999);
            
            try {
                $stmt = $pdo->prepare("INSERT INTO messages (user_id, username, message) VALUES (?, ?, ?)");
                $stmt->execute([$user_id, $username, $message]);
                echo json_encode(['status' => 'success']);
            } catch(PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => 'Educational database exercise']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Database connection learning example']);
        }
    }
}
?>
