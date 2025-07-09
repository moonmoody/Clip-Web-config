<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8" />
  <title>회원가입</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 flex justify-center items-center min-h-screen">
  <form action="signup_process.php" method="POST" class="bg-white p-8 rounded shadow-md w-96 space-y-4">
    <h2 class="text-xl font-bold text-center mb-4">회원가입</h2>

    <input type="text" name="username" placeholder="아이디" required class="w-full border px-3 py-2 rounded" />
    <input type="password" name="password" placeholder="비밀번호" required class="w-full border px-3 py-2 rounded" />
    
    <input type="text" name="name" placeholder="이름" required class="w-full border px-3 py-2 rounded" />
    <input type="text" name="phone" placeholder="전화번호 (예: 010-1234-5678)" required class="w-full border px-3 py-2 rounded" />
    <input type="text" name="region" placeholder="지역 (예: 서울 강남구)" required class="w-full border px-3 py-2 rounded" />

    <input type="date" name="birthdate" required class="w-full border px-3 py-2 rounded text-gray-700" />

    <select name="payment_method" required class="w-full border px-3 py-2 rounded text-gray-700">
      <option value="">결제 방식 선택</option>
      <option value="card">신용/체크카드</option>
      <option value="bank">무통장 입금</option>
      <option value="mobile">휴대폰 결제</option>
      <option value="naverpay">네이버페이</option>
      <option value="kakaopay">카카오페이</option>
    </select>

    <button type="submit" class="bg-black text-white w-full py-2 rounded hover:bg-gray-800">가입하기</button>

    <div class="text-center text-sm text-gray-500">
      이미 계정이 있으신가요? <a href="login.php" class="text-blue-500 underline">로그인</a>
    </div>
  </form>
</body>
</html>
