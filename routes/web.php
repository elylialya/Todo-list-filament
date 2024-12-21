<?php

use App\Filament\Pages\KanbanBoard;
use Filament\Facades\Filament;

    use App\Http\Controllers\TodoController;

   // Add this route to your routes/web.php
Route::post('/todos/update-status', [Kanban::class, 'updateTodoStatus'])->name('todos.update-status');

                    