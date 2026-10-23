<?php
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();
header('Content-Type: application/json');

function reply($ok, $message, $code = 200) {
  http_response_code($code);
  echo json_encode(['ok' => $ok, 'message' => $message]);
  exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  reply(false, 'Method not allowed.', 405);
}


if (empty($_SESSION['admin'])) {
  reply(false, 'Please log in first.', 403);
}


$id = isset($_POST['id']) && is_string($_POST['id']) ? $_POST['id'] : '';
if (!preg_match('/^[a-f0-9]{40}$/', $id)) {
  reply(false, 'Invalid message.', 422);
}

$file = __DIR__ . '/data/messages.txt';
if (!file_exists($file)) {
  reply(false, 'Message not found.', 404);
}

$fp = fopen($file, 'c+');
if (!$fp) {
  reply(false, 'Could not open the messages file.', 500);
}
flock($fp, LOCK_EX); 

$kept = [];
$found = false;
while (($line = fgets($fp)) !== false) {
  $line = rtrim($line, "\r\n");
  if ($line === '') continue;
  if (sha1($line) === $id) { $found = true; continue; }   // skip the message to delete
  $kept[] = $line;
}

if ($found) {
  ftruncate($fp, 0);
  rewind($fp);
  fwrite($fp, $kept ? implode(PHP_EOL, $kept) . PHP_EOL : '');
  fflush($fp);
}

flock($fp, LOCK_UN);
fclose($fp);

if (!$found) {
  reply(false, 'Message not found (maybe already deleted).', 404);
}
reply(true, 'Message deleted.');