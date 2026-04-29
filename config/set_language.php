<?php
require_once __DIR__ . '/setup.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed'], JSON_OUT);
    exit;
}

$lang = $_POST['lang'] ?? '';
if (!in_array($lang, ['en', 'es'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid language'], JSON_OUT);
    exit;
}

$_SESSION['app_lang'] = $lang;
setcookie('app_lang', $lang, [
    'expires'  => time() + 365 * 24 * 3600,
    'path'     => '/',
    'samesite' => 'Lax',
    'secure'   => false,
    'httponly' => true,
]);

echo json_encode(['success' => true, 'lang' => $lang], JSON_OUT);
