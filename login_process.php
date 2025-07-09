<?php
session_start();
include 'db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($password, $user['password'])) {
    // 로그인 성공 → 세션 저장
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    // ✅ 로그인 로그 DB 저장
    $user_id = $user['id'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $stmt_log = $conn->prepare("INSERT INTO login_logs (user_id, ip_address) VALUES (?, ?)");
    if ($stmt_log) {
        $stmt_log->bind_param("is", $user_id, $ip);
        $stmt_log->execute();
    } else {
        error_log("❌ 로그인 로그 저장 실패: " . $conn->error);
    }

    // 홈으로 이동
    header("Location: myshop.php");
    exit;
} else {
    echo "<script>alert('❌ 로그인 실패! 아이디 또는 비밀번호를 확인하세요.'); history.back();</script>";
}
?>
