@extends('layouts.app')

@section('title', 'Task Details')

@section('content')

    <h2>Task Details</h2>

    <table>
        <tr>
            <th>Task Name</th>
            <td>{{ $task->task_name }}</td>
        </tr>
        <tr>
            <th>Description</th>
            <td>{{ $task->description ?: 'No description provided.' }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td class="{{ $task->status === 'Completed' ? 'status-completed' : 'status-pending' }}">
                {{ $task->status }}
            </td>
        </tr>
        <tr>
            <th>Due Date</th>
            <td>
                {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No due date' }}
                @if ($task->isOverdue())
                    <br><small style="color:red;">Overdue</small>
                @endif
            </td>
        </tr>
        <tr>
            <th>Created</th>
            <td>{{ $task->created_at->format('M d, Y') }}</td>
        </tr>
    </table>

    <br>

    <a href="{{ route('tasks.edit', $task) }}" class="btn-add">Edit</a>
    <a href="{{ route('tasks.index') }}">Back to Task List</a>

    <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this task?');" style="margin-top:10px;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-delete">Delete Task</button>
    </form>

@endsection