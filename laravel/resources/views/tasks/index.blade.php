@extends('layouts.app')

@section('title', 'All Tasks')

@section('content')

    @if (session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    <h2>Task List</h2>

    <p>Total Tasks: {{ $tasks->count() }}</p>

    <div class="top-bar">
        <div class="filter-links">
            <a href="{{ route('tasks.index') }}">All</a>
            <a href="{{ route('tasks.index', ['status' => 'Pending']) }}">Pending</a>
            <a href="{{ route('tasks.index', ['status' => 'Completed']) }}">Completed</a>
        </div>

        <form method="GET" action="{{ route('tasks.index') }}">
            <input type="text" name="search" placeholder="Search task..." value="{{ request('search') }}">
            <button type="submit" class="btn">Search</button>
        </form>

        <a href="{{ route('tasks.create') }}" class="btn-add">+ Add Task</a>
    </div>

    @if ($tasks->isEmpty())
        <p>No tasks found.</p>
    @else
        <table>
            <tr>
                <th>Task Name</th>
                <th>Description</th>
                <th>Due Date</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            @foreach ($tasks as $task)
                <tr>
                    <td>{{ $task->task_name }}</td>
                    <td>{{ $task->description ?: '-' }}</td>
                    <td>
                        {{ $task->due_date ? $task->due_date->format('M d, Y') : '-' }}
                        @if ($task->isOverdue())
                            <br><small style="color:red;">Overdue</small>
                        @endif
                    </td>
                    <td class="{{ $task->status === 'Completed' ? 'status-completed' : 'status-pending' }}">
                        {{ $task->status }}
                    </td>
                    <td>
                        <form class="inline" method="POST" action="{{ route('tasks.toggle-status', $task) }}">
    @csrf
    @method('PATCH')
    <button type="submit" class="btn">
        {{ $task->status === 'Completed' ? 'Mark Pending' : 'Mark Done' }}
    </button>
</form>
                        <a href="{{ route('tasks.edit', $task) }}" class="btn">Edit</a>
                        <form class="inline" method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-delete">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
@endsection