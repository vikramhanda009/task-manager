@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')

<h1>Edit Task</h1>

<form method="POST" action="{{ route('tasks.update', $task) }}">
    @csrf
    @method('PUT')

    <input type="text" name="name" value="{{ $task->name }}" required>

    <select name="project_id">
        <option value="">Select Project</option>
        @foreach($projects as $project)
            <option value="{{ $project->id }}"
                {{ $task->project_id == $project->id ? 'selected' : '' }}>
                {{ $project->name }}
            </option>
        @endforeach
    </select>

    <button class="btn btn-primary">Update</button>
</form>

@endsection