<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
$user = $_ENV['DB_USER'] ?? getenv('DB_USER');
$pass = $_ENV['DB_PASS'] ?? getenv('DB_PASS');
$db   = $_ENV['DB_NAME'] ?? getenv('DB_NAME');

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("DB 연결 실패: " . $conn->connect_error);
}

// 입력값 받기
$username        = $_POST['username'];
$password        = password_hash($_POST['password'], PASSWORD_DEFAULT);
$name            = $_POST['name'];
$phone           = $_POST['phone'];
$region          = $_POST['region'];
$birthdate       = $_POST['birthdate'];
$payment_method  = $_POST['payment_method'];

// 아이디 중복 확인
$check = $conn->prepare("SELECT id FROM users WHERE username = ?");
$check->bind_param("s", $username);
$check->execute();
$check->store_result();

if ($check->num_rows > 0) {
    echo "❌ 이미 사용 중인 아이디입니다.";
} else {
    $stmt = $conn->prepare("
        INSERT INTO users (username, password, name, phone, region, birthdate, payment_method)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssssss", $username, $password, $name, $phone, $region, $birthdate, $payment_method);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit;
    } else {
        echo "❌ 에러: " . $stmt->error;
    }

    $stmt->close();
}

$check->close();
$conn->close();
?>
