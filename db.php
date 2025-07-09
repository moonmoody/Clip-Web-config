<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// ✅ 환경 변수 가져오기 (getenv 또는 $_ENV 둘 다 시도)
$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
$user = $_ENV['DB_USER'] ?? getenv('DB_USER');
$pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS');
$db   = $_ENV['DB_NAME'] ?? getenv('DB_NAME');

// ✅ DB 연결
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("❌ DB 연결 실패: " . $conn->connect_error);
}
?>
