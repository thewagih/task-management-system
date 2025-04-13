<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $task->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Add Task Form -->

            <div class="bg-white shadow sm:rounded-lg p-6">
                <form action="{{ route('tasks.update', $task->id) }}" method="POST" enctype="multipart/form-data">
                    @method('PUT')
                    @include('tasks.form')
                </form>
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
                    location.reload(); // Refresh to show updated status
                }
            });
        });

        // Delete Task
        $(document).on('click', '.delete-task', function() {
            if (!confirm('Are you sure?')) return;

            let taskId = $(this).closest('tr').data('id');

            $.ajax({
                url: `/tasks/${taskId}`,
                method: 'DELETE',
                data: {
                    _token: "{{ csrf_token() }}"
                },
                success: function() {
                    // location.reload();
                }
            });
        });

    </script>
</x-app-layout>
