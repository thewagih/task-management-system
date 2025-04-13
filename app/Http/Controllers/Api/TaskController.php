<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskResource;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use App\Traits\ApiResponserTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{

    use ApiResponserTrait;

    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
    ) {}

    /**
     * Display a listing of the tasks.
     */
    public function index()
    {
        $tasks = $this->taskRepository->index();
        return $this->successResponse(
            data: TaskResource::collection($tasks)
        );
    }

    /**
     * Store a newly created task in storage.
     */
    public function store(TaskRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::user()->id;

        $task = $this->taskRepository->create($data);

        return $this->successResponse(
            data: new TaskResource($task)
        );
    }

    /**
     * Display the specified task.
     */
    public function show(string $id)
    {

        $task = $this->taskRepository->find($id);
        if (!$task) {
            return  $this->errorResponse([], 'Task not found', [], 404);
        }
        return $this->successResponse(
            data: new TaskResource($task)
        );
    }

    /**
     * Update the specified task in storage.
     */
    public function update(TaskRequest $request, string $id)
    {

        $task = $this->taskRepository->update($id, $request->validated());
        return $this->successResponse(
            data: new TaskResource($task)
        );
    }

    /**
     * Remove the specified task from storage.
     */
    public function destroy(string $id)
    {

        if($this->taskRepository->delete($id)){

            return $this->successResponse(
                data: 'Deleted Successfully'
            );
        }
        return  $this->errorResponse([] , 'Task not found', [], 404);
    }

    public function toggleStatus($task)
    {
        $task = $this->taskRepository->toggleStatus($task);
        return $this->successResponse(
            data: new TaskResource($task)
        );
    }
}
