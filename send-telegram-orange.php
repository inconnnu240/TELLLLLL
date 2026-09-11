<?php
header('Content-Type: application/json; charset=utf-8');

$token = getenv('TELEGRAM_BOT_TOKEN');
$chatId = getenv('TELEGRAM_CHAT_ID');

if (!$token || !$chatId) {
    http_response_code(500);
    echo json_encode([
        'ok' => false,
        'error' => 'Variables Telegram absentes'
    ]);
    exit;
}

$url = "https://api.telegram.org/bot{$token}/sendMessage";

$ch = curl_init($url);

curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 20,
    CURLOPT_POSTFIELDS => http_build_query([
        'chat_id' => $chatId,
        'text' => 'TEST TELEGRAM — serveur connecté'
    ])
]);

$response = curl_exec($ch);
$curlError = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

curl_close($ch);

if ($response === false) {
    http_response_code(502);
    echo json_encode([
        'ok' => false,
        'error' => 'cURL',
        'detail' => $curlError,
        'http_code' => $httpCode
    ]);
    exit;
}

$result = json_decode($response, true);

echo json_encode([
    'ok' => !empty($result['ok']),
    'http_code' => $httpCode,
    'telegram_description' => $result['description'] ?? null
]);
