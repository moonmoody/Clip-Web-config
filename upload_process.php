<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'db.php';

require_once __DIR__ . '/vendor/autoload.php';
// ✅ .env 로딩
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


// echo '✅ ENV REGION: ' . getenv('AWS_REGION'); exit;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;


$region   = $_ENV['AWS_REGION']             ?? 'ap-northeast-2';
$accessId = $_ENV['AWS_ACCESS_KEY_ID']      ?? '';
$secret   = $_ENV['AWS_SECRET_ACCESS_KEY']  ?? '';
$bucket   = $_ENV['S3_BUCKET']              ?? '';

if (!isset($_SESSION['user_id'])) {
  echo "<script>alert('로그인 후 이용해 주세요.'); location.href='login.php';</script>";
  exit;
}

$user_id   = $_SESSION['user_id'];
$brand     = $_POST['category'];
$title     = $_POST['title'];
$price     = $_POST['price'];
$desc      = $_POST['description'];
$condition = $_POST['condition'];

$image_path = ''; // 대표 이미지 1장 URL
$uploaded_urls = [];

if (isset($_FILES['image']) && count($_FILES['image']['name']) > 0) {
$s3 = new S3Client([
  'version' => 'latest',
  'region'  => $region,
  'credentials' => [
    'key'    => $accessId,
    'secret' => $secret,
  ],
]);

  for ($i = 0; $i < count($_FILES['image']['name']); $i++) {
    if ($_FILES['image']['error'][$i] === 0) {
      $filename = time() . '_' . basename($_FILES['image']['name'][$i]);
      $tmp_path = $_FILES['image']['tmp_name'][$i];

      try {
        $result = $s3->putObject([
          'Bucket'     => $bucket,
          'Key'        => 'images/' . $filename,
          'SourceFile' => $tmp_path,
        //  'ACL'        => 'public-read',
        ]);
        $uploaded_urls[] = $result['ObjectURL'];
      } catch (AwsException $e) {
        die("❌ S3 업로드 실패: " . $e->getMessage());
      }
    }
  }

  // 대표 이미지 (첫 번째 이미지)
  $image_path = $uploaded_urls[0] ?? '';
} else {
  die("❌ 이미지가 없습니다");
}

// ✅ DB 저장
$stmt = $conn->prepare("INSERT INTO products (user_id, brand, title, price, image_url, description) VALUES (?, ?, ?, ?, ?, ?)");
if (!$stmt) {
  die("❌ DB 오류: " . $conn->error);
}

$stmt->bind_param("ississ", $user_id, $brand, $title, $price, $image_path, $desc);
$stmt->execute();

header("Location: index.php");
exit;
?>
