@extends('layouts.app')

@section('title', 'Tasks')

@section('content')

<h1>Task Manager</h1>

<div class="top-bar">
    <div>
        <form method="GET" action="{{ route('tasks.index') }}">
            <select name="project_id" onchange="this.form.submit()">
                <option value="">All Projects</option>
                @foreach($projects as $project)
                    <option value="{{ $project->id }}"
                        {{ $selectedProjectId == $project->id ? 'selected' : '' }}>
                        {{ $project->name }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    <a href="{{ route('tasks.create') }}" class="btn btn-primary">+ Add Task</a>
</div>

<div id="task-list">
    @foreach($tasks as $task)
        <div class="task task-item" data-id="{{ $task->id }}">
            <span>#{{ $task->priority }} - {{ $task->name }}</span>

            <div>
                <a href="{{ route('tasks.edit', $task) }}" class="btn btn-edit">Edit</a>

                <form action="{{ route('tasks.destroy', $task) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    @endforeach
</div>

@if($tasks->isEmpty())
    <p>No tasks found.</p>
@endif

@endsection

@push('scripts')
<script src="/js/tasks/reorder.js"></script>
@endpush