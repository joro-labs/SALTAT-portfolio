<?php
// Admin login / logout. Called with fetch() from js/script.js.
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();
header('Content-Type: application/json');

// Private credentials live in config.php (not on GitHub).
// If it does not exist, the demo login in config.sample.php is used.
$config = file_exists(__DIR__ . '/config.php')
  ? require __DIR__ . '/config.php'
  : require __DIR__ . '/config.sample.php';

function reply($ok, $message, $code = 200) {
  http_response_code($code);
  echo json_encode(['ok' => $ok, 'message' => $message]);
  exit;
}

// Read one form field as a string (ignores tampered array values).
function field($key) {
  return isset($_POST[$key]) && is_string($_POST[$key]) ? $_POST[$key] : '';
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  reply(false, 'Method not allowed.', 405);
}

if (field('action') === 'logout') {
  session_unset();
  session_destroy();
  reply(true, 'Logged out successfully.');
}

$username = trim(field('username'));
$password = field('password');

usleep(300000); // small delay makes password guessing slower

if (hash_equals($config['user'], $username) && password_verify($password, $config['pass_hash'])) {
  session_regenerate_id(true);
  $_SESSION['admin'] = $username;
  reply(true, 'Login successful! Reloading...');
}

reply(false, 'Invalid username or password.', 401);
