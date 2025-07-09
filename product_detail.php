<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>상품 상세</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="text-gray-800">

<?php

session_start();
include 'db.php';

$id = $_GET['id'] ?? 0;
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
  echo "상품을 찾을 수 없습니다.";
  exit;
}

// 다중 이미지 불러오기 (product_images 테이블이 있어야 함)
$images = [];
$image_stmt = $conn->prepare("SELECT image_url FROM product_images WHERE product_id = ?");
$image_stmt->bind_param("i", $id);
$image_stmt->execute();
$image_result = $image_stmt->get_result();
while ($row = $image_result->fetch_assoc()) {
  $images[] = $row['image_url'];
}
$image_stmt->close();
?>

<!-- 상단바 -->
<header class="sticky top-0 bg-white border-b shadow-sm z-50">
  <div class="text-xs bg-white border-b text-gray-500 flex justify-end space-x-4 px-6 py-2">
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

<div class="max-w-5xl mx-auto p-4 grid grid-cols-1 md:grid-cols-2 gap-10">
  
<!-- 이미지 영역 -->
<div>
  <?php if (count($images) > 0): ?>
    <img src="<?= htmlspecialchars($images[0]) ?>" alt="상품 이미지" class="w-full" />
    <div class="flex space-x-2 mt-4">
      <?php foreach ($images as $img_url): ?>
        <img src="<?= htmlspecialchars($img_url) ?>" class="w-16 h-16 border cursor-pointer" />
      <?php endforeach; ?>
    </div>
  <?php else: ?>
    <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="상품 이미지" class="w-full" />
    <div class="flex space-x-2 mt-4">
      <img src="<?= htmlspecialchars($product['image_url']) ?>" class="w-16 h-16 border cursor-pointer" />
    </div>
  <?php endif; ?>
</div>


  <!-- 상품 정보 영역 -->
  <div class="space-y-4">
    <div class="flex space-x-3 border-b pb-2">
      <a href="upload.php" class="text-xs px-3 py-1 rounded hover:bg-black hover:text-white transition-colors duration-200 flex items-center space-x-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M16 12l-4-4m0 0l-4 4m4-4v12" />
        </svg>
        <span>UP하기</span>
      </a>

      <form method="POST" action="delete_product.php" onsubmit="return confirm('정말 삭제하시겠습니까?');" class="inline">
        <input type="hidden" name="id" value="<?= htmlspecialchars($product['id']) ?>">
        <button type="submit" class="text-xs px-3 py-1 rounded hover:bg-black hover:text-white transition-colors duration-200 flex items-center space-x-1">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v1h6V4a1 1 0 00-1-1m-4 0h4" />
          </svg>
          <span>상품삭제</span>
        </button>
      </form>

      <a href="upload.php?id=<?= htmlspecialchars($product['id']) ?>" class="text-xs px-3 py-1 rounded hover:bg-black hover:text-white transition-colors duration-200 flex items-center space-x-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4H6a2 2 0 00-2 2v4l7-7zm0 0l7 7-7 7v-4H6a2 2 0 01-2-2v-4l7-7z" />
        </svg>
        <span>상품수정</span>
      </a>

      <a href="status_change.php?id=<?= htmlspecialchars($product['id']) ?>" class="text-xs px-3 py-1 rounded hover:bg-black hover:text-white transition-colors duration-200 flex items-center space-x-1">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        <span>상태변경</span>
      </a>
    </div>

    <div class="text-sm text-gray-500">즉시 구매가</div>
    <div class="text-3xl font-bold text-black"><?= number_format($product['price']) ?>원</div>
    <h1 class="text-lg font-semibold leading-tight"><?= htmlspecialchars($product['title']) ?></h1>
    <p class="text-sm text-gray-500"><?= nl2br(htmlspecialchars($product['description'])) ?></p>

    <div class="text-sm text-blue-600 cursor-pointer underline">리뷰 61</div>

    <div class="grid grid-cols-3 gap-4 text-sm text-gray-700">
      <div>
        <div class="text-gray-400">카테고리</div>
        <div><?= htmlspecialchars($product['brand']) ?></div>
      </div>
      <div>
        <div class="text-gray-400">상태</div>
        <div><?= htmlspecialchars($product['condition'] ?? '') ?></div>
      </div>
      <div>
        <div class="text-gray-400">등록일</div>
        <div><?= date('Y-m-d', strtotime($product['created_at'] ?? 'now')) ?></div>
      </div>
    </div>

    <div class="flex items-center gap-2 mt-4">
      <button class="flex-1 bg-red-500 hover:bg-red-600 text-white py-3 rounded font-semibold">구매 <?= number_format($product['price']) ?>원</button>
      <button class="flex-1 bg-green-500 hover:bg-green-600 text-white py-3 rounded font-semibold">채팅하기</button>
    </div>

    <div class="flex items-center justify-between border p-2 mt-4 rounded">
      <span class="text-sm text-gray-700">관심상품</span>
      <span class="text-sm font-semibold">2,708</span>
    </div>

    <div class="mt-6">
      <h2 class="text-sm font-semibold mb-2">추가 혜택</h2>
      <ul class="text-sm text-gray-600 list-disc ml-5">
        <li>계좌 간편결제 시 <span class="font-bold">1% 적립</span></li>
        <li>크림카드 최대 20만원 상당 혜택 외 6건</li>
      </ul>
    </div>

    <div class="mt-6 flex items-center gap-2">
      <img src="popmart-logo.png" alt="팝마트" class="w-6 h-6 rounded" />
      <span class="text-sm font-semibold">Pop Mart</span>
      <span class="text-xs text-gray-400">· 관심 4,224</span>
    </div>

    <div class="mt-4 p-4 border rounded shadow-sm max-w-5xl mx-auto">
      <h2 class="text-lg font-semibold mb-4">추천 상품</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <?php
        $rec_stmt = $conn->prepare("SELECT id, title, price, image_url FROM products WHERE id != ? ORDER BY RAND() LIMIT 4");
        $rec_stmt->bind_param("i", $id);
        $rec_stmt->execute();
        $rec_result = $rec_stmt->get_result();

        while ($rec = $rec_result->fetch_assoc()):
        ?>
          <a href="product_detail.php?id=<?= htmlspecialchars($rec['id']) ?>" class="block border rounded overflow-hidden hover:shadow-lg">
            <img src="<?= htmlspecialchars($rec['image_url']) ?>" alt="<?= htmlspecialchars($rec['title']) ?>" class="w-full h-40 object-cover" />
            <div class="p-2 text-sm">
              <div class="truncate font-semibold"><?= htmlspecialchars($rec['title']) ?></div>
              <div class="text-red-600 font-bold"><?= number_format($rec['price']) ?>원</div>
            </div>
          </a>
        <?php endwhile; ?>
      </div>
    </div>

  </div>
</div>

<footer class="bg-white text-gray-700 text-xs mt-20 px-6 py-10 ">
  <div class="max-w-5xl mx-auto border-b border-gray-200 mb-8"></div>
  <div class="max-w-5xl mx-auto grid grid-cols-4 gap-12 mb-8">
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

  <div class="max-w-5xl mx-auto border-b border-gray-200 mb-6"></div>

  <div class="max-w-5xl mx-auto flex justify-between items-center py-4 text-xs text-gray-500">
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
