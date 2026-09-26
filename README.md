# Personal Task Manager

Project Code: WST21-PM-2026-SF
Student Name: Eljun Nish Manaba L.
Course & Year: BSIT 2 - Section 1
Database Used: SQLite

This is a simple task manager made using Laravel for our mini project. It lets you add, view, edit, delete, and update the status of your tasks.

## Features

- Add Task
- View Tasks
- Edit Task
- Delete Task
- Update Status (Pending / Completed)

I also added a search bar and filter buttons (All / Pending / Completed) on the task list, and a page that shows the full details of one task.

## How it works

The project follows the Routes → Controller → Model → Database → Blade flow that we learned in class.

- `database/migrations` - has the migration for the `tasks` table (task_name, description, status, due_date)
- `app/Models/Task.php` - the Task model
- `app/Http/Controllers/TaskController.php` - handles the CRUD logic
- `routes/web.php` - all the routes for the tasks
- `resources/views/tasks` - the blade pages (index, create, edit, show)
- `resources/views/layouts/app.blade.php` - the layout used by all pages, includes the CSS

The status toggle button on the task list uses its own route (`tasks.toggle-status`) so you can mark a task as done without going to the edit page.

## Setup

1. Clone the repo
```
git clone <repo-url>
cd laravel
```

2. Install dependencies
```
composer install
```

3. Copy the env file and generate the key
```
cp .env.example .env
php artisan key:generate
```

4. Create the sqlite database
```
touch database/database.sqlite
```

5. Run the migrations
```
php artisan migrate
```

6. Run the server
```
php artisan serve
```

Then open the link it gives you, it will redirect straight to the tasks page.

## Notes

- I used SQLite so I didn't have to set up a separate database server.
- If you're running this in GitHub Codespaces, make sure `APP_URL` in `.env` matches the forwarded URL of your codespace, or the links and CSS won't load properly.