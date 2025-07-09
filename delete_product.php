<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('로그인 후 이용해주세요.'); window.history.back();</script>";
    exit;
}

if (!isset($_POST['id']) || intval($_POST['id']) === 0) {
    echo "<script>alert('잘못된 접근입니다.'); window.history.back();</script>";
    exit;
}

$product_id = intval($_POST['id']);
$user_id = intval($_SESSION['user_id']);

// 본인 상품인지 확인
$stmt = $conn->prepare("SELECT user_id FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

if (!$product) {
    echo "<script>alert('상품을 찾을 수 없습니다.'); window.history.back();</script>";
    exit;
}

if ($product['user_id'] != $user_id) {
    echo "<script>alert('본인의 상품만 삭제할 수 있습니다.'); window.history.back();</script>";
    exit;
}

// 삭제
$stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
if ($stmt->execute()) {
    echo "<script>alert('상품이 삭제되었습니다.'); location.href='myshop.php';</script>";
} else {
    echo "<script>alert('삭제 실패. 다시 시도해주세요.'); window.history.back();</script>";
}
?>
