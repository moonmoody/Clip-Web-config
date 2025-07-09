
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>번개장터 클론</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
  <style>
    #slider {
      min-width: 100%;
      transition: transform 0.5s ease-in-out;
    }
    .slide {
      min-width: 100%;
    }
    .dot {
      width: 10px;
      height: 10px;
      margin: 0 4px;
      background-color: rgba(0, 0, 0, 0.2);
      border-radius: 50%;
      cursor: pointer;
    }
    .dot.active {
      background-color: rgba(0, 0, 0, 0.6);
    }
  </style>
</head>
<body class="bg-white text-gray-800">

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


<!-- 배너 영역 -->
<div class="relative overflow-hidden w-full h-96 z-0">
  <div id="slider" class="flex w-full h-full">
    <div class="slide bg-cover bg-center" style="background-image: url('img/banner1.png');"></div>
    <div class="slide bg-cover bg-center" style="background-image: url('img/banner2.png');"></div>
    <div class="slide bg-cover bg-center" style="background-image: url('img/banner3.png');"></div>
  </div>
  <button id="prev" class="absolute left-4 top-1/2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 rounded-full px-3 py-1">&#10094;</button>
  <button id="next" class="absolute right-4 top-1/2 transform -translate-y-1/2 text-white bg-black bg-opacity-50 rounded-full px-3 py-1">&#10095;</button>
  <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex" id="dots"></div>
</div>

<!-- 상품 출력 영역 -->
<div class="max-w-screen-xl mx-auto px-4 py-8">
  <div class="mb-5">
    <h2 class="text-xl font-bold">🔥 지금 인기 상품</h2>
    <p class="text-sm text-gray-500">실시간 등록된 최신 상품을 만나보세요!</p>
  </div>
  <div id="product-list" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-5"></div>
</div>

<script>
const slider = document.getElementById("slider");
const slides = slider.querySelectorAll(".slide");
const dots = document.getElementById("dots");
let index = 0;

function showSlide(i) {
  slider.style.transform = `translateX(-${i * 100}%)`;
  dots.querySelectorAll(".dot").forEach(dot => dot.classList.remove("active"));
  dots.children[i].classList.add("active");
}

slides.forEach((_, i) => {
  const dot = document.createElement("div");
  dot.className = "dot" + (i === 0 ? " active" : "");
  dot.onclick = () => {
    index = i;
    showSlide(index);
  };
  dots.appendChild(dot);
});

document.getElementById("prev").onclick = () => {
  index = (index - 1 + slides.length) % slides.length;
  showSlide(index);
};
document.getElementById("next").onclick = () => {
  index = (index + 1) % slides.length;
  showSlide(index);
};

setInterval(() => {
  index = (index + 1) % slides.length;
  showSlide(index);
}, 5000);

showSlide(index);

fetch("products.php")
  .then(res => res.json())
  .then(data => {
    const container = document.getElementById("product-list");
    container.innerHTML = "";
    data.forEach(p => {
    const card = document.createElement("a");
card.href = `product_detail.php?id=${p.id}`;
card.className = "block rounded-lg bg-white overflow-hidden w-full max-w-xs transition";

card.innerHTML = `
<div class="w-full h-[200px] overflow-hidden rounded-lg">
  <img 
    src="${p.image_url}" 
    alt="${p.title}" 
    class="w-full h-full object-cover transform transition-transform duration-300 hover:scale-105"
  />
</div>
  <div class="p-3 space-y-1">
    <p class="text-base  text-gray-900 leading-snug">${p.title}</p></p>
    <p class="text-lg font-bold text-black">${Number(p.price).toLocaleString()}원</p>
    <p class="text-xs text-gray-500">장지동 · 2일 전</p>
    <span class="inline-block font-bold px-1.5 py-0.5 bg-green-100 text-green-700 rounded-sm" style="font-size: 10px;">
      안전거래
    </span>
  </div>
`;
      
      
      

      container.appendChild(card);
    });
  });

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
