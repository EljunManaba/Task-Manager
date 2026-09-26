@extends('layouts.app')

@section('title', 'New Task - TaskFlow')
@section('page-title', 'New Task')

@section('content')

<div class="form-container">

    <div class="form-heading">
        <p class="small-label">CREATE TASK</p>

        <h2>What needs to be done?</h2>

        <p>
            Add a new task to your personal workspace.
        </p>
    </div>

    <form action="{{ route('tasks.store') }}" method="POST">

        @csrf

        <div class="form-group">

            <label for="task_name">
                Task Name
            </label>

            <input
                type="text"
                id="task_name"
                name="task_name"
                value="{{ old('task_name') }}"
                placeholder="Example: Finish Laravel project"
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
                placeholder="Add some details about this task..."
            >{{ old('description') }}</textarea>

            @error('description')
                <small class="error">{{ $message }}</small>
            @enderror

        </div>

        <div class="form-row">

            <div class="form-group">

                <label for="status">
                    Status
                </label>

                <select id="status" name="status">

                    <option value="Pending"
                        {{ old('status', 'Pending') === 'Pending' ? 'selected' : '' }}>
                        Pending
                    </option>

                    <option value="Completed"
                        {{ old('status') === 'Completed' ? 'selected' : '' }}>
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
                    value="{{ old('due_date') }}"
                >

                @error('due_date')
                    <small class="error">{{ $message }}</small>
                @enderror

            </div>

        </div>

        <div class="form-buttons">

            <a href="{{ route('tasks.index') }}" class="cancel-button">
                Cancel
            </a>

            <button type="submit" class="primary-button">
                Create Task
            </button>

        </div>

    </form>

</div>

@endsection