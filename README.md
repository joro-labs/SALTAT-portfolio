# Portfolio: John Joros D. Saltat

Event-Driven Programming midterm project. Built with HTML, CSS, JavaScript and PHP.

## Run locally
Requires PHP 7.4+ (or XAMPP).
```
php -S localhost:8000
```
Open http://localhost:8000. With XAMPP, put this folder in `htdocs` and open http://localhost/portfolio.

## Features
- Sections: Home, About, Projects (filter + live search + details popup), Skills, Contact.
- Dark / light mode, remembered in the browser.
- Contact form checked in the browser and again on the server, then saved by `contact.php` into `data/messages.txt` (private, not on GitHub).
- Admin login (`auth.php`) to read messages in the Inbox section.
  Demo login from `config.sample.php`: `admin` / `admin123`.
  For a private login, copy it to `config.php` and change the values.

## Events used (js/script.js)
load, click, input, blur, focus, mouseover / mouseout, scroll, keydown, submit (fetch to `contact.php` and `auth.php`).

## Customize
Edit the `$owner`, `$about`, `$skills`, `$filterLabels` and `$projects` arrays at the top of `index.php`.
Put images in `img/`: `profile`, `still-becoming`, `community-reporting`, `pc-build`, `midterm-portfolio` (.jpg, .png or .webp).
A project with an empty `'link'` has no "View project" button.

## Note on privacy
`data/.htaccess` blocks direct access to `data/messages.txt` on Apache / XAMPP.
PHP's built-in server (`php -S`) ignores `.htaccess`, so use it for local testing only.
