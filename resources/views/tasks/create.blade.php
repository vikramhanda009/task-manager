@extends('layouts.app')

@section('title', 'Create Task')

@section('content')

<h1>Create Task</h1>

<form method="POST" action="{{ route('tasks.store') }}">
    @csrf

    <input type="text" name="name" placeholder="Task name" required>

    <select name="project_id">
        <option value="">Select Project</option>
        @foreach($projects as $project)
            <option value="{{ $project->id }}">{{ $project->name }}</option>
        @endforeach
    </select>

    <button class="btn btn-primary">Save</button>
</form>

@endsection