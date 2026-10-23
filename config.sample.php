<?php
// DEMO admin login (username: admin / password: admin123).
// For your own private login: copy this file to config.php, change the values,
// and do NOT upload config.php to GitHub (it is already in .gitignore).
// Make a new hash with:  php -r "echo password_hash('your-password', PASSWORD_DEFAULT);"
return [
  'user'      => 'admin',
  'pass_hash' => '$2y$10$EkH0/uUzbFv7JwRFuZawl.44QHzQvcCZFryjRKz8YlEqOOiaFnvFO',
];
