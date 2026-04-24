# Task Manager

A simple Laravel 11 task management application with drag-and-drop priority reordering and optional project grouping.

## Features

- **Create tasks** — Add a task with a name and optionally assign it to a project
- **Edit tasks** — Rename a task and/or reassign it to a different project inline
- **Delete tasks** — Remove a task; remaining priorities automatically compact
- **Reorder by drag & drop** — Drag rows to re-prioritise; #1 = highest priority (top). Changes persist instantly via a background JSON request
- **Projects** — Create projects and filter the task list to a single project. Delete a project (and all its tasks) from the filter tab
- Timestamps (`created_at`, `updated_at`) are stored for every task and project

## Technology Stack

| Layer | Choice |
|-------|--------|
| Language | PHP 8.3 |
| Framework | Laravel 11 |
| Database | MySQL 8+ |
| Frontend | Blade + Tailwind CSS 3 (JIT) + SortableJS |
| Build tool | Vite 5 |

## Architecture Notes

- **MVC + Form Requests** — Controllers are thin; all validation lives in dedicated `FormRequest` classes (`StoreTaskRequest`, `UpdateTaskRequest`, `StoreProjectRequest`)
- **Eloquent Scopes** — `Task::scopeForProject()` and `Task::scopeOrderedByPriority()` keep query logic close to the model
- **Route model binding** — `{task}` and `{project}` parameters are resolved automatically by Laravel
- **Route ordering** — `/tasks/reorder` is registered *before* `/tasks/{task}` to prevent Laravel treating `"reorder"` as a Task ID
- **Cascade deletes** — The `tasks.project_id` foreign key uses `cascadeOnDelete()`, so removing a project removes its tasks at the database level
- **Optimistic UI** — Priority badge numbers are updated immediately on drag-end; the server is updated asynchronously. On failure the page reloads to show the true state

## Requirements

- PHP ≥ 8.3 with extensions: `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`
- Composer ≥ 2
- Node.js ≥ 20 + npm
- MySQL ≥ 8.0 (or MariaDB ≥ 10.6)

## Setup & Installation

### 1. Clone / unzip the project

```bash
unzip task-manager.zip -d task-manager
cd task-manager
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install Node dependencies and build assets

```bash
npm install
npm run build
```

> During development use `npm run dev` to start the Vite hot-reload server.

### 4. Configure the environment

```bash
cp .env.example .env
php artisan key:generate
```

Open `.env` and update the database block:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
```

### 5. Create the database

```sql
CREATE DATABASE task_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Run migrations

```bash
php artisan migrate
```

### 7. (Optional) Seed sample data

```bash
php artisan db:seed
```

This creates three sample projects (*Website Redesign*, *Mobile App*, *Marketing Campaign*) with seven starter tasks.

### 8. Start the development server

```bash
php artisan serve
```

Visit **http://localhost:8000** in your browser.

## Running Unit Tests

The test suite uses an in-memory SQLite database so no MySQL connection is needed.

```bash
php artisan test
```

Or run a specific test class:

```bash
php artisan test --filter TaskTest
php artisan test --filter ProjectTest
```

To see detailed output:

```bash
php artisan test --verbose
```

## Deployment (Production)

1. Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`
2. Run `composer install --no-dev --optimize-autoloader`
3. Run `npm run build` to compile production assets
4. Run `php artisan config:cache && php artisan route:cache && php artisan view:cache`
5. Point your web server document root to the `public/` directory
6. Ensure `storage/` and `bootstrap/cache/` are writable by the web server user

### nginx example

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/task-manager/public;

    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── TaskController.php       # index, store, update, destroy, reorder
│   │   └── ProjectController.php    # store, destroy
│   └── Requests/
│       ├── StoreTaskRequest.php
│       ├── UpdateTaskRequest.php
│       └── StoreProjectRequest.php
└── Models/
    ├── Task.php                     # scopes: forProject, orderedByPriority
    └── Project.php

database/
├── migrations/
│   ├── ..._create_projects_table.php
│   └── ..._create_tasks_table.php
├── factories/
│   ├── TaskFactory.php
│   └── ProjectFactory.php
└── seeders/
    └── DatabaseSeeder.php

resources/views/
├── layouts/app.blade.php
└── tasks/index.blade.php

routes/web.php

tests/Feature/
├── TaskTest.php
└── ProjectTest.php
```
# task-manager
# task-manager
