@extends('layouts.app')

@section('title', 'View Task - TaskFlow')
@section('page-title', 'Task Details')

@section('content')

<div class="detail-container">

    <div class="detail-top">

        <div>

            <span class="status {{ strtolower($task->status) }}">
                {{ $task->status }}
            </span>

            <p class="task-number">
                TASK #{{ $task->id }}
            </p>

            <h2>{{ $task->task_name }}</h2>

        </div>

        <a href="{{ route('tasks.edit', $task) }}" class="edit-large">
            Edit Task
        </a>

    </div>

    <div class="detail-section">

        <span class="detail-label">
            DESCRIPTION
        </span>

        <p class="detail-description">
            {{ $task->description ?: 'No description provided.' }}
        </p>

    </div>

    <div class="detail-information">

        <div>
            <span class="detail-label">STATUS</span>

            <strong>
                {{ $task->status }}
            </strong>
        </div>

        <div>
            <span class="detail-label">DUE DATE</span>

            <strong>
                {{ $task->due_date ? $task->due_date->format('F d, Y') : 'No due date' }}
            </strong>
        </div>

        <div>
            <span class="detail-label">CREATED</span>

            <strong>
                {{ $task->created_at->format('F d, Y') }}
            </strong>
        </div>

    </div>

    <div class="detail-bottom">

        <a href="{{ route('tasks.index') }}" class="cancel-button">
            ← Back to Tasks
        </a>

        <form action="{{ route('tasks.destroy', $task) }}"
              method="POST"
              onsubmit="return confirm('Are you sure you want to delete this task?');">

            @csrf
            @method('DELETE')

            <button type="submit" class="delete-large">
                Delete Task
            </button>

        </form>

    </div>

</div>

@endsection