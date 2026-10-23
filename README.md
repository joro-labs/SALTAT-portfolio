# Portfolio: John Joros D. Saltat

Event-Driven Programming midterm project. Built with HTML, CSS, JavaScript and PHP.

## Run locally
Requires PHP 7.4+ (or XAMPP).
```
php -S localhost:8000
```
Open http://localhost:8000. With XAMPP, put this folder in `htdocs` and open http://localhost/portfolio.
# Portfolio: John Joros D. Saltat

A personal portfolio website made for my **Event-Driven Programming** midterm project.

## Features

- Project cards with filter buttons, live search and a details popup
- Skills section with hover descriptions
- Contact form with validation (JavaScript and PHP). Messages are saved on the server.
- Admin login. When logged in, the admin can:
  - read and delete contact messages
  - add, edit and delete projects
  - add and remove skills

## Technologies

HTML, CSS, JavaScript, PHP (runs on XAMPP). No database is needed. Data is saved in text and JSON files inside the `data/` folder.

## How to run

1. Install [XAMPP](https://www.apachefriends.org/) and start **Apache**.
2. Copy this folder into `C:\xampp\htdocs\`.
3. Make a copy of `config.sample.php` and name it `config.php`.
4. Open `http://localhost/` followed by the folder name in your browser.

PHP does not run on GitHub Pages, so the site must be opened through XAMPP.

## Admin login

Click **Login** at the top of the page.

- Username: `(see config.sample.php)`
- Password: `(see config.sample.php)`

## Events used

| Event | Where |
| --- | --- |
| `load` | Greeting based on the time of day |
| `mouseover`, `focus` | Skill descriptions |
| `scroll` | Highlights the current section in the menu |
| `click`, `input` | Project filters and search |
| `submit`, `blur` | Contact form and login form |
| `keydown` | `/` focuses the search box, `Esc` closes popups |

## Project structure

```
index.php       main page
auth.php        login and logout
contact.php     saves contact messages
delete.php      deletes a message (admin only)
save.php        saves project and skill changes (admin only)
content.php     default projects and skills
css/style.css   styles
js/script.js    all JavaScript events
data/           saved messages and content (protected by .htaccess)
```
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
