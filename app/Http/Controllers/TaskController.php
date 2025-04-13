<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
    ) {}

    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        $tasks = $this->taskRepository->index();
        return view('tasks.index', ['tasks' => $tasks]);
    }

    public function create()
    {
        return view('tasks.create');
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;
        $this->taskRepository->create($data);
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified task.
     */
    public function show(string $id)
    {
        $task = $this->taskRepository->find($id);
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified task.
     */
    public function edit(string $id)
    {
        $task = $this->taskRepository->find($id);
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified task in storage.
     */
    public function update(TaskRequest $request, string $id)
    {

        $this->taskRepository->update($id, $request->validated());
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(string $id)
    {

        if ($this->taskRepository->delete($id)) {
            return  response()->json([
                'success' => true,
            ]);
        }

        return  response()->json([
            'success' => false,
        ], 400);
    }

    public function toggleStatus($task)
    {
        $task = $this->taskRepository->toggleStatus($task);
        return response()->json([
            'success' => true,
            'status' => $task->status,
        ]);
    }
}
