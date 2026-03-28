# Changelog

All notable changes to this project will be documented in this file.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [Unreleased]

---

## [2.0.0] — 2026-03-28

### Summary
Full backend migration from Firebase (Firestore + Firebase Auth) to a self-hosted PHP + MySQL stack. This removes all external cloud service dependencies and gives the project team full control over data and authentication.

### Added
- `api/db.php` — PDO database connection, shared JSON response helper, and admin session guard
- `api/auth.php` — Admin login / logout / session check via PHP sessions and bcrypt password verification
- `api/comments.php` — REST endpoint for comment CRUD (public read/submit; admin approve/delete)
- `api/posts.php` — REST endpoint for blog post CRUD (public read; admin create/delete)
- `api/setup.sql` — MySQL schema for `comments` and `posts` tables
- `includes/nav.php` — Shared, parameterised navigation partial (replaces duplicated nav in each page)
- `includes/footer.php` — Shared footer partial
- `js/main.js` — Client-side comment loading and submission logic for `index.php`
- `js/admin.js` — Full admin dashboard JavaScript (auth, tabs, comments, posts)
- `js/blog.js` — Blog post fetching and rendering logic for `blog.php`
- `index.php` — Replaces `index.html`; uses PHP includes and external JS
- `admin.php` — Replaces `admin.html`; password-only login (no email required)
- `blog.php` — Replaces `blog.html`; uses PHP includes and external JS

### Changed
- Admin authentication changed from Firebase email/password to PHP session with bcrypt-hashed password stored in `api/db.php`
- All data operations changed from Firestore SDK calls to `fetch()` calls to the PHP REST API
- JavaScript moved from inline `<script type="module">` blocks to external files in `js/`, placed at end of `<body>` for reliable DOM availability
- Navigation and footer extracted into shared PHP includes to eliminate code duplication
- `.html` file extensions replaced with `.php` throughout

### Removed
- All Firebase SDK dependencies (no CDN imports, no Firebase config in frontend code)
- Firebase anonymous authentication (no longer needed)
- `firestore.rules` — Firestore security rules file (no longer applicable)
- `index.html`, `admin.html`, `blog.html` — Replaced by `.php` equivalents
- Page loader overlay in `index.php` — Was only needed to mask Firebase async auth delay; no longer required

### Fixed
- Page stuck on "Loading Project Data…" spinner — caused by Firebase async auth initialisation delay; eliminated by removing Firebase entirely
- Blog post creation "Missing or insufficient permissions" error — root cause was Firestore security rules mismatch; resolved by migrating off Firebase
- Admin dashboard now correctly restores session state on page reload

---

## [1.1.0] — 2025-12-25

### Summary
Attempted to add blog post management to the admin dashboard using Firestore. Encountered a Firestore permissions error due to security rules not being updated. Firebase console was inaccessible (lost 2FA device), leaving the issue unresolved at the time.

### Added
- Blog / Updates page (`blog.html`) — displays project update posts fetched from Firestore
- Admin dashboard blog tab — create and delete blog posts stored in Firestore
- `firestore.rules` — Updated security rules to allow authenticated writes to the `posts` collection

### Known Issues (at time of release)
- `admin.html` — "Error creating post: Missing or insufficient permissions" when attempting to create a blog post
- Root cause: updated Firestore rules were not deployed due to loss of Firebase console access (2FA)
- Resolved in v2.0.0 by migrating off Firebase

---

## [1.0.0] — 2025-11-01 (estimated)

### Summary
Initial public release of the SDAR Field Rehabilitation Project website.

### Added
- `index.html` — Full project showcase page including:
  - Hero section with drone photography
  - Project overview and objectives
  - Timeline (6 stages, Dec 2024 – Jul 2025)
  - Financial summary with cost breakdown bar chart (total: RM148,534.07)
  - Implementation details for 7 key activities with photos
  - Community involvement gallery (8 images)
  - Challenges and recommendations
  - Public comment section (Firebase anonymous auth + Firestore)
- `admin.html` — Admin dashboard with Firebase email/password login
  - Comment moderation: view all, approve pending, delete
- Firebase Firestore as the database (cloud-hosted NoSQL)
- Firebase Authentication (anonymous for public users; email/password for admin)
- Tailwind CSS via CDN for styling
- Google Fonts (Inter)
- cPanel automated deployment via `.cpanel.yml`

---

[Unreleased]: https://github.com/ariffjamili/sdarfield-web/compare/v2.0.0...HEAD
[2.0.0]: https://github.com/ariffjamili/sdarfield-web/compare/v1.1.0...v2.0.0
[1.1.0]: https://github.com/ariffjamili/sdarfield-web/compare/v1.0.0...v1.1.0
[1.0.0]: https://github.com/ariffjamili/sdarfield-web/releases/tag/v1.0.0
