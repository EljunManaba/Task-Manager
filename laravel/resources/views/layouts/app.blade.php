<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'TaskFlow')</title>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>

<div class="app">

    <aside class="sidebar">

        <div class="brand">
            <div class="brand-icon">✓</div>

            <div>
                <h2>TaskFlow</h2>
                <span>Personal Manager</span>
            </div>
        </div>

        <nav class="navigation">

            <a href="{{ route('tasks.index') }}"
               class="{{ request()->routeIs('tasks.index') ? 'active' : '' }}">
                <span>▦</span>
                Dashboard
            </a>

            <a href="{{ route('tasks.create') }}"
               class="{{ request()->routeIs('tasks.create') ? 'active' : '' }}">
                <span>＋</span>
                New Task
            </a>

        </nav>

        <div class="sidebar-bottom">
            <p>PERSONAL TASK MANAGER</p>
            <span>Laravel Project</span>
        </div>

    </aside>

    <main class="main-content">

        <header class="topbar">
            <div>
                <span class="small-label">PERSONAL WORKSPACE</span>
                <h1>@yield('page-title', 'Dashboard')</h1>
            </div>

            <a href="{{ route('tasks.create') }}" class="top-button">
                + New Task
            </a>
        </header>

        @if(session('success'))
            <div class="alert success-alert">
                <span>✓</span>
                {{ session('success') }}
            </div>
        @endif

        @yield('content')

    </main>

</div>

</body>
</html>