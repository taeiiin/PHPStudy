<?php
require_once "vendor/autoload.php";

use ApiTest\CaptchaClient;
use ApiTest\CaptchaService;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$clientId = $_ENV['NAVER_API_CLIENT_ID'];
$clientSecret = $_ENV['NAVER_API_CLIENT_SECRET'];

$captchaClient = new CaptchaClient($clientId, $clientSecret);
$captchaService = new CaptchaService($captchaClient);

$captchaKey = '';
$captchaImageUrl = '';
$resultMsg = '';

//캡차 키와 이미지 생성
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $captchaData = $captchaService->generateCaptcha();
    $captchaKey = $captchaData['key'];
    $captchaImageUrl = $captchaData['image_url'];
}

//사용자 입력 검증
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $captchaKey = $_POST['key'] ?? '';
    $userValue = $_POST['value'] ?? '';

    $response = $captchaService->verifyUserInput($captchaKey, $userValue);
    $resultMsg = $response['message'];
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Captcha Verify</title>
    <script>
        const resultMsg = "<?= htmlspecialchars($resultMsg, ENT_QUOTES) ?>";
        if (resultMsg) {
            alert(resultMsg);
            window.location.href = "index.php";
        }
    </script>
</head>
<body>
    <h1>Captcha Verify</h1>
    <?php if (!empty($captchaKey) && !empty($captchaImageUrl)): ?>
        <div>
            <p><img src="<?= htmlspecialchars($captchaImageUrl) ?>" alt="Captcha Image"></p>
        </div>
        <button onclick="window.location.href='index.php?refresh=true'" style="margin-bottom: 10px; padding:5px 10px;">Refresh Captcha</button>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <input type="hidden" name="key" value="<?= htmlspecialchars($captchaKey ?? '') ?>">
        <label for="value">Enter Captcha </label>
        <input type="text" id="value" name="value" required>
        <button type="submit">Verify Captcha</button>
    </form>
</body>
</html>