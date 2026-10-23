<?php
date_default_timezone_set('Asia/Manila');
session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
session_start();
$isLoggedIn = !empty($_SESSION['admin']);

$owner = [
  'name'    => 'John Joros D. Saltat',
  'tagline' => 'BSIT Student | Aspiring Networking & Cybersecurity Professional',
  'intro'   => 'A 3rd year BSIT student at PLV who wants to build a career in networking or cybersecurity. This portfolio is my Event-Driven Programming midterm project.',
  'school'  => 'Pamantasan ng Lungsod ng Valenzuela (PLV)',
  'program' => 'BS Information Technology, 3rd Year',
  'github'  => 'https://github.com/joro-labs',
];

$about = [
  'Background'       => 'I am a 3rd year Information Technology student at Pamantasan ng Lungsod ng Valenzuela (PLV). Through school work and hands-on practice, I have learned web development, networking basics and PC hardware.',
  'Interests'        => 'Networking, cybersecurity, web development, and building and troubleshooting PCs.',
  'Goals'            => 'To become a professional in networking or cybersecurity and help organizations keep their systems connected and secure.',
  'Learning journey' => 'This semester I am learning event-driven programming with JavaScript and PHP, along with Git and GitHub for version control.',
];

require __DIR__ . '/content.php';
$content      = load_content();
$skills       = $content['skills'];
$projects     = $content['projects'];
$filterLabels = categories();

$messages = [];
$msgFile = __DIR__ . '/data/messages.txt';
if ($isLoggedIn && file_exists($msgFile)) {
  $lines = file($msgFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
  foreach (array_reverse($lines) as $line) {
    $decoded = json_decode($line, true);
    if ($decoded) {
      $decoded['id'] = sha1(rtrim($line, "\r\n"));
      $messages[] = $decoded;
    }
  }
}

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function find_img($base) {
  foreach (['jpg', 'jpeg', 'png', 'webp'] as $x) {
    if (file_exists(__DIR__ . "/img/$base.$x")) return "img/$base.$x";
  }
  return '';
}

$profile    = find_img('profile');
$initial    = mb_strtoupper(mb_substr($owner['name'], 0, 1));
$parts      = array_map('trim', explode('|', $owner['tagline']));
$usedCats   = array_unique(array_column($projects, 'cat'));
$skillCount = 0;
foreach ($skills as $group) $skillCount += count($group);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($owner['name']) ?> | Portfolio</title>
  <meta name="description" content="<?= e($owner['intro']) ?>">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <header class="top">
    <a class="logo" href="#home" aria-label="Home"><?= e($initial) ?></a>
    <nav class="pill-nav" aria-label="Main">
      <a href="#home">Home</a>
      <a href="#about">About</a>
      <a href="#projects">Projects</a>
      <a href="#skills">Skills</a>
      <a href="#contact">Contact</a>
      <?php if ($isLoggedIn): ?>
        <a href="#inbox">Inbox (<?= count($messages) ?>)</a>
      <?php endif; ?>
    </nav>
    <div class="actions">
      <?php if ($isLoggedIn): ?>
        <button id="logoutBtn" class="btn-sm" type="button">Logout (<?= e($_SESSION['admin']) ?>)</button>
      <?php else: ?>
        <button id="loginBtn" class="btn-sm" type="button">Login</button>
      <?php endif; ?>
      <a class="btn-sm talk" href="#contact">Let's Talk</a>
    </div>
  </header>

  <main>
    <section id="home" class="hero">
      <div class="wrap hero-in">
        <div class="hero-text">
          <p class="small">Hello, I'm <?= e($owner['name']) ?></p>
          <h1><?= e($parts[0]) ?><?php if (!empty($parts[1])): ?><br><?= e($parts[1]) ?><?php endif; ?></h1>
          <p class="intro"><?= e($owner['intro']) ?></p>
          <p id="greeting"></p>
          <div class="hero-links">
            <a class="btn" href="#projects">View my projects</a>
            <a class="btn ghost" href="#contact">Contact me</a>
          </div>
        </div>
        <div class="photo">
          <?php if ($profile): ?>
            <img src="<?= e($profile) ?>" alt="Photo of <?= e($owner['name']) ?>" width="320" height="380">
          <?php else: ?>
            <div class="photo-fallback"><?= e($initial) ?></div>
          <?php endif; ?>
        </div>
      </div>
    </section>

    <section id="about">
      <div class="wrap">
        <div class="sec-head"><h2>About Me</h2><p>Who I am and where I'm headed</p></div>
        <div class="about-grid">
          <?php $i = 0; foreach ($about as $title => $text): $i++; ?>
            <div class="about-item">
              <span class="num"><?= str_pad($i, 2, '0', STR_PAD_LEFT) ?></span>
              <div><h3><?= e($title) ?></h3><p><?= e($text) ?></p></div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="stats">
          <div class="stat"><b data-count="<?= count($projects) ?>"><?= count($projects) ?></b><span>Projects</span></div>
          <div class="stat"><b data-count="<?= $skillCount ?>"><?= $skillCount ?></b><span>Skills</span></div>
        </div>
      </div>
    </section>

    <section id="projects">
      <div class="wrap">
        <div class="sec-head"><h2>Projects</h2><p>School work and personal coding exercises. Click a card for details.</p></div>
        <?php if ($isLoggedIn): ?>
          <div class="add-bar"><button id="addProject" class="btn-sm" type="button">+ Add project</button></div>
        <?php endif; ?>
        <div class="tools">
          <div class="filters" role="group" aria-label="Filter projects">
            <button class="filter active" data-filter="all" type="button">All</button>
            <?php foreach ($filterLabels as $key => $label): if (!in_array($key, $usedCats, true)) continue; ?>
              <button class="filter" data-filter="<?= e($key) ?>" type="button"><?= e($label) ?></button>
            <?php endforeach; ?>
          </div>
          <input id="search" type="search" placeholder="Search projects (press / to focus)" aria-label="Search projects">
        </div>
        <div class="grid" id="grid">
          <?php foreach ($projects as $idx => $p): $img = find_img($p['img']); ?>
            <article class="card" data-index="<?= $idx ?>" data-img="<?= e($p['img']) ?>" data-cat="<?= e($p['cat']) ?>"
                     data-title="<?= e($p['title']) ?>" data-desc="<?= e($p['desc']) ?>"
                     data-learned="<?= e($p['learned']) ?>" data-tech="<?= e($p['tech']) ?>"
                     data-link="<?= e($p['link']) ?>" data-shot="<?= e($img) ?>" tabindex="0">
              <div class="shot-wrap">
                <?php if ($img): ?>
                  <img class="shot" src="<?= e($img) ?>" alt="Screenshot of <?= e($p['title']) ?>" loading="lazy">
                <?php else: ?>
                  <div class="shot-empty"><?= e($p['title']) ?></div>
                <?php endif; ?>
              </div>
              <h3><?= e($p['title']) ?></h3>
              <p><?= e($p['desc']) ?></p>
              <ul class="techs">
                <?php foreach (explode(',', $p['tech']) as $t): ?><li><?= e(trim($t)) ?></li><?php endforeach; ?>
              </ul>
              <?php if ($isLoggedIn): ?>
                <div class="admin-bar">
                  <button type="button" class="btn-sm admin-btn edit-project">Edit</button>
                  <button type="button" class="btn-sm danger admin-btn delete-project">Delete</button>
                </div>
              <?php endif; ?>
            </article>
          <?php endforeach; ?>
        </div>
        <p id="empty" class="note center" hidden>No project matches your search. Clear the box or pick "All".</p>
      </div>
    </section>

    <section id="skills">
      <div class="wrap">
        <div class="sec-head"><h2>Skills</h2><p>Technologies and tools I have practiced in school and on my own</p></div>
        <div class="skill-grid">
          <?php foreach ($skills as $group => $items): ?>
            <div class="card plain">
              <h3><?= e($group) ?></h3>
              <ul class="techs">
                <?php foreach ($items as $s): ?><li class="chip" tabindex="0" data-name="<?= e($s) ?>"><?= e($s) ?><?php if ($isLoggedIn): ?><button type="button" class="chip-x" data-group="<?= e($group) ?>" data-name="<?= e($s) ?>" aria-label="Remove <?= e($s) ?>">&times;</button><?php endif; ?></li><?php endforeach; ?>
              </ul>
              <?php if ($isLoggedIn): ?>
                <form class="skill-add" data-group="<?= e($group) ?>">
                  <input type="text" name="name" maxlength="40" placeholder="Add a skill" aria-label="Add a skill to <?= e($group) ?>" required>
                  <button class="btn-sm" type="submit">Add</button>
                </form>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
        <p id="skillNote" class="note center" aria-live="polite">Hover or focus a skill to see what I use it for.</p>
      </div>
    </section>

    <section id="contact">
      <div class="wrap">
        <div class="sec-head"><h2>Contact</h2><p>Leave a message and I will read it in my admin inbox</p></div>
        <div class="card plain contact-grid">
          <ul class="links">
            <li><span>GitHub</span><a href="<?= e($owner['github']) ?>" target="_blank" rel="noopener"><?= e($owner['github']) ?></a></li>
            <li><span>School</span><b><?= e($owner['school']) ?></b></li>
            <li><span>Program</span><b><?= e($owner['program']) ?></b></li>
          </ul>
          <form id="contactForm" action="contact.php" method="post" novalidate>
            <label>Name
              <input type="text" name="name" id="name" maxlength="60" autocomplete="name" required>
              <small class="err" data-for="name"></small>
            </label>
            <label>Email
              <input type="email" name="email" id="email" maxlength="100" autocomplete="email" required>
              <small class="err" data-for="email"></small>
            </label>
            <label>Message
              <textarea name="message" id="message" rows="5" maxlength="500" required></textarea>
              <small id="count">0 / 500</small>
              <small class="err" data-for="message"></small>
            </label>
            <button class="btn" type="submit">Send message</button>
            <p id="status" role="status"></p>
          </form>
        </div>
      </div>
    </section>

    <?php if ($isLoggedIn): ?>
    <section id="inbox">
      <div class="wrap">
        <div class="sec-head"><h2>Admin Inbox</h2><p>Messages sent through the contact form</p></div>
        <?php if (empty($messages)): ?>
          <p class="note center">No messages received yet.</p>
        <?php else: ?>
          <p id="inboxStatus" class="note center" role="status"></p>
          <div class="grid" id="inboxGrid">
            <?php foreach ($messages as $m): ?>
              <div class="card plain" data-id="<?= e($m['id']) ?>">
                <div class="msg-head">
                  <h3><?= e($m['name'] ?? 'Unknown') ?></h3>
                  <small class="note"><?= e(isset($m['time']) ? date('M j, Y g:i A', strtotime($m['time'])) : '') ?></small>
                </div>
                <p class="msg-email"><a href="mailto:<?= e($m['email'] ?? '') ?>"><?= e($m['email'] ?? '') ?></a></p>
                <p class="msg-body"><?= nl2br(e($m['message'] ?? '')) ?></p>
                <button type="button" class="btn-sm danger delete-msg">Delete</button>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </section>
    <?php endif; ?>
  </main>

  <footer>
    <p>&copy; <?= date('Y') ?> <?= e($owner['name']) ?> &middot; <a href="<?= e($owner['github']) ?>" target="_blank" rel="noopener">GitHub</a></p>
  </footer>

  <div id="modal" class="modal" hidden>
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="mTitle">
      <button id="closeModal" type="button" aria-label="Close">Close</button>
      <img id="mShot" class="shot" alt="" hidden>
      <h3 id="mTitle"></h3>
      <p id="mDesc"></p>
      <p id="mLearnedWrap"><strong>What I learned:</strong> <span id="mLearned"></span></p>
      <p><strong>Tech:</strong> <span id="mTech"></span></p>
      <a id="mLink" class="btn" href="#" target="_blank" rel="noopener" hidden>View project</a>
    </div>
  </div>

  <div id="loginModal" class="modal" hidden>
    <div class="modal-box login-box" role="dialog" aria-modal="true" aria-labelledby="lTitle">
      <button id="closeLogin" class="close-btn" type="button" aria-label="Close">Close</button>
      <h3 id="lTitle">Admin Login</h3>
      <p class="note">Sign in to manage your messages, projects and skills.</p>
      <form id="loginForm" action="auth.php" method="post" novalidate>
        <label>Username
          <input type="text" name="username" id="loginUser" required autocomplete="username">
        </label>
        <label>Password
          <div class="pass-wrap">
            <input type="password" name="password" id="loginPass" required autocomplete="current-password">
            <button type="button" id="togglePass" class="btn-sm" aria-label="Show or hide password">Show</button>
          </div>
        </label>
        <button class="btn" type="submit">Sign In</button>
        <p id="loginStatus" role="status"></p>
      </form>
    </div>
  </div>

  <?php if ($isLoggedIn): ?>
  <div id="projectModal" class="modal" hidden>
    <div class="modal-box" role="dialog" aria-modal="true" aria-labelledby="pHead">
      <button id="closeProject" class="close-btn" type="button" aria-label="Close">Close</button>
      <h3 id="pHead">Add project</h3>
      <form id="projectForm" action="save.php" method="post">
        <input type="hidden" name="action" value="project_save">
        <input type="hidden" name="index" value="">
        <label>Title
          <input type="text" name="title" maxlength="80" required>
        </label>
        <label>Category
          <select name="cat" required>
            <?php foreach ($filterLabels as $key => $label): ?>
              <option value="<?= e($key) ?>"><?= e($label) ?></option>
            <?php endforeach; ?>
          </select>
        </label>
        <label>Description
          <textarea name="desc" rows="3" maxlength="400" required></textarea>
        </label>
        <label>What I learned
          <textarea name="learned" rows="2" maxlength="300"></textarea>
        </label>
        <label>Tech (separate with commas)
          <input type="text" name="tech" maxlength="100" placeholder="PHP, MySQL, JavaScript" required>
        </label>
        <label>Screenshot name
          <input type="text" name="img" maxlength="60" placeholder="still-becoming">
          <small class="note">File name inside the img/ folder, without .jpg or .png. Leave empty for none.</small>
        </label>
        <label>Project link
          <input type="url" name="link" maxlength="200" placeholder="https://github.com/...">
        </label>
        <button class="btn" type="submit">Save project</button>
        <p id="projectStatus" role="status"></p>
      </form>
    </div>
  </div>
  <?php endif; ?>

  <script src="js/script.js"></script>
</body>
</html>