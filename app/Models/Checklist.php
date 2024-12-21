<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checklist extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'task_id', 'checklist_id', 'checklist_status'];


    public function todo()
{
    return $this->belongsTo(Todo::class, 'task_id');
}

    public function todos(): BelongsToMany
    {
        return $this->belongsToMany(Todo::class, 'task_id', 'id');
    }
    
}
