# SDAR Field Rehabilitation Project — Website

A public-facing project showcase website for the SDAR Field Rehabilitation Project (2024–2025), initiated by [SDARA](https://sdara.org.my) and Persatuan Alumni SDAR Lions (PASL).

**Live site:** https://sdara.org.my/sdarfield/

---

## Table of Contents

- [Overview](#overview)
- [Tech Stack](#tech-stack)
- [Project Structure](#project-structure)
- [Local Development](#local-development)
- [Deployment](#deployment)
- [Database Setup](#database-setup)
- [Admin Dashboard](#admin-dashboard)
- [Configuration Reference](#configuration-reference)
- [Known Issues & History](#known-issues--history)
- [Contributing](#contributing)

---

## Overview

The website serves as a project report and community engagement platform. It includes:

- **Home page** (`index.php`) — Project overview, timeline, financials, implementation details, gallery, and a public comment section.
- **Blog / Updates** (`blog.php`) — Project updates posted by the admin.
- **Admin Dashboard** (`admin.php`) — Password-protected panel to moderate comments and publish blog posts.

---

## Tech Stack

| Layer      | Technology                                      |
|------------|-------------------------------------------------|
| Frontend   | HTML5, Tailwind CSS (CDN), Vanilla JavaScript   |
| Backend    | PHP 8+ with PDO                                 |
| Database   | MySQL (hosted via cPanel)                       |
| Fonts      | Google Fonts — Inter                            |
| Hosting    | cPanel shared hosting (`sdara.org.my`)          |
| Deployment | Git push → cPanel Git Version Control (`.cpanel.yml`) |

> **Note:** The site was originally built on Firebase (Firestore + Firebase Auth). It was migrated to PHP + MySQL in v2.0.0 to remove the dependency on a Firebase project that had lost console access. See [CHANGELOG.md](CHANGELOG.md) for details.

---

## Project Structure

```
sdarfield-web/
├── index.php               # Main landing page
├── blog.php                # Project updates / blog
├── admin.php               # Admin dashboard (password protected)
│
├── api/                    # PHP REST API
│   ├── db.php              # Database connection + shared helpers (⚠ contains credentials)
│   ├── auth.php            # Admin login / logout / session check
│   ├── comments.php        # CRUD for comments
│   ├── posts.php           # CRUD for blog posts
│   └── setup.sql           # MySQL schema — run once to create tables
│
├── includes/               # Shared PHP partials
│   ├── nav.php             # Site navigation (parameterised)
│   └── footer.php          # Site footer
│
├── js/                     # Client-side JavaScript (one file per page)
│   ├── main.js             # Comment loading & submission (index.php)
│   ├── admin.js            # Admin dashboard logic
│   └── blog.js             # Blog posts rendering
│
├── images/                 # Static image assets
├── .cpanel.yml             # cPanel auto-deployment config
└── CHANGELOG.md
```

---

## Local Development

### Prerequisites

- PHP 8.0+
- MySQL 5.7+ or MariaDB 10.3+
- A local web server (e.g. XAMPP, Laravel Herd, or PHP's built-in server)

### Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/ariffjamili/sdarfield-web.git
   cd sdarfield-web
   ```

2. **Create a MySQL database**
   ```sql
   CREATE DATABASE sdarfield CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. **Run the schema**
   Import `api/setup.sql` via phpMyAdmin or the CLI:
   ```bash
   mysql -u root -p sdarfield < api/setup.sql
   ```

4. **Configure credentials** in `api/db.php`:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'sdarfield');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

5. **Set the admin password hash**
   Generate a bcrypt hash and paste it into `api/db.php`:
   ```bash
   php -r "echo password_hash('your-password', PASSWORD_DEFAULT);"
   ```
   ```php
   define('ADMIN_PASSWORD_HASH', '$2y$12$...');
   ```

6. **Start a local server**
   ```bash
   php -S localhost:8080
   ```
   Then open `http://localhost:8080/index.php`.

---

## Deployment

Deployment is automated via **cPanel Git Version Control**. Pushing to the `main` branch triggers `.cpanel.yml`, which copies all files to:

```
/home/sdara/public_html/sdarfield/
```

> **Before the first deployment**, complete the [Database Setup](#database-setup) steps on the production server.

### Manual deployment (fallback)

Upload all files via cPanel File Manager or FTP to `/home/sdara/public_html/sdarfield/`.

---

## Database Setup (Production)

1. Log into cPanel → **MySQL Databases**
2. Create a database and user (e.g. `sdara_sdarfield`)
3. Grant the user full privileges on the database
4. Open **phpMyAdmin**, select the database, go to the **SQL** tab
5. Paste and run the contents of `api/setup.sql`
6. Update `api/db.php` with the production credentials

---

## Admin Dashboard

Access the admin panel at `/admin.php`.

| Feature             | Description                                        |
|---------------------|----------------------------------------------------|
| Login               | Password only (no email). Session-based via PHP.   |
| Comments tab        | View all comments; approve pending or delete any.  |
| Updates (Blog) tab  | Create new project updates; delete existing posts. |

### Setting / changing the admin password

Generate a new hash and update `ADMIN_PASSWORD_HASH` in `api/db.php`:

```bash
php -r "echo password_hash('new-password', PASSWORD_DEFAULT);"
```

> The password is **never stored in plain text**. Only the bcrypt hash is stored in the source file.

---

## Configuration Reference

All runtime configuration lives in **`api/db.php`**.

| Constant              | Description                               |
|-----------------------|-------------------------------------------|
| `DB_HOST`             | MySQL host (usually `localhost`)          |
| `DB_NAME`             | Database name                             |
| `DB_USER`             | Database username                         |
| `DB_PASS`             | Database password                         |
| `ADMIN_PASSWORD_HASH` | bcrypt hash of the admin dashboard password |

> ⚠ `api/db.php` contains credentials. It is tracked in Git for deployment convenience since this is a private repository. If the repository is ever made public, move credentials to a `.env` file and add `api/db.php` to `.gitignore`.

---

## API Endpoints

All endpoints are in `api/` and communicate via JSON.

### `api/auth.php`
| Method | Body / Params              | Auth     | Description              |
|--------|----------------------------|----------|--------------------------|
| GET    | —                          | —        | Check if session is active |
| POST   | `{ password }`             | —        | Login                    |
| POST   | `{ action: "logout" }`     | Session  | Logout                   |

### `api/comments.php`
| Method | Body / Params              | Auth     | Description              |
|--------|----------------------------|----------|--------------------------|
| GET    | —                          | —        | Get approved comments    |
| GET    | `?all=1`                   | Session  | Get all comments (admin) |
| POST   | `{ name, comment }`        | —        | Submit new comment       |
| PATCH  | `{ id }`                   | Session  | Approve a comment        |
| DELETE | `{ id }`                   | Session  | Delete a comment         |

### `api/posts.php`
| Method | Body / Params                        | Auth    | Description         |
|--------|--------------------------------------|---------|---------------------|
| GET    | —                                    | —       | Get all posts       |
| POST   | `{ title, content, imageUrl }`       | Session | Create a post       |
| DELETE | `{ id }`                             | Session | Delete a post       |

---

## Known Issues & History

- The original Firebase-based version (v1.x) had a Firestore permissions error introduced when blog post management was added. The Firebase project's console was also inaccessible due to a lost 2FA device.
- v2.0.0 resolved this by migrating the entire backend to PHP + MySQL, removing all Firebase dependencies.
- See [CHANGELOG.md](CHANGELOG.md) for the full version history.

---

## Contributing

1. Fork or clone the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Make changes and test locally
4. Commit with a descriptive message following [Conventional Commits](https://www.conventionalcommits.org/):
   - `feat:` new feature
   - `fix:` bug fix
   - `docs:` documentation only
   - `refactor:` code change without new feature or bug fix
5. Push and open a Pull Request against `main`
6. Update [CHANGELOG.md](CHANGELOG.md) under `[Unreleased]` before merging

---

*Maintained by SDARA Web Team. For questions, contact secretariat@sdara.org.*
