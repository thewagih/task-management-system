<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="d-flex justify-content-center">
                        <div class="card flex-fill m-2">
                            <div class="card-header">
                              Total Tasks
                            </div>
                            <div class="card-body">
                              <p class="card-text">{{ $totalTasks }}</p>
                            </div>
                          </div>

                          <div class="card flex-fill m-2">
                            <div class="card-header">
                              Total Complete Tasks
                            </div>
                            <div class="card-body">
                              <p class="card-text">{{ $totalCompletedTasks }}</p>
                            </div>
                          </div>

                          <div class="card flex-fill m-2">
                            <div class="card-header">
                              Total Incomplete Tasks
                            </div>
                            <div class="card-body">
                              <p class="card-text">{{ $totalIncompleteTasks }}</p>
                            </div>
                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
