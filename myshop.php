<?php
session_start();
require 'db.php';

$user_id = $_SESSION['user_id'] ?? null;
$username = $_SESSION['username'] ?? '사용자';

if (!$user_id) {
    echo "<script>alert('로그인이 필요합니다.'); location.href='login.php';</script>";
    exit;
}

$stmt = $conn->prepare("SELECT * FROM products WHERE user_id = ?");

if (!$stmt) {
    die("❌ SQL 준비 실패: " . $conn->error);
}

$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$products = $result->fetch_all(MYSQLI_ASSOC);
?>
<!-- 이후 HTML은 그대로 유지하셔도 됩니다 -->


<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>내 상점</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-800">

<!-- 오버레이 -->
<div id="overlay" class="fixed inset-0 bg-black bg-opacity-40 hidden" style="z-index: 9998;" onclick="closeCategoryPanel()"></div>

<!-- 카테고리 패널 -->
<div id="category-panel" class="fixed top-0 right-0 h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 overflow-y-auto" style="z-index: 9999; width: 80vw; max-width: 500px;">
  <div class="flex justify-between items-center p-4 border-b">
    <h2 class="font-semibold text-lg">카테고리</h2>
    <button onclick="closeCategoryPanel()" class="hover:bg-gray-200 rounded p-1">
      <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>
  <ul class="p-4 space-y-4 text-sm">
    <li><strong>투데이 픽</strong></li>
    <li class="flex items-center justify-between">색감 좋은 티셔츠 <span class="text-xs text-blue-500">🔥</span></li>
    <li>컬러별 인기 반소매</li>
    <li><strong>럭셔리</strong></li>
    <li>인기 주얼리</li>
    <li><strong>신발</strong></li>
    <li>지금 저장한 신발</li>
    <li><strong>상의</strong></li>
    <li>여름 인기 카테고리</li>
    <li><strong>기타</strong></li>
    <li>가방, 시계, 키덜트, 캠핑 등</li>
  </ul>
</div>



<!-- 상단바 -->
<header class="sticky top-0 bg-white border-b shadow-sm z-50">
  <div class="text-xs bg-white  flex justify-end space-x-4 px-6 py-2 max-w-screen-xl mx-auto">
    <?php if (isset($_SESSION['user_id'])): ?>
      <span class="text-gray-600"><?= htmlspecialchars($_SESSION['username']) ?>님</span>
      <a href="myshop.php" class="hover:underline">내 상점</a>
      <a href="logout.php" class="hover:underline">로그아웃</a>
    <?php else: ?>
      <a href="login.php" class="hover:underline">로그인</a>
      <a href="signup.php" class="hover:underline">회원가입</a>
    <?php endif; ?>
    <a href="#" class="hover:underline">고객센터</a>
  </div>
  <div class="flex justify-between items-center px-4 py-4 max-w-screen-xl mx-auto">
    <div class="flex items-center space-x-2">
      <a href="index.php" class="text-xl font-bold hover:underline">번개장터</a>
    </div>
    <div id="full-search" class="flex-1 mx-6 hidden md:block">
      <div class="flex border-2 border-black rounded-md overflow-hidden">
        <input type="text" placeholder="상품명, 지역명, @상점명 입력" class="w-full px-4 py-2 text-sm focus:outline-none" />
        <button class="px-4 text-gray-600">
          <img src="https://cdn-icons-png.flaticon.com/512/54/54481.png" alt="search" class="w-4 h-4" />
        </button>
      </div>
    </div>
    <nav class="flex items-center space-x-4 text-sm">
      <a href="upload.php" class="flex items-center space-x-1 hover:underline">
        <img src="https://cdn-icons-png.flaticon.com/512/126/126083.png" alt="판매하기" class="w-4 h-4" />
        <span>판매하기</span>
      </a>
      <span class="text-gray-300">|</span>
      <a href="myshop.php" class="flex items-center space-x-1 hover:underline">
        <img src="https://cdn-icons-png.flaticon.com/512/1077/1077063.png" alt="내상점" class="w-4 h-4" />
        <span>내 상점</span>
      </a>
      <span class="text-gray-300">|</span>
      <a href="#" class="flex items-center space-x-1 hover:underline">
        <img src="https://cdn-icons-png.flaticon.com/512/1380/1380338.png" alt="톡" class="w-4 h-4" />
        <span>채팅하기</span>
      </a>
      <button onclick="openCategoryPanel()" class="ml-2 p-1 hover:bg-gray-100 rounded">
        <img src="https://cdn-icons-png.flaticon.com/512/56/56763.png" alt="카테고리" class="w-5 h-5" />
      </button>
    </nav>
  </div>
</header>

<div class="max-w-5xl mx-auto px-4 py-6 bg-white rounded-md shadow mt-6">
  <div class="flex gap-6 items-center">
    <div class="w-28 h-28 bg-gray-200 rounded-full flex items-center justify-center text-4xl text-gray-400">😊</div>
    <div class="flex-1">
      <div class="flex items-center gap-2">
        <h1 class="text-xl font-bold"><?= htmlspecialchars($username) ?> 상점</h1>
        <span class="text-sm text-gray-500">신뢰등급 🌟🌟🌟🌟🌟</span>
      </div>
      <div class="mt-1 text-sm text-gray-600">
        가입일: <?= date('Y년 m월 d일', strtotime('-1377 days')) ?> | 등록 상품수: <?= count($products) ?>개
      </div>
    </div>
    <a href="upload.php" class="bg-black text-white px-4 py-2 rounded">상품 등록</a>
  </div>
</div>

<div class="max-w-5xl mx-auto px-4 mt-6 bg-white rounded shadow">
  <div class="border-b flex gap-6 text-sm font-medium px-4 pt-4">
    <button class="border-b-2 border-black pb-2">상품 <?= count($products) ?></button>
    <button class="text-gray-400">상점후기 0</button>
    <button class="text-gray-400">찜 0</button>
    <button class="text-gray-400">팔로잉 0</button>
    <button class="text-gray-400">팔로워 0</button>
  </div>

  <div class="p-4">
  <?php if (empty($products)): ?>
    <p class="text-gray-500 text-sm text-center py-12">등록한 상품이 없습니다.</p>
  <?php else: ?>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
      <?php foreach ($products as $p): ?>
        <a href="product_detail.php?id=<?= htmlspecialchars($p['id']) ?>" class="block border rounded overflow-hidden shadow text-sm hover:shadow-lg transition-shadow">
          <div class="aspect-square bg-gray-100">
            <img src="<?= htmlspecialchars($p['image_url']) ?>" alt="<?= htmlspecialchars($p['title']) ?>" class="w-full h-full object-cover" />
          </div>
          <div class="p-2 space-y-1">
            <p class="text-gray-700 font-semibold truncate"><?= htmlspecialchars($p['title']) ?></p>
            <p class="text-black font-bold"><?= number_format($p['price']) ?>원</p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>


<script>
  function openCategoryPanel() {
  document.getElementById("category-panel").classList.remove("translate-x-full");
  document.getElementById("overlay").classList.remove("hidden");
}
function closeCategoryPanel() {
  document.getElementById("category-panel").classList.add("translate-x-full");
  document.getElementById("overlay").classList.add("hidden");
}
  </script>
<footer class="bg-white text-gray-700 text-xs mt-20 px-6 py-10 ">
  <div class="max-w-7xl mx-auto border-b border-gray-200 mb-8"></div>
  <div class="max-w-7xl mx-auto grid grid-cols-4 gap-12 mb-8">
    <div class="col-span-1 flex flex-col space-y-1">
      <p class="font-semibold text-gray-900 mb-2">이용안내</p>
      <ul class="space-y-1">
        <li><a href="#" class="hover:underline">검수기준</a></li>
        <li><a href="#" class="hover:underline">이용정책</a></li>
        <li><a href="#" class="hover:underline">페널티 정책</a></li>
        <li><a href="#" class="hover:underline">커뮤니티 가이드라인</a></li>
      </ul>
    </div>

    <div class="col-span-1 flex flex-col space-y-1">
      <p class="font-semibold text-gray-900 mb-2">고객지원</p>
      <ul class="space-y-1">
        <li><a href="#" class="hover:underline">공지사항</a></li>
        <li><a href="#" class="hover:underline">서비스 소개</a></li>
        <li><a href="#" class="hover:underline">스토어 안내</a></li>
        <li><a href="#" class="hover:underline">판매자 방문접수</a></li>
      </ul>
    </div>

    <div></div> <!-- 빈 칸으로 공간 확보 -->

    <div class="col-span-1 flex flex-col justify-between">
      <div>
        <p class="font-semibold text-gray-900 mb-2">고객센터 1588-1234</p>
        <p class="mb-1">운영시간 평일 09:00 - 18:00 (주말 및 공휴일 휴무)</p>
        <p class="mb-1">점심시간 평일 12:00 - 13:00</p>
        <p class="mb-3 font-semibold">1:1 문의는 앱을 통해서만 가능합니다.</p>
      </div>
      <button class="bg-black text-white px-3 py-1 rounded text-xs self-start">자주 묻는 질문</button>
    </div>
  </div>

  <div class="max-w-7xl mx-auto border-b border-gray-200 mb-6"></div>

  <div class="max-w-7xl mx-auto flex justify-between items-center py-4 text-xs text-gray-500">
    <div class="space-x-6">
      <a href="#" class="hover:underline">회사소개</a>
      <a href="#" class="hover:underline">인재채용</a>
      <a href="#" class="hover:underline">이용약관</a>
      <a href="#" class="hover:underline font-semibold">개인정보처리방침</a>
    </div>
    <div>
      © 2025 CLIP Inc.
    </div>
  </div>
</footer>
</body>
</html>
