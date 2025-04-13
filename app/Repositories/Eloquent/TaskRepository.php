<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Interfaces\TaskRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TaskRepository extends BaseRepository implements TaskRepositoryInterface
{
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }

    public function index()
    {
        return $this->paginated(columns: ['id', 'title', 'description', 'status'], perPage: 10, where: ['user_id' => Auth::user()->id]);
    }

    public function toggleStatus(int $id)
    {
        $task = $this->find($id);
        $task->status = $task->status === 'complete' ? 'incomplete' : 'complete';
        $task->save();

        return $task;
    }
}
