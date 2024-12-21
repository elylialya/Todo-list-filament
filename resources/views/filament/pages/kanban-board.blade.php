<x-filament::page>
    <div x-data="{ isEditModalOpen: @entangle('isEditModalOpen'), currentTask: @entangle('currentTask'), isChecklistModalOpen: @entangle('isChecklistModalOpen'), currentChecklistTaskId: null, checklistItems: [] }">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- To Do Column -->
            <div id="todo-column" class="kanban-column w-full p-4 bg-blue-100 rounded-lg shadow-md" data-status="todo" ondrop="drop(event)" ondragover="allowDrop(event)">
                <h2 class="text-xl font-bold text-gray-700 mb-4">To Do</h2>
                <ul>
                    @foreach ($todos['todo'] as $task)
                        <li id="task-{{ $task->id }}" class="task-item bg-white p-3 shadow-md rounded mb-2" data-task-id="{{ $task->id }}" draggable="true" ondragstart="drag(event)">
                            <div class="task-labels flex flex-wrap space-x-2">
                            @foreach ($task->labels as $label)
                                <div class="text-xs mb-3 font-semibold p-1 inline-block" style="background-color: {{ $label->color }}; color: white; border-radius: 4px;">
                                    {{ $label->name }}
                                </div>
                            @endforeach
                            </div>
                            <div class="font-semibold text-gray-800">{{ $task->title }}</div>
                            <p class="text-sm text-gray-500">{{ $task->description }}</p>
                               <div class="mt-4">
                                <h2 class="text-sm font-bold text-gray-700 mb-1">Checklist :</h2>
                                <ul>
                                    <li class="flex items-center mb-1">
                                        <input class="w-3 h-3" type="checkbox" wire:click="toggleChecklistStatus" {{ $task->checklists->where('checklist_status', true)->count() == $task->checklists->count() ? 'checked' : '' }} />
                                        <label class="text-sm ml-1">   ({{ $task->checklists->where('checklist_status', true)->count() }}/{{ $task->checklists->count() }})</label>
                                    </li>
                                </ul>
                           <div class="flex items-center justify-between w-full">
                                <div class="flex items-center space-x-2">
                                    <button wire:click="openModal({{ $task->id }})"  class="mt-2 text-blue-600 text-sm font-semibold">Edit</button>
                                    <button @click="deleteTask({{ $task->id }})" class="mt-2 text-red-500 text-sm font-semibold">Delete</button>
                                </div>
                                <div class="flex items-center -space-x-2">
                                @foreach($task->members as $member)
                                <div class="text-xs w-8 h-8 flex items-center justify-center rounded-full border border-slate-300 bg-slate-50 text-black font-semibold shadow-md">
                                {{ strtoupper(substr($member->name, 0, 1)) . strtoupper(substr($member->name, 1, 1)) }}
                                </div>
                                @endforeach
                                </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- In Progress Column -->
            <div id="in-progress-column" class="kanban-column w-full p-4 bg-yellow-100 rounded-lg shadow-md" data-status="in-progress" ondrop="drop(event)" ondragover="allowDrop(event)">
                <h2 class="text-xl font-bold text-gray-700 mb-4">In Progress</h2>
                <ul>
                    @foreach ($todos['in-progress'] as $task)
                        <li id="task-{{ $task->id }}" class="task-item bg-white p-3 shadow-md rounded mb-2" data-task-id="{{ $task->id }}" draggable="true" ondragstart="drag(event)">
                             <div class="task-labels flex flex-wrap space-x-2">
                    @foreach ($task->labels as $label)
                        <div class="text-xs mb-3 font-semibold p-1 inline-block" style="background-color: {{ $label->color }}; color: white; border-radius: 4px;">
                            {{ $label->name }}
                        </div>
                    @endforeach
                    </div>
                            <div class="font-semibold text-gray-800">{{ $task->title }}</div>
                            <p class="text-sm text-gray-500">{{ $task->description }}</p>
                                <div class="mt-4">
                                <h2 class="text-sm font-bold text-gray-700 mb-1">Checklist :</h2>
                                <ul>
                                    <li class="flex items-center mb-1">
                                        <input class="w-3 h-3" type="checkbox" wire:click="toggleChecklistStatus" {{ $task->checklists->where('checklist_status', true)->count() == $task->checklists->count() ? 'checked' : '' }} />
                                        <label class="text-sm ml-1">   ({{ $task->checklists->where('checklist_status', true)->count() }}/{{ $task->checklists->count() }})</label>
                                    </li>
                                </ul>
                             <div class="flex items-center justify-between w-full">
                                <div class="flex items-center space-x-2">
                                     <button wire:click="openModal({{ $task->id }})"  class="mt-2 text-blue-600 text-sm font-semibold">Edit</button>
                                    <button @click="deleteTask({{ $task->id }})" class="mt-2 text-red-500 text-sm font-semibold">Delete</button>
                                </div>
                                <div class="flex items-center -space-x-2">
                                @foreach($task->members as $member)
                                <div class="text-xs w-8 h-8 flex items-center justify-center rounded-full border border-slate-300 bg-slate-50 text-black font-semibold shadow-md">
                                {{ strtoupper(substr($member->name, 0, 1)) . strtoupper(substr($member->name, 1, 1)) }}
                                </div>
                                @endforeach
                                </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- Done Column -->
            <div id="done-column" class="kanban-column w-full p-4 bg-green-100 rounded-lg shadow-md" data-status="done" ondrop="drop(event)" ondragover="allowDrop(event)">
                <h2 class="text-xl font-bold text-gray-700 mb-4">Done</h2>
                <ul>
                    @foreach ($todos['done'] as $task)
                        <li id="task-{{ $task->id }}" class="task-item bg-white p-3 shadow-md rounded mb-2" data-task-id="{{ $task->id }}" draggable="true" ondragstart="drag(event)">
                              <div class="task-labels flex flex-wrap space-x-2">
                            @foreach ($task->labels as $label)
                                <div class="text-xs mb-3 font-semibold p-1 inline-block" style="background-color: {{ $label->color }}; color: white; border-radius: 4px;">
                                    {{ $label->name }}
                                </div>
                            @endforeach
                            </div>
                            <div class="font-semibold text-gray-800">{{ $task->title }}</div>
                            <p class="text-sm text-gray-500">{{ $task->description }}</p>
                                    <div class="mt-4">
                                            <h2 class="text-sm font-bold text-gray-700 mb-1">Checklist :</h2>
                                            <ul>
                                                <li class="flex items-center mb-1">
                                                    <input class="w-3 h-3" type="checkbox" wire:click="toggleChecklistStatus" {{ $task->checklists->where('checklist_status', true)->count() == $task->checklists->count() ? 'checked' : '' }} />
                                                    <label class="text-sm ml-1">   ({{ $task->checklists->where('checklist_status', true)->count() }}/{{ $task->checklists->count() }})</label>
                                                </li>
                                            </ul>
                                        <div class="flex items-center justify-between w-full">
                                            <div class="flex items-center space-x-2">
                                        <button wire:click="openModal({{ $task->id }})"  class="mt-2 text-blue-600 text-sm font-semibold">Edit</button>
                                                <button @click="deleteTask({{ $task->id }})" class="mt-2 text-red-500 text-sm font-semibold">Delete</button>
                                            </div>
                                            <div class="flex items-center -space-x-2">
                                                @foreach($task->members as $member)
                                                    <div class="text-xs w-8 h-8 flex items-center justify-center rounded-full border border-slate-300 bg-slate-50 text-black font-semibold shadow-md">
                                                        {{ strtoupper(substr($member->name, 0, 1)) . strtoupper(substr($member->name, 1, 1)) }}
                                                    </div>
                                                @endforeach
                                            </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                {{-- Modal --}}
                <div>
                    @if ($isOpen)
                        <div class="fixed inset-0 z-50 flex items-center justify-center bg-slate bg-opacity-50">
                            <div class="bg-white rounded-lg shadow-lg w-1/2 relative">
                                <button wire:click="closeModal" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>

                                <div class="p-4 border-b">
                                    <h2 class="text-xl font-bold">
                                        {{ $currentTask['id'] ? 'Edit Task' : 'Create Task' }}
                                    </h2>
                                </div>

                                {{-- aasign members --}}
                               <div class="p-4 space-y-4">
                                <div class="mb-4">
                                    <label for="members" class="font-bold mb-4">Assign Members</label>
                                    <div class="relative" x-data="{ open: false, selected: @entangle('selectedMembers'), searchQuery: '' }">
                                        <button @click="open = !open" type="button" class="relative w-full mt-3 py-3 ps-4 pe-9 border border-slate-600 rounded bg-white cursor-pointer text-start text-sm focus:ring-2 focus:ring-blue-500">
                                            <span>Search Members...</span>
                                            <div class="absolute top-1/2 end-3 -translate-y-1/2">
                                                <svg class="w-5 h-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path d="m7 15 5 5 5-5"/>
                                                    <path d="m7 9 5-5 5 5"/>
                                                </svg>
                                            </div>
                                        </button>
                                        <div x-show="open" @click.away="open = false" class="absolute mt-2 z-50 w-full max-h-72 bg-white border border-gray-200 rounded overflow-y-auto shadow-lg p-1 space-y-1">
                                            <div class="p-2">
                                                <input type="text" x-model="searchQuery" placeholder="Search..." class="block w-full text-sm border-gray-200 rounded-lg focus:border-blue-500 focus:ring-blue-500 dark:bg-neutral-900 dark:border-neutral-700 dark:text-neutral-400 dark:placeholder-neutral-500 py-2 px-3">
                                            </div>
                                            @foreach($users as $user)
                                                <div x-show="!searchQuery || $refs['user-{{ $user->id }}'].innerText.toLowerCase().includes(searchQuery.toLowerCase())"
                                                    class="flex items-center p-2 cursor-pointer hover:bg-gray-100 rounded-lg" 
                                                    @click="selected.includes('{{ $user->id }}') 
                                                        ? selected = selected.filter(id => id !== '{{ $user->id }}') 
                                                        : selected.push('{{ $user->id }}'); 
                                                        @this.updateSelectedMembers(selected)">
                                                    <span x-ref="user-{{ $user->id }}" class="text-sm text-gray-800">{{ $user->name }}</span>
                                                    <span x-show="selected.includes('{{ $user->id }}')" class="ml-auto text-blue-600">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                              <div x-show="selected.length > 0" class="mt-2 flex flex-wrap gap-2">
                            <template x-for="(id, index) in selected" :key="index">
                                <div class="flex items-center space-x-2">
                                    <div class="text-xs w-8 h-8 flex items-center justify-center rounded-full border border-slate-300 bg-slate-50 text-black font-semibold shadow-md">
                                        <span x-text="(() => {
                                            let name = $refs['user-' + id].innerText.split(' ');
                                            return (name[0] && name[0].length > 1 ? name[0].slice(0, 2).toUpperCase() : name[0].charAt(0).toUpperCase());
                                        })()"></span>
                                    </div>

                                    <button type="button" @click="selected = selected.filter(selectedId => selectedId !== id); @this.updateSelectedMembers(selected)"
                                        class="text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                            </div>
                        </div>
                    <!-- Labels Section -->
                    <div>
                        <h3 class="font-bold ">Labels</h3>
                        <div class="flex gap-2 mb-2">
                            <input type="text" wire:model="newLabelName" placeholder="Label name" class="flex-1 border border-slate-600 rounded px-2 py-1">
                            <input type="color" wire:model="newLabelColor" class="w-12 h-10 border rounded">
                            <button wire:click="addLabel" class="px-2 py-1 bg-blue-600 text-white rounded">Add</button>
                        </div>
                        <div class="flex flex-wrap mb-4 gap-2">
                            @foreach ($labels as $label)
                                <li class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span style="background-color: {{ $label['color'] }};" class="px-2 py-1 rounded text-white">
                                            {{ $label['name'] }}
                                            <button wire:click="deleteLabel({{ $label['id'] }})" class="ml-2 text-red-200 hover:text-red-400">
                                                &times;
                                            </button>
                                        </span>
                                    </div>
                                </li>
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="title" class="block font-medium">Title</label>
                        <input type="text" wire:model="currentTask.title" id="title" class="w-full border rounded px-2 py-1">
                    </div>
                    <div class="mb-2">
                        <label for="description" class="block font-medium">Description</label>
                        <textarea wire:model="currentTask.description" id="description" class="w-full border rounded px-2 py-1"></textarea>
                    </div>
                    <div class="mb-4">
                        <label for="status" class="block font-medium">Status</label>
                        <select wire:model="currentTask.status" id="status" class="w-full border rounded px-2 py-1">
                            <option value="todo">To Do</option>
                            <option value="in-progress">In Progress</option>
                            <option value="done">Done</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <h3 class="font-bold">Checklist</h3>
                        <div class="flex gap-2 mb-2">
                            <input type="text" wire:model="checklistName" placeholder="Add checklist item" class="flex-1 border rounded px-2 py-1">
                            <button wire:click="addChecklistItem" class="px-4 py-1 bg-blue-600 text-white rounded">Add</button>
                        </div>
                        <ul>
                            @foreach ($checklists as $checklist)
                                <li class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2">
                                        <input 
                                            type="checkbox" 
                                            wire:click="toggleChecklistStatus({{ $checklist->id }})" 
                                            {{ $checklist->checklist_status ? 'checked' : '' }}
                                        >
                                        <span class="{{ $checklist->checklist_status ? 'line-through text-gray-500' : '' }}">
                                            {{ $checklist->name }}
                                        </span>
                                    </div>
                                    <div wire:click="deleteChecklistItem({{ $checklist->id }})" class="cursor-pointer text-red-600 hover:text-red-800 mr-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                                        </svg>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="p-4 border-t flex justify-end gap-2">
                    <button 
                        wire:click="closeModal" 
                        class="flex-1 px-4 py-2 bg-gray-300 rounded text-center">
                        Cancel
                    </button>
                    <button 
                        wire:click="saveTask" 
                        class="flex-1 px-4 py-2 bg-blue-600 text-white rounded text-center">
                        Save Task
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
   <script>
  function deleteTask(taskId) {
    Swal.fire({
      title: "Are you sure?",
      text: "You won't be able to revert this!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Yes, delete it!"
    }).then((result) => {
      if (result.isConfirmed) {
        @this.call('deleteTask', taskId);
      }
    });
  }

</script>
    <script>
        function drag(event) {
            event.dataTransfer.setData("taskId", event.target.id);
        }
        function allowDrop(event) {
            event.preventDefault(); 
        }
        function drop(event) {
            event.preventDefault();
            const taskId = event.dataTransfer.getData("taskId");
            const draggedTask = document.getElementById(taskId);
            const targetColumn = event.target.closest('.kanban-column');
            if (targetColumn && draggedTask) {
                targetColumn.querySelector('ul').appendChild(draggedTask);
                const newStatus = targetColumn.getAttribute('data-status');
                const taskIdValue = draggedTask.getAttribute('data-task-id');
                @this.call('updateTaskStatus', taskIdValue, newStatus);
            }
        }

        function saveTask() {
    const updatedTask = {
        id: currentTask.id,
        title: currentTask.title,
        description: currentTask.description,
        status: currentTask.status,
        label: currentTask.label 
    };
    fetch('/update-task', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(updatedTask)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            isEditModalOpen = false;
        } else {
            console.error('Error updating task:', data.error);
           
        }
    })
    .catch(error => {
        console.error('Error updating task:', error);
    });
}
    </script>
</x-filament::page>


