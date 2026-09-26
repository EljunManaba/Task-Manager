@extends('layouts.app')

@section('title', 'Edit Task - TaskFlow')
@section('page-title', 'Edit Task')

@section('content')

<div class="form-container">

    <div class="form-heading">
        <p class="small-label">EDIT TASK #{{ $task->id }}</p>

        <h2>Update your task</h2>

        <p>
            Change the information below and save your updates.
        </p>
    </div>

    <form action="{{ route('tasks.update', $task) }}" method="POST">

        @csrf
        @method('PUT')

        <div class="form-group">

            <label for="task_name">
                Task Name
            </label>

            <input
                type="text"
                id="task_name"
                name="task_name"
                value="{{ old('task_name', $task->task_name) }}"
            >

            @error('task_name')
                <small class="error">{{ $message }}</small>
            @enderror

        </div>

        <div class="form-group">

            <label for="description">
                Description
            </label>

            <textarea
                id="description"
                name="description"
                rows="5"
            >{{ old('description', $task->description) }}</textarea>

        </div>

        <div class="form-row">

            <div class="form-group">

                <label for="status">
                    Status
                </label>

                <select id="status" name="status">

                    <option value="Pending"
                        {{ old('status', $task->status) === 'Pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="Completed"
                        {{ old('status', $task->status) === 'Completed' ? 'selected' : '' }}>
                        Completed
                    </option>

                </select>

            </div>

            <div class="form-group">

                <label for="due_date">
                    Due Date
                </label>

                <input
                    type="date"
                    id="due_date"
                    name="due_date"
                    value="{{ old('due_date', optional($task->due_date)->format('Y-m-d')) }}"
                >

            </div>

        </div>

        <div class="form-buttons">

            <a href="{{ route('tasks.index') }}" class="cancel-button">
                Cancel
            </a>

            <button type="submit" class="primary-button">
                Save Changes
            </button>

        </div>

    </form>

</div>

@endsection