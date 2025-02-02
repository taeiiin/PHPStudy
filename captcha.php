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
$captchaImage = '';
$resultMsg = '';

function escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function handleGetRequest(CaptchaService $captchaService): array
{
    $captchaData = $captchaService->generateCaptcha();
    return [
        'key' => $captchaData['key'] ?? '',
        'image' => $captchaData['image'] ?? '',
    ];
}

function handlePostRequest(CaptchaService $captchaService): string
{
    $captchaKey = $_POST['key'] ?? '';
    $userValue = $_POST['value'] ?? '';

    if (!$captchaKey || !$userValue) {
        return "Invalid Input";
    }

    $response = $captchaService->verifyUserInput($captchaKey, $userValue);
    return $response['message'];
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    ['key' => $captchaKey, 'image' => $captchaImage] = handleGetRequest($captchaService);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $resultMsg = handlePostRequest($captchaService);
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Captcha Verify</title>
    <script>
        const resultMsg = "<?= escape($resultMsg) ?>";
        if (resultMsg) {
            alert(resultMsg);
            window.location.href = "index.php";
        }
    </script>
</head>
<body>
    <h1>Captcha Verify</h1>
    <?php if (!empty($captchaKey) && !empty($captchaImage)): ?>
        <div>
            <p><img src="<?= escape($captchaImage) ?>" alt="Captcha Image"></p>
        </div>
        <button onclick="window.location.href='index.php?refresh=true'" style="margin-bottom: 10px; padding:5px 10px;">Refresh Captcha</button>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <input type="hidden" name="key" value="<?= escape($captchaKey) ?>">
        <label for="value">Enter Captcha </label>
        <input type="text" id="value" name="value" required>
        <button type="submit">Verify Captcha</button>
    </form>
</body>
</html>