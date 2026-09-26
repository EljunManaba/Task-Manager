@extends('layouts.app')

@section('title', 'Dashboard - TaskFlow')
@section('page-title', 'Dashboard')

@section('content')

<div class="welcome-section">
    <div>
        <p class="small-label">YOUR TASKS</p>
        <h2>Stay organized. Get things done.</h2>
        <p class="welcome-text">
            Manage your personal tasks and keep track of your progress.
        </p>
    </div>
</div>

<div class="statistics">

    <div class="stat-card">
        <div class="stat-icon purple">▦</div>

        <div>
            <span>Total Tasks</span>
            <strong>{{ $totalTasks }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon orange">◷</div>

        <div>
            <span>Pending</span>
            <strong>{{ $pendingTasks }}</strong>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon green">✓</div>

        <div>
            <span>Completed</span>
            <strong>{{ $completedTasks }}</strong>
        </div>
    </div>

</div>

<div class="task-header">

    <div>
        <h2>Your Tasks</h2>
        <p>Manage everything you need to accomplish.</p>
    </div>

    <div class="filters">

        <a href="{{ route('tasks.index', ['filter' => 'all']) }}"
           class="{{ $filter === 'all' ? 'selected' : '' }}">
            All
        </a>

        <a href="{{ route('tasks.index', ['filter' => 'pending']) }}"
           class="{{ $filter === 'pending' ? 'selected' : '' }}">
            Pending
        </a>

        <a href="{{ route('tasks.index', ['filter' => 'completed']) }}"
           class="{{ $filter === 'completed' ? 'selected' : '' }}">
            Completed
        </a>

    </div>

</div>

@if($tasks->count() > 0)

    <div class="task-grid">

        @foreach($tasks as $task)

            <div class="task-card {{ $task->status === 'Completed' ? 'completed-card' : '' }}">

                <div class="task-card-top">

                    <span class="status {{ strtolower($task->status) }}">
                        {{ $task->status }}
                    </span>

                    <span class="task-id">
                        #{{ $task->id }}
                    </span>

                </div>

                <h3>{{ $task->task_name }}</h3>

                <p class="task-description">
                    {{ $task->description ?: 'No description provided.' }}
                </p>

                @if($task->due_date)

                    <div class="due-date">
                        <span>◷</span>

                        Due:
                        {{ $task->due_date->format('M d, Y') }}
                    </div>

                @else

                    <div class="due-date no-date">
                        <span>◷</span>
                        No due date
                    </div>

                @endif

                <div class="task-actions">

                    <a href="{{ route('tasks.show', $task) }}" class="view-button">
                        View
                    </a>

                    <a href="{{ route('tasks.edit', $task) }}" class="edit-button">
                        Edit
                    </a>

                    <form action="{{ route('tasks.toggle-status', $task) }}"
                          method="POST">
                        @csrf
                        @method('PATCH')

                        <button type="submit" class="status-button">
                            {{ $task->status === 'Pending' ? 'Complete' : 'Undo' }}
                        </button>
                    </form>

                    <form action="{{ route('tasks.destroy', $task) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this task?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="delete-button">
                            Delete
                        </button>

                    </form>

                </div>

            </div>

        @endforeach

    </div>

@else

    <div class="empty-state">

        <div class="empty-icon">✓</div>

        <h2>No tasks found</h2>

        <p>
            You don't have any tasks in this section yet.
        </p>

        <a href="{{ route('tasks.create') }}" class="primary-button">
            Create Your First Task
        </a>

    </div>

@endif

@endsection