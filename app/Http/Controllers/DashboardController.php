<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\DashboardRepositoryInterface;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardRepositoryInterface $dashboardRepository,
    ) {}
    
    
    public function index(){
        
        $totalTasks = $this->dashboardRepository->getTasksCount();

        $totalCompletedTasks = $this->dashboardRepository->getCompletedTasksCount();

        $totalIncompleteTasks = $this->dashboardRepository->getInCompleteTasksCount();

        return view('dashboard', get_defined_vars());
    }
}
