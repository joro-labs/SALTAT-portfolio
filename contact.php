<?php

date_default_timezone_set('Asia/Manila');
header('Content-Type: application/json');

function reply($ok, $message, $code = 200) {
  http_response_code($code);
  echo json_encode(['ok' => $ok, 'message' => $message], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
  exit;
}


function field($key) {
  return isset($_POST[$key]) && is_string($_POST[$key]) ? trim($_POST[$key]) : '';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  reply(false, 'Use the contact form to send a message.', 405);
}

$name    = preg_replace('/\s+/u', ' ', field('name')) ?? '';   
$email   = field('email');
$message = field('message');


$nameLen = mb_strlen($name);
$msgLen  = mb_strlen($message);

if ($nameLen < 2 || $nameLen > 60 || mb_strlen($email) > 100 || !filter_var($email, FILTER_VALIDATE_EMAIL) || $msgLen < 10 || $msgLen > 500) {
  reply(false, 'Please check your name, email and message.', 422);
}

$dir = __DIR__ . '/data';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$line  = json_encode(
  ['time' => date('c'), 'name' => $name, 'email' => $email, 'message' => $message],
  JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE
) . PHP_EOL;
$saved = file_put_contents($dir . '/messages.txt', $line, FILE_APPEND | LOCK_EX);

if ($saved === false) {
  reply(false, 'Could not save your message. Try again later.', 500);
}


reply(true, 'Message sent. Thank you, ' . $name . '!');
