<?php 
session_start(); 
?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>상품 등록 - 번개장터</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
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

<!-- 등록 폼 -->
<div class="max-w-3xl mx-auto px-4 py-12 text-sm space-y-6">
  <form id="uploadForm" action="upload_process.php" method="POST" enctype="multipart/form-data" class="bg-white p-6 space-y-6">

    <!-- 이미지 업로드 (한 개 input, 최대 5장 선택 가능) -->
    <div class="flex justify-start mb-4">
      <label for="image" class="cursor-pointer flex flex-col items-center justify-center w-32 h-32 border rounded text-gray-400 relative">
        <img src="https://cdn-icons-png.flaticon.com/512/685/685655.png" class="w-6 h-6" alt="이미지 등록" />
        <span id="imageCount" class="text-xs mt-1">0/5</span>
      </label>
      <input type="file" id="image" name="image[]" accept="image/*" multiple class="hidden" onchange="handleFiles(event)">
      <div id="previewContainer" class="flex space-x-2 ml-4"></div>
    </div>

    <!-- 제목 -->
    <div class="space-y-2">
      <label class="block font-medium" for="title">상품명</label>
      <input type="text" id="title" name="title" class="w-full border px-4 py-2 rounded" placeholder="상품명을 입력하세요" required>
    </div>

    <!-- 카테고리 -->
    <div class="space-y-2">
      <label class="block font-medium" for="category">카테고리</label>
      <select id="category" name="category" class="w-full border px-4 py-2 rounded">
        <option>수입명품</option>
        <option>패션의류</option>
        <option>패션잡화</option>
        <option>뷰티</option>
        <option>출산/유아동</option>
        <option>모바일/태블릿</option>
      </select>
    </div>

    <!-- 가격 -->
    <div class="space-y-2">
      <label class="block font-medium" for="price">₩ 판매가격</label>
      <input type="number" id="price" name="price" class="w-full border px-4 py-2 rounded" placeholder="가격을 입력하세요" required>
    </div>

    <!-- 설명 -->
    <div class="space-y-2">
      <label class="block font-medium" for="description">상품 설명</label>
      <textarea id="description" name="description" rows="5" class="w-full border px-4 py-2 rounded text-sm"
        placeholder="- 상품명(브랜드)\n- 구매 시기\n- 사용 기간\n- 하자 여부 등"></textarea>
    </div>

    <!-- 상품 상태 -->
    <div class="space-y-2">
      <label class="block font-medium text-sm">상품 상태</label>
      <div class="flex gap-2">
        <button type="button" onclick="selectCondition('중고')" id="btn-used"
          class="px-4 py-1.5 rounded border border-black bg-black text-white text-sm">중고</button>
        <button type="button" onclick="selectCondition('새상품')" id="btn-new"
          class="px-4 py-1.5 rounded border border-black bg-white text-black text-sm">새상품</button>
      </div>
      <input type="hidden" name="condition" id="condition" value="중고" />
    </div>

    <!-- 판매하기 버튼 -->
    <div class="text-center mt-4">
      <button type="button" onclick="openModal()" class="bg-black text-white px-20 py-2 rounded hover:bg-gray-800 transition">
        판매하기
      </button>
    </div>
  </form>
</div>

<!-- ✅ 모달창 -->
<div id="confirmModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white p-6 rounded shadow-md w-80 text-center space-y-4">
    <p class="text-sm font-semibold">안전거래로 등록하시겠습니까?</p>

    <div class="flex justify-center gap-4">
      <button onclick="submitForm()"
        class="px-6 py-2 rounded text-white transition"
        style="background-color: #41B979;"
        onmouseover="this.style.backgroundColor='#369f68'"
        onmouseout="this.style.backgroundColor='#41B979'">
        예
      </button>
      <button onclick="closeModal()"
        class="px-6 py-2 rounded text-white transition"
        style="background-color: #EF6253;"
        onmouseover="this.style.backgroundColor='#d14f45'"
        onmouseout="this.style.backgroundColor='#EF6253'">
        아니오
      </button>
    </div>
  </div>
</div>

<script>
  const maxImages = 5;
  let selectedFiles = [];

  function handleFiles(event) {
  const files = Array.from(event.target.files);
  for (const file of files) {
    if (selectedFiles.length >= maxImages) {
      alert('최대 5개까지 업로드 가능합니다.');
      break;
    }
      if (!selectedFiles.some(f => f.name === file.name && f.size === file.size)) {
        selectedFiles.push(file);
      }
    }
    updatePreview();
    updateInputFiles();
    updateImageCount();
  }

  function updatePreview() {
    const container = document.getElementById('previewContainer');
    container.innerHTML = '';

    selectedFiles.forEach((file, idx) => {
      const reader = new FileReader();
      reader.onload = e => {
        const div = document.createElement('div');
        div.className = 'relative w-32 h-32 border rounded overflow-hidden';

        const img = document.createElement('img');
        img.src = e.target.result;
        img.className = 'w-full h-full object-cover';

        const delBtn = document.createElement('button');
        delBtn.type = 'button';
        delBtn.className = 'absolute top-1 right-1 bg-black bg-opacity-50 text-white rounded-full w-6 h-6 flex items-center justify-center text-sm';
        delBtn.innerHTML = '×';
        delBtn.onclick = () => {
          selectedFiles.splice(idx, 1);
          updatePreview();
          updateInputFiles();
          updateImageCount();
        };

        div.appendChild(img);

        if (idx === 0) {
          const badge = document.createElement('div');
          badge.textContent = '대표이미지';
          badge.className = 'absolute top-1 left-1 bg-gray-800 bg-opacity-75 text-white text-xs px-1 rounded';
          div.appendChild(badge);
        }

        div.appendChild(delBtn);
        container.appendChild(div);
      };
      reader.readAsDataURL(file);
    });
  }

  function updateInputFiles() {
    const dataTransfer = new DataTransfer();
    selectedFiles.forEach(file => dataTransfer.items.add(file));
    document.getElementById('image').files = dataTransfer.files;
  }

  function updateImageCount() {
    const countSpan = document.getElementById('imageCount');
    countSpan.textContent = `${selectedFiles.length}/5`;
  }

  function selectCondition(type) {
    const usedBtn = document.getElementById('btn-used');
    const newBtn = document.getElementById('btn-new');
    const conditionInput = document.getElementById('condition');
    conditionInput.value = type;

    if (type === '중고') {
      usedBtn.classList.add('bg-black', 'text-white');
      usedBtn.classList.remove('bg-white', 'text-black');
      newBtn.classList.add('bg-white', 'text-black');
      newBtn.classList.remove('bg-black', 'text-white');
    } else {
      newBtn.classList.add('bg-black', 'text-white');
      newBtn.classList.remove('bg-white', 'text-black');
      usedBtn.classList.add('bg-white', 'text-black');
      usedBtn.classList.remove('bg-black', 'text-white');
    }
  }

  function openModal() {
    document.getElementById('confirmModal').classList.remove('hidden');
  }
  function closeModal() {
    document.getElementById('confirmModal').classList.add('hidden');
  }
  function submitForm() {
    document.getElementById('uploadForm').submit();
  }

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
