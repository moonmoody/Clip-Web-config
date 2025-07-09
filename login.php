<?php session_start(); ?>
<!DOCTYPE html>
<html lang="ko">
<head>
  <meta charset="UTF-8">
  <title>로그인</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
</head>
<body class="bg-gray-100 text-gray-800 flex items-center justify-center h-screen">
  <form action="login_process.php" method="POST" class="bg-white p-8 rounded shadow-md w-80 space-y-4">
    <h2 class="text-lg font-bold text-center">로그인</h2>
    <input type="text" name="username" placeholder="아이디" required class="w-full border px-3 py-2 rounded" />
    <input type="password" name="password" placeholder="비밀번호" required class="w-full border px-3 py-2 rounded" />
    <button type="submit" class="bg-black text-white w-full py-2 rounded hover:bg-gray-800">로그인</button>
    <div class="text-center text-sm text-gray-500">
      계정이 없으신가요? <a href="signup.php" class="text-blue-500 underline">회원가입</a>
    </div>
  </form>
</body>
</html>
