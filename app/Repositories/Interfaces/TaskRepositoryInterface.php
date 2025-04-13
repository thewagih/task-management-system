<?php

namespace App\Repositories\Interfaces;

use App\Repositories\Interfaces\BaseRepositoryInterface;

interface TaskRepositoryInterface extends BaseRepositoryInterface
{
    public function index();

    public function toggleStatus(int $id);

}
