<?php
// Admin only: add / edit / delete projects and add / remove skills.
// Saves everything to data/content.json. Called with fetch() from js/script.js.
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();
header('Content-Type: application/json');
require __DIR__ . '/content.php';

function reply($ok, $message, $code = 200) {
  http_response_code($code);
  echo json_encode(['ok' => $ok, 'message' => $message], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
  exit;
}

// Read one form field as a trimmed string (ignores tampered array values).
function field($key) {
  return isset($_POST[$key]) && is_string($_POST[$key]) ? trim($_POST[$key]) : '';
}

// Same, but squeezed onto one line.
function line($key) {
  return trim(preg_replace('/\s+/u', ' ', field($key)) ?? '');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  reply(false, 'Method not allowed.', 405);
}

// The server checks the login. Hiding the buttons in the page is not enough.
if (empty($_SESSION['admin'])) {
  reply(false, 'Please log in first.', 403);
}

// Lock so two saves at the same time cannot overwrite each other.
$dir = __DIR__ . '/data';
if (!is_dir($dir)) mkdir($dir, 0755, true);
$lock = fopen($dir . '/content.lock', 'c');
if (!$lock || !flock($lock, LOCK_EX)) {
  reply(false, 'Server is busy. Try again.', 500);
}

$content = load_content();
$action  = field('action');

if ($action === 'project_save') {
  $p = [
    'title'   => line('title'),
    'cat'     => field('cat'),
    'img'     => field('img'),
    'desc'    => line('desc'),
    'learned' => line('learned'),
    'tech'    => line('tech'),
    'link'    => field('link'),
  ];

  $titleLen = mb_strlen($p['title']);
  $descLen  = mb_strlen($p['desc']);
  $techLen  = mb_strlen($p['tech']);

  if ($titleLen < 2 || $titleLen > 80)  reply(false, 'Title must be 2 to 80 characters.', 422);
  if (!array_key_exists($p['cat'], categories())) reply(false, 'Pick a valid category.', 422);
  if ($descLen < 10 || $descLen > 400)  reply(false, 'Description must be 10 to 400 characters.', 422);
  if (mb_strlen($p['learned']) > 300)   reply(false, 'What I learned is too long (max 300).', 422);
  if ($techLen < 2 || $techLen > 100)   reply(false, 'Tech must be 2 to 100 characters.', 422);
  // Letters, numbers, - and _ only, so nobody can point to other folders with "../".
  if (!preg_match('/^[A-Za-z0-9_-]{0,60}$/', $p['img'])) {
    reply(false, 'Screenshot name can only use letters, numbers, - and _.', 422);
  }
  // Only http/https links, so a "javascript:" link cannot be saved.
  if ($p['link'] !== '') {
    $scheme = strtolower((string)parse_url($p['link'], PHP_URL_SCHEME));
    if (mb_strlen($p['link']) > 200 || !filter_var($p['link'], FILTER_VALIDATE_URL) || !in_array($scheme, ['http', 'https'], true)) {
      reply(false, 'Enter a valid link that starts with http:// or https://', 422);
    }
  }

  $index = field('index');
  if ($index === '') {
    $content['projects'][] = $p;                      // add
    $message = 'Project added.';
  } else {
    if (!ctype_digit($index) || !isset($content['projects'][(int)$index])) {
      reply(false, 'Project not found.', 404);
    }
    $content['projects'][(int)$index] = $p;           // edit
    $message = 'Project updated.';
  }

} elseif ($action === 'project_delete') {
  $index = field('index');
  if (!ctype_digit($index) || !isset($content['projects'][(int)$index])) {
    reply(false, 'Project not found.', 404);
  }
  unset($content['projects'][(int)$index]);
  $content['projects'] = array_values($content['projects']);
  $message = 'Project deleted.';

} elseif ($action === 'skill_add') {
  $group = field('group');
  $name  = line('name');
  $len   = mb_strlen($name);

  if (!isset($content['skills'][$group])) reply(false, 'Skill group not found.', 404);
  if ($len < 1 || $len > 40) reply(false, 'Skill name must be 1 to 40 characters.', 422);
  foreach ($content['skills'][$group] as $existing) {
    if (mb_strtolower($existing) === mb_strtolower($name)) reply(false, 'That skill is already in this group.', 422);
  }
  $content['skills'][$group][] = $name;
  $message = 'Skill added.';

} elseif ($action === 'skill_remove') {
  $group = field('group');
  $name  = field('name');

  if (!isset($content['skills'][$group])) reply(false, 'Skill group not found.', 404);
  $pos = array_search($name, $content['skills'][$group], true);
  if ($pos === false) reply(false, 'Skill not found.', 404);
  unset($content['skills'][$group][$pos]);
  $content['skills'][$group] = array_values($content['skills'][$group]);
  $message = 'Skill removed.';

} else {
  reply(false, 'Unknown action.', 400);
}

if (!save_content($content)) {
  reply(false, 'Could not save. Check that the data folder is writable.', 500);
}
reply(true, $message);