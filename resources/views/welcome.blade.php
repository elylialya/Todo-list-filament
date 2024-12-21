<x-filament::page>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- To Do Column -->
        <div id="todo-list" class="bg-gray-100 rounded-lg p-4 shadow-md">
            <h2 class="text-xl font-bold text-gray-700 mb-4">To Do</h2>
            <ul>
                @foreach ($todos['todo'] as $todo)
                    <li id="todo-{{ $todo->id }}" class="bg-white p-3 shadow-md rounded mb-2">
                        <div class="font-semibold text-gray-800">{{ $todo->title }}</div>
                        <p class="text-sm text-gray-500">{{ $todo->description }}</p>
                        <button class="edit-todo-btn" data-todo-id="{{ $todo->id }}">Edit</button>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- In Progress Column -->
        <div id="in-progress-list" class="bg-gray-100 rounded-lg p-4 shadow-md">
            <h2 class="text-xl font-bold text-gray-700 mb-4">In Progress</h2>
            <ul>
                @foreach ($todos['in_progress'] as $todo)
                    <li id="in-progress-{{ $todo->id }}" class="bg-white p-3 shadow-md rounded mb-2">
                        <div class="font-semibold text-gray-800">{{ $todo->title }}</div>
                        <p class="text-sm text-gray-500">{{ $todo->description }}</p>
                        <button class="edit-todo-btn" data-todo-id="{{ $todo->id }}">Edit</button>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Done Column -->
        <div id="done-list" class="bg-gray-100 rounded-lg p-4 shadow-md">
            <h2 class="text-xl font-bold text-gray-700 mb-4">Done</h2>
            <ul>
                @foreach ($todos['done'] as $todo)
                    <li id="done-{{ $todo->id }}" class="bg-white p-3 shadow-md rounded mb-2">
                        <div class="font-semibold text-gray-800">{{ $todo->title }}</div>
                        <p class="text-sm text-gray-500">{{ $todo->description }}</p>
                        <button class="edit-todo-btn" data-todo-id="{{ $todo->id }}">Edit</button>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Modal Edit Todo (Hidden initially) -->
    <div id="edit-modal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded shadow-lg">
            <h2 class="text-xl font-semibold">Edit Todo</h2>
            <form id="edit-todo-form">
                <input type="hidden" id="todo-id">
                <div class="mb-4">
                    <label for="title" class="block text-sm font-semibold">Title</label>
                    <input type="text" id="title" class="w-full p-2 mt-1 border border-gray-300 rounded">
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-sm font-semibold">Description</label>
                    <textarea id="description" rows="4" class="w-full p-2 mt-1 border border-gray-300 rounded"></textarea>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Todo</button>
                <button type="button" id="close-modal" class="bg-gray-500 text-white px-4 py-2 rounded mt-2">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Open edit modal when 'Edit' button is clicked
            const editBtns = document.querySelectorAll('.edit-todo-btn');
            editBtns.forEach(button => {
                button.addEventListener('click', function () {
                    const todoId = this.dataset.todoId;
                    const todoElement = document.getElementById(`todo-${todoId}`) || 
                                        document.getElementById(`in-progress-${todoId}`) || 
                                        document.getElementById(`done-${todoId}`);
                    const title = todoElement.querySelector('.font-semibold').textContent;
                    const description = todoElement.querySelector('p').textContent;

                    // Set form values
                    document.getElementById('todo-id').value = todoId;
                    document.getElementById('title').value = title;
                    document.getElementById('description').value = description;

                    // Show the modal
                    document.getElementById('edit-modal').classList.remove('hidden');
                });
            });

            // Close modal
            document.getElementById('close-modal').addEventListener('click', function () {
                document.getElementById('edit-modal').classList.add('hidden');
            });

            // Handle form submission for editing todo
            document.getElementById('edit-todo-form').addEventListener('submit', function (event) {
                event.preventDefault();
                
                const todoId = document.getElementById('todo-id').value;
                const title = document.getElementById('title').value;
                const description = document.getElementById('description').value;

                fetch(`/update-todo/${todoId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ title, description })
                })
                .then(response => response.json())
                .then(data => {
                    // Update todo element with new data
                    const todoElement = document.getElementById(`todo-${todoId}`) ||
                                        document.getElementById(`in-progress-${todoId}`) ||
                                        document.getElementById(`done-${todoId}`);
                    todoElement.querySelector('.font-semibold').textContent = data.todo.title;
                    todoElement.querySelector('p').textContent = data.todo.description;

                    // Close the modal
                    document.getElementById('edit-modal').classList.add('hidden');
                })
                .catch(error => {
                    console.error('Error updating todo:', error);
                });
            });
        });
    </script>
</x-filament::page>
