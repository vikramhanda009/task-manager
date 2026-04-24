<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller; // ✅ THIS LINE IS REQUIRED
use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $selectedProjectId = $request->project_id;

        $tasks = Task::when($selectedProjectId, fn($q) =>
                $q->where('project_id', $selectedProjectId)
            )
            ->orderBy('priority')
            ->get();

        $projects = Project::all();

        return view('tasks.index', compact(
            'tasks',
            'projects',
            'selectedProjectId' // ✅ ADD THIS
        ));
    }

    public function create()
    {
        $projects = Project::all();
        return view('tasks.create', compact('projects'));
    }

    public function store(StoreTaskRequest $request)
    {
        $maxPriority = Task::max('priority') ?? 0;

        Task::create([
            ...$request->validated(),
            'priority' => $maxPriority + 1
        ]);

        return redirect()->route('tasks.index');
    }

    public function edit(Task $task)
    {
        $projects = Project::all();
        return view('tasks.edit', compact('task', 'projects'));
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $task->update($request->validated());

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return back();
    }

    public function reorder(Request $request)
    {
        foreach ($request->all() as $item) {
            Task::where('id', $item['id'])
                ->update(['priority' => $item['priority']]);
        }

        return response()->json(['status' => 'success']);
    }
}