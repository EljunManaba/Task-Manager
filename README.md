# Personal Task Manager (TaskFlow)

**Project Code:** WST21-PM-2026-SF
**Student Name:** Eljun Nish Manaba L.
**Course & Year:** BSIT 2 - SECTION 1
**Database Used:** SQLite (default) — also works with MySQL / PostgreSQL by changing `.env`

A simple Laravel CRUD web app for managing personal tasks, built by following the classroom flow:
**Routes → Controller → Model → Database → Blade**

Branded internally as **TaskFlow** — "Stay organized. Get things done."

## Features

- Add Task
- View Tasks
- Edit Task
- Delete Task
- Update Status

### Extra features I added on top of the requirements
- Dashboard-style layout with a sidebar and a stats overview (Total / Pending / Completed)
- Filter tasks by status (All / Pending / Completed)
- Search tasks by name
- Automatic "Overdue" badge for pending tasks whose due date has passed
- One-click "Mark Done / Mark Pending" status toggle (no separate edit page needed)
- A dedicated task "show" / detail page
- Server-side form validation with inline error messages
- Custom stylesheet (`public/css/style.css`) instead of a CSS framework
- Database seeder that inserts sample tasks for quick demoing

---

## How I Built This

I followed the order suggested in the assignment: **Database → Model → Controller → Routes → Blade → CRUD.** Below is a walkthrough of each step and *why* it was done that way.

### 1. Project setup

I started with a fresh Laravel installation:

```bash
composer create-project laravel/laravel .
cd laravel
```

I used **SQLite** for the database because it needs zero setup — no separate database server, just a single file:

```env
DB_CONNECTION=sqlite
```

```bash
touch database/database.sqlite
```

The app still fully supports MySQL — swap the `.env` connection block and create the database if preferred.

### 2. Database — the migration

I generated a migration for the `tasks` table:

```bash
php artisan make:migration create_tasks_table
```

```php
Schema::create('tasks', function (Blueprint $table) {
    $table->id();
    $table->string('task_name');
    $table->text('description')->nullable();
    $table->enum('status', ['Pending', 'Completed'])->default('Pending');
    $table->date('due_date')->nullable();
    $table->timestamps();
});
```

**Design decisions:**
- `status` is an `enum` restricted to `Pending`/`Completed` at the database level, so invalid statuses can't be saved even if validation is bypassed.
- `description` and `due_date` are `nullable` since not every task needs a deadline or extra notes.
- `status` defaults to `Pending` so a new task doesn't need the field explicitly set.

```bash
php artisan migrate
```

### 3. Model — `Task.php`

```bash
php artisan make:model Task
```

Key parts of the model:
- **`$fillable`** — whitelists which fields can be mass-assigned via `Task::create($data)`, protecting against mass-assignment vulnerabilities.
- **`$casts`** — casts `due_date` to a Carbon date object, so I can call `$task->due_date->format('M d, Y')` or `$task->due_date->isPast()` directly in Blade.
- **Query scopes** (`scopePending`, `scopeCompleted`) — for clean, readable queries like `Task::pending()->count()`.
- **`isOverdue()`** — checks if a task is still `Pending` *and* its due date has passed, powering the "Overdue" badge and the dashboard stats.

### 4. Controller — `TaskController.php`

```bash
php artisan make:controller TaskController
```

A standard **resource controller** (`index`, `create`, `store`, `show`, `edit`, `update`, `destroy`) maps directly onto the required features:

| Method | Feature it implements |
|---|---|
| `index()` | View Tasks (dashboard/list) |
| `show()` | Task detail page |
| `create()` + `store()` | Add Task |
| `edit()` + `update()` | Edit Task |
| `destroy()` | Delete Task |
| `updateStatus()` *(custom, extra method)* | Update Status |

Status updates got their **own method** (`updateStatus`) so a task can be flipped between Pending/Completed with one click, without opening the full edit form.

Validation is done directly in the controller with `$request->validate([...])`, since the project is small enough that a dedicated Form Request class would be overkill:

```php
$request->validate([
    'task_name' => ['required', 'string', 'max:255'],
    'description' => ['nullable', 'string'],
    'status' => ['required', 'in:Pending,Completed'],
    'due_date' => ['nullable', 'date'],
]);
```

`index()` also accepts optional `status` and `search` query parameters, powering the filter buttons and search box.

### 5. Routes — `routes/web.php`

I used **route resource binding** to generate all the standard CRUD routes (and their names, like `tasks.index`, `tasks.store`) in one line:

```php
Route::resource('tasks', TaskController::class);
```

Plus one extra route for the status toggle, since that's not part of the standard resource set:

```php
Route::patch('/tasks/{task}/status', [TaskController::class, 'updateStatus'])
    ->name('tasks.status');
```

And a redirect from the root URL straight to the dashboard:

```php
Route::redirect('/', '/tasks');
```

Because the routes use `{task}` and the controller methods type-hint `Task $task`, Laravel's **route model binding** automatically fetches the right record (or throws a 404) — no manual `Task::findOrFail($id)` needed.

### 6. Blade Views

The layout (`resources/views/layouts/app.blade.php`) contains the shared `<head>`, a sidebar navigation, and a single `@yield('content')` slot. Every page extends it with `@extends('layouts.app')`, so the sidebar and styling stay consistent without repeating markup.

- **`tasks/index.blade.php`** — the dashboard: stats (Total/Pending/Completed), filter links, search form, and the task list with inline forms for status-toggle/edit/delete.
- **`tasks/create.blade.php`** — the "Add Task" form.
- **`tasks/edit.blade.php`** — the same form pre-filled with the task's data via `old('field', $task->field)`.
- **`tasks/show.blade.php`** — a read-only detail view for a single task.

Every form includes:
- `@csrf` — required on every POST/PUT/PATCH/DELETE form or the request is rejected.
- `@method('PUT')` / `@method('DELETE')` — HTML forms only support GET/POST natively, so Laravel "spoofs" the other verbs via a hidden `_method` field.
- `@error('field')` blocks — inline validation error messages.
- `old('field')` — repopulates the form if validation fails, so nothing is lost.

Instead of a CSS framework, I wrote a dedicated stylesheet at `public/css/style.css` and linked it from the layout:

```html
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
```

Using `asset()` instead of a hardcoded path means the URL is generated correctly regardless of what subfolder or domain the app is served from.

### 7. Testing the CRUD flow

Once everything was wired up, I tested manually in this order:
1. Visit `/tasks` — confirms routes + `index()` + Blade rendering work.
2. Add a task — confirms `store()`, validation, and the redirect-with-flash-message pattern.
3. View a task — confirms `show()` and route model binding.
4. Edit a task — confirms pre-filled forms and `update()`.
5. Toggle status — confirms the dedicated `PATCH` route works independently of full edit.
6. Delete a task — confirms the `DELETE` form + confirmation dialog.
7. Filter/search — confirms query-string based filtering in `index()`.

### 8. Deployment / running environment quirks (GitHub Codespaces)

I developed this inside a **GitHub Codespace**, which surfaced two related issues worth documenting because they're easy to hit again in any proxied dev environment (not just this one):

**Issue A — broken links from `route()`/`url()`.**
Laravel normally builds links from the incoming request's `Host` header. Inside Codespaces, the PHP dev server sees every request as `localhost:8000`, even though the browser is actually going through the public forwarded domain. Every generated link was getting built with `localhost:8000` baked in, which then broke once the Codespaces proxy tried to rewrite it into the public URL.

**Issue B — the stylesheet silently not loading.**
The same root cause hit `asset('css/style.css')`: it generated a URL for the wrong origin, so the browser's request for the CSS file never even showed up in the server logs — it wasn't a 404, it just never got requested from the right place.

**The fix**, in `AppServiceProvider::boot()`, forces Laravel to always build URLs (routes *and* assets) from `APP_URL` in `.env`, instead of trusting the request's Host header:

```php
use Illuminate\Support\Facades\URL;

public function boot(): void
{
    if (config('app.url')) {
        URL::forceRootUrl(config('app.url'));
        URL::forceScheme('https');
    }
}
```

With this in place, `APP_URL` in `.env` must be kept in sync with whatever Codespace/forwarded domain is currently active:

```env
APP_URL=https://special-dollop-qv466xv7v5qphxppx-8000.app.github.dev
```

After changing `.env`, both `php artisan config:clear` and a full restart of `php artisan serve` are required, since environment variables are only read once when the server process starts.

---

## Tech Stack

- Laravel 11 (PHP 8.2+)
- Blade templating engine
- Eloquent ORM
- Custom CSS (`public/css/style.css`) — no CSS framework
- SQLite (default) — MySQL/PostgreSQL supported via `.env`

## Project Structure (key files)

```
app/Models/Task.php                              → Task model, scopes, isOverdue()
app/Http/Controllers/TaskController.php          → CRUD + status update logic
app/Providers/AppServiceProvider.php             → forces correct URL/asset generation
database/migrations/..._create_tasks_table.php   → tasks table schema
database/factories/TaskFactory.php               → fake data generator
database/seeders/DatabaseSeeder.php              → sample task data
routes/web.php                                   → all task routes
public/css/style.css                             → all styling
resources/views/layouts/app.blade.php            → shared layout + sidebar
resources/views/tasks/index.blade.php            → dashboard / task list
resources/views/tasks/create.blade.php           → add task form
resources/views/tasks/edit.blade.php             → edit task form
resources/views/tasks/show.blade.php             → task detail view
```

### Database schema (`tasks` table)

| Field | Type | Notes |
|---|---|---|
| id | bigint, auto-increment | Primary key |
| task_name | string | Required |
| description | text, nullable | Optional details |
| status | enum('Pending','Completed') | Defaults to `Pending` |
| due_date | date, nullable | Optional deadline |
| created_at / updated_at | timestamps | Managed automatically by Laravel |

## Routes Overview

| Method | URI | Action | Name |
|---|---|---|---|
| GET | /tasks | List all tasks (dashboard) | tasks.index |
| GET | /tasks/create | Show add-task form | tasks.create |
| POST | /tasks | Store a new task | tasks.store |
| GET | /tasks/{task} | View a single task | tasks.show |
| GET | /tasks/{task}/edit | Show edit-task form | tasks.edit |
| PUT | /tasks/{task} | Update a task | tasks.update |
| DELETE | /tasks/{task} | Delete a task | tasks.destroy |
| PATCH | /tasks/{task}/status | Update only the status | tasks.status |

---

## Setup Instructions

### 1. Clone the repository
```bash
git clone <your-repo-url>
cd laravel
```

### 2. Install PHP dependencies
```bash
composer install
```

### 3. Set up the environment file
```bash
cp .env.example .env
php artisan key:generate
```

Set `APP_URL` in `.env` to match wherever you're running the app (e.g. `http://localhost:8000`, or your Codespace's current forwarded domain — see the troubleshooting note above if using Codespaces).

### 4. Configure the database

**Option A — SQLite (default, no server needed):**
```bash
touch database/database.sqlite
```

**Option B — MySQL:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```
```sql
CREATE DATABASE task_manager;
```

### 5. Run migrations (and optional sample data)
```bash
php artisan migrate
php artisan db:seed   # optional: adds sample tasks
```

### 6. Start the development server
```bash
php artisan serve
```

Visit **http://127.0.0.1:8000** (or your forwarded Codespace URL) — it redirects straight to the dashboard. (or by simply pressing ctrl+click at the same time)

---

## Notes

- `vendor/` and `.env` are intentionally excluded from the repository (standard Laravel practice) — run `composer install` and copy `.env.example` after cloning.
- If styling or links break after moving to a new environment (e.g. a fresh Codespace), double-check `APP_URL` in `.env` and that `AppServiceProvider::boot()` still contains the `forceRootUrl` fix described above.
- Built as part of the Laravel Mini Project assignment to practice the Routes → Controller → Model → Database → Blade flow.