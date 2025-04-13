<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Interfaces\DashboardRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class DashboardRepository extends BaseRepository implements DashboardRepositoryInterface
{
    public function __construct(Task $model)
    {
        parent::__construct($model);
    }


    public function getTasksCount(): int
    {
        return $this->model->where(['user_id' => Auth::user()->id])->count();
    }

    public function getCompletedTasksCount(): int
    {
        return $this->model->where(['user_id' => Auth::user()->id, 'status' => 'complete'])->count();
    }

    public function getInCompleteTasksCount(): int
    {
        return $this->model->where(['user_id' => Auth::user()->id, 'status' => 'incomplete'])->count();
    }
}
