<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Tasks') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Add Task Form -->
            <a href="{{ route('tasks.create') }}" class="btn btn-primary mt-3">Create New</a>

            <div class="bg-white shadow sm:rounded-lg p-6">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="task-table">
                        @forelse($tasks as $task)
                            <tr data-id="{{ $task->id }}">
                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input toggle-status" type="checkbox" role="switch"
                                            id="switchCheckChecked" {{ $task->status === 'complete' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="switchCheckChecked">
                                            {{ $task->status === 'complete' ? 'Completed' : 'Incomplete' }}</label>
                                    </div>
                                </td>
                                <td>{{ $task->title }}</td>
                                <td>{{ $task->description }}</td>
               
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('tasks.show', $task->id) }}"  class="btn btn-sm btn-success me-2">Show</a>
                                        <a href="{{ route('tasks.edit', $task->id) }}"  class="btn btn-sm btn-warning edit-task me-2">Edit</a>
                                        <button class="btn btn-sm btn-danger delete-task">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No tasks found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $tasks->links() }}
                </div>
            </div>


        </div>
    </div>

    <!-- Optional: jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>

        // Toggle Completion Status
        $(document).on('change', '.toggle-status', function() {
            let taskId = $(this).closest('tr').data('id');
            $.ajax({
                url: `/tasks/${taskId}/toggle`,
                data: {
                    _token: "{{ csrf_token() }}"
                },
                method: 'PATCH',
                success: function() {
                    location.reload();
                }
            });
        });

        // Delete Task
        $(document).on('click', '.delete-task', function() {
            if (!confirm('Are you sure uu?')) return;
            let taskId = $(this).closest('tr').data('id');

            $.ajax({
                url: `/tasks/${taskId}`,
                method: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function() {
                    location.reload();
                }
            });
        });

    </script>
</x-app-layout>
