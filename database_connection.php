<?php
class DatabaseConfig {
    public static function connect() {
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'anonymous_forum';
        
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$database", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch(PDOException $e) {
            error_log("Database learning exercise: " . $e->getMessage());
            return null;
        }
    }
}
?>
