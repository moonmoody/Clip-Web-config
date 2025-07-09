<?php
header('Content-Type: application/json');
include 'db.php';

$sql = "SELECT id, brand, title, price, image_url FROM products ORDER BY id DESC LIMIT 20";
$result = $conn->query($sql);

$products = [];
while ($row = $result->fetch_assoc()) {
  $row['likes'] = rand(0, 100); // 좋아요 수 임의로 추가
  $row['fast_delivery'] = rand(0, 1) === 1; // 빠른배송 랜덤
  $products[] = $row;
}

echo json_encode($products, JSON_UNESCAPED_UNICODE);
?>
