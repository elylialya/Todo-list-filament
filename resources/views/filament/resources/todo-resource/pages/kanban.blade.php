public $checklistItems = []; 
    public $checklistName = [];  // Store the checklist items
public $newChecklistItem = '';  // Store the input for the new checklist item

    // Open the modal to add/edit checklist
    public function openChecklistModal($taskId)
    {
        $task = Todo::find($taskId);
        if ($task) {
            $this->currentTask = $task->toArray();
            $this->checklistItems = $task->checklist_items ?? []; // Load existing checklist items if any
            $this->isChecklistModalOpen = true;
        }
    }


// Add new checklist item
public function addChecklistItem()
{
    if (empty($this->newChecklistItem)) {
        session()->flash('error', 'Checklist item cannot be empty.');
        return;
    }

    // Add new checklist item to the list
    $this->checklistName[] = [
        'name' => $this->newChecklistItem,
        'completed' => false,  // Initially, the checklist item is not completed
    ];

    // Clear the input field
    $this->newChecklistItem = '';

    $this->checklistItems = Todo::find($this->currentTask['id'])->checklistItems;
}

public function saveChecklist()
{
    // No need to save manually since each checklist item is saved individually
    $this->isChecklistModalOpen = false;
    $this->loadTodos();

    // Flash message to indicate success
    session()->flash('message', 'Checklist updated successfully!');
}


  <div class="task-labels flex flex-wrap space-x-2">
    @foreach ($task->labels as $label)
        <!-- Check if the label's task_id matches the current task -->
        @if ($label->task_id == $task->id)
            <span class="px-2 py-1 text-white rounded flex items-center" style="background-color: {{ $label->color }};">
                {{ $label->name }}
                <button wire:click="removeLabel({{ $task->id }}, {{ $label->id }})" class="ml-2 text-red-200 hover:text-red-400">
                    &times;
                </button>
            </span>
        @endif
    @endforeach
</div>