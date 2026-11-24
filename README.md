# LIBÉRICA — Batangas Kapeng Barako Heritage

**A separate Laravel informational and cultural-promotion website. NOT an online shop.**

This project intentionally does not share the original LIBÉRICA shop-themed project's files, database, or Git repository.

## Structure

- `resources/views/landing/index.blade.php` — full public homepage
- `resources/views/encyclopedia/` — public topic index, topic pages, and entries
- `resources/views/layouts/landing.blade.php` — public header/footer
- `resources/views/admin/` — login, dashboard, articles, gallery, references, messages, settings
- `resources/views/layouts/admin.blade.php` — shared admin shell
- `public/css/landing.css` and `public/css/admin.css` — separate styles
- `public/js/landing.js` — menu + interactions
- `app/Http/Controllers/` — public/admin application logic
- `app/Models/` — Article, GalleryItem, Reference, Message, Setting, User
- `database/migrations/` and `database/seeders/` — data structures and non-commercial educational sample content

## Local setup (Windows 11 + Laragon + VS Code)

1. Extract the **entire** `LIBERICA_Barako_Heritage` folder to `C:\laragon\www\`.
2. In VS Code terminal inside the folder, run `composer install`.
3. Copy `.env.example` to `.env` using `Copy-Item .env.example .env`.
4. Create a **new** MySQL database `liberica_heritage_db` (do not reuse the older database).
5. In `.env`, verify database connection, set `ADMIN_EMAIL` and `ADMIN_PASSWORD` (at least 12 characters, unique), and leave `APP_URL=http://127.0.0.1:8001`.
6. Run `php artisan key:generate` only on this **new project**, followed by `php artisan migrate`, `php artisan db:seed`, `php artisan storage:link`.
7. Launch with `php artisan serve --port=8001`.
8. Public page: `http://127.0.0.1:8001`; admin: `http://127.0.0.1:8001/admin/login`.

**Important:** Composer dependencies (`vendor/`) and the `.env` secrets are intentionally excluded. `composer.lock` will be created during your first `composer install`; commit it for reproducible team installations.

## Admin modules

- Dashboard: counts + recent contact messages
- Articles: CRUD and publish/unpublish; plain text content is safely escaped and displayed with paragraph breaks
- Encyclopedia: manage public topics and their entries; deleting a topic also deletes its entries after confirmation
- Gallery: image upload, caption, credit, delete
- References: link, publisher, note, delete
- Messages: view and update status
- Settings: hero messaging, hero photo, contact email

The initial public gallery is deliberately empty. Add real photographs that you have permission to publish and include photographer credits. Two local photos in `public/assets` serve only as starter design imagery.

## Deploy later

Deploy the new project to a **separate Hostinger directory and database**. Point its domain/document root to Laravel `public/`, set `APP_ENV=production`, `APP_DEBUG=false`, HTTPS `APP_URL`, secure session/cookie configuration, set writable `storage`/`bootstrap/cache`, and run production Composer setup/migrations. Do not expose `.env` or project root publicly.

## Scope and editorial quality

This project promotes **Coffea liberica / Batangas Kapeng Barako**, not coffee sales. Article text is an educational starting point and should be reviewed against linked references. Use consented local photography and avoid inventing farm identities, testimonials, protected geographic claims, or tasting guarantees.
