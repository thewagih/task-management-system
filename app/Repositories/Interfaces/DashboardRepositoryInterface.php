<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\BaseRepositoryInterface;

interface DashboardRepositoryInterface extends BaseRepositoryInterface
{
    public function getTasksCount():int;
    public function getCompletedTasksCount():int;
    public function getInCompleteTasksCount():int;

}
