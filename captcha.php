<?php
require_once "vendor/autoload.php";

use ApiTest\CaptchaClient;
use ApiTest\CaptchaService;

$clientId = '';
$clientSecret = '';

$captchaClient = new CaptchaClient($clientId, $clientSecret);
$captchaService = new CaptchaService($captchaClient);

$captchaKey = '';
$captchaImageUrl = '';
$resultMsg = '';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $captchaData = $captchaService->generateCaptcha();
    $captchaKey = $captchaData['key'];
    $captchaImageUrl = $captchaData['image_url'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $captchaKey = $_POST['key'] ?? '';
        $userValue = $_POST['value'] ?? '';
        $response = $captchaService->verifyCaptcha($captchaKey, $userValue);

        $resultMsg = $response['is_valid'] ? 'Valid Captcha' : 'Invalid Captcha';
    } catch (Exception $e) {
        $resultMsg = $e->getMessage();
    }
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
            <p>Captcha Key: <strong><?= htmlspecialchars($captchaKey) ?></strong></p>
            <p><img src="<?= htmlspecialchars($captchaImageUrl) ?>" alt="Captcha Image"></p>
        </div>
    <?php endif; ?>

    <form action="index.php" method="POST">
        <input type="hidden" name="key" value="<?= htmlspecialchars($captchaKey ?? '') ?>">
        <label for="value">Enter Captcha </label>
        <input type="text" id="value" name="value" required>
        <button type="submit">Verify Captcha</button>
    </form>
</body>
</html>