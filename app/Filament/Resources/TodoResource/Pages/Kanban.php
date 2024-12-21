<?php

namespace App\Filament\Resources\TodoResource\Pages;

use App\Models\Todo;
use App\Models\Checklist;
use App\Models\Label; 
use App\Models\User;
use App\Models\Member; 
use App\Filament\Resources\TodoResource;
use Filament\Resources\Pages\Page;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\HiddenInput;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Kanban extends Page
{
    protected static string $resource = TodoResource::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard';
    protected static string $view = 'filament.pages.kanban-board';


    public $todos = [];
    public $isOpen = false; 
    public $taskId;
    public $currentTask = [
        'id' => null,
        'label' => '',
        'title' => '',
        'description' => '',
        'status' => 'todo',
    ];
    public $checklists = [];
    public $checklistName = '';
    public $labels = []; 
    public $newLabelName = ''; 
    public $newLabelColor = '#000000';
  public $selectedMembers = []; 
    public $members = [];
    public $users = [];
    

    public function mount()
{
    $this->todos = [
        'todo' => Todo::where('status', 'todo')->get(),
        'in-progress' => Todo::where('status', 'in-progress')->get(),
        'done' => Todo::where('status', 'done')->get(),
    ];

    $this->users = User::all(); 
}


public function updateSelectedMembers($selected)
{
    $task = Todo::find($this->taskId); 
    if ($task) {
        $task->members()->sync($selected);
        $this->selectedMembers = $selected; 
    }
}

 
public function openModal($taskId = null)
{
    $this->isOpen = true;
    $this->taskId = $taskId;

    if ($taskId) {
        $task = Todo::with(['labels', 'members'])->find($taskId);
        if ($task) {
            $this->currentTask = $task->toArray();
            $this->labels = $task->labels->toArray();
            $this->selectedMembers = $task->members->pluck('id')->toArray();
            $this->loadChecklists();
        }
    } else {
        $this->resetFields();
        $this->selectedMembers = User::pluck('id')->toArray();
    }
}

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetFields();
    }

    public function saveTask()
    {
        $task = Todo::updateOrCreate(
            ['id' => $this->currentTask['id']],
            [
                'label' => $this->currentTask['label'],
                'title' => $this->currentTask['title'],
                'description' => $this->currentTask['description'],
                'status' => $this->currentTask['status'],
            ]
        );

        $this->taskId = $task->id;
        $this->loadChecklists();
        session()->flash('message', 'Task saved successfully with members auto-added!');
    }
    
    
    //label

    public function addLabel()
    {
        $this->validate([
            'newLabelName' => 'required|string|max:255',
            'newLabelColor' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);
    
        $label = Label::create([
            'task_id' => $this->taskId,
            'name' => $this->newLabelName,
            'color' => $this->newLabelColor,
        ]);
    
        $this->labels[] = [
            'id' => $label->id,
            'name' => $label->name,
            'color' => $label->color,
        ];
    
        $this->resetLabelForm();
    }
    
    public function deleteLabel($labelId)
    {
        $label = Label::find($labelId);
        if ($label) {
            $label->delete();
            $this->labels = array_filter($this->labels, function ($label) use ($labelId) {
                return $label['id'] !== $labelId;
            });
            $this->labels = array_values($this->labels);
        }
    }
    
    public function updateLabel()
    {
        $this->validate([
            'newLabelName' => 'required|string|max:255',
            'newLabelColor' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);
    
        $label = Label::find($this->editingLabelId);
        if ($label) {
            $label->update([
                'name' => $this->newLabelName,
                'color' => $this->newLabelColor,
            ]);
    
            $this->labels = array_map(function ($labelItem) {
                if ($labelItem['id'] === $this->editingLabelId) {
                    $labelItem['name'] = $this->newLabelName;
                    $labelItem['color'] = $this->newLabelColor;
                }
                return $labelItem;
            }, $this->labels);
    
            $this->resetLabelForm();
        }
    }
    
    private function resetLabelForm()
    {
        $this->newLabelName = '';
        $this->newLabelColor = '#000000';
    }
    
private function refreshLabels()
{
    $this->labels = Label::where('task_id', $this->taskId)->get()->toArray();
}

// done label

    // checklist
    public function loadChecklists()
    {
        $this->checklists = Checklist::where('task_id', $this->taskId)->get();
    }

   
    public function addChecklistItem()
    {
        $this->validate([
            'checklistName' => 'required|string|max:255',
        ]);

        Checklist::create([
            'task_id' => $this->taskId,
            'name' => $this->checklistName,
            'checklist_status' => false,
        ]);

        $this->checklistName = '';
        $this->loadChecklists();
    }

    // Toggle checklist status
    public function toggleChecklistStatus($checklistId)
    {
        $checklist = Checklist::find($checklistId);
        if ($checklist) {
            $checklist->update([
                'checklist_status' => !$checklist->checklist_status,
            ]);

            $this->loadChecklists();
        }
    }

    // Delete a checklist item
    public function deleteChecklistItem($checklistId)
    {
        $checklist = Checklist::find($checklistId);
        if ($checklist) {
            $checklist->delete();
            $this->loadChecklists();
        }
    }

    public function updateTaskStatus($taskId, $status)
{
    $task = Todo::find($taskId);

    if ($task) {
        $task->update(['status' => $status]);
        $this->todos = [
            'todo' => Todo::where('status', 'todo')->get(),
            'in-progress' => Todo::where('status', 'in-progress')->get(),
            'done' => Todo::where('status', 'done')->get(),
        ];

        session()->flash('message', 'Task status updated successfully!');
    }
}

public function deleteTask($taskId)
{
    $task = Todo::find($taskId);
    if ($task) {
        $task->delete();
    }
    $this->mount();
}

   // Reset all fields
   public function resetFields()
   {
       $this->taskId = null;
       $this->currentTask = [];
       $this->checklists = [];
       $this->labels = [];
       $this->selectedMembers = [];
   }
   protected function getHeaderActions(): array
   {
       return [
           Action::make('createOrUpdateTodo')
               ->label('Save Todo')
               ->form([
                   TextInput::make('label')
                       ->label('Label')
                       ->required()
                       ->maxLength(255),
                   TextInput::make('title')
                       ->label('Title')
                       ->required()
                       ->maxLength(255),
                   TextInput::make('description')
                       ->label('Description')
                       ->required()
                       ->maxLength(255),
                   Select::make('status')
                       ->label('Status')
                       ->options([
                           'todo' => 'To Do',
                           'in-progress' => 'In Progress',
                           'done' => 'Done',
                       ])
                       ->default('todo')
                       ->required(),
               ])
               ->action(function (array $data): void {
                   Todo::updateOrCreate(
                       ['id' => $data['id'] ?? null],
                       [
                           'label' => $data['label'],
                           'title' => $data['title'],
                           'description' => $data['description'],
                           'status' => $data['status'],
                       ]
                   );
               }),
       ];
   }
} 
   
