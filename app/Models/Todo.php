<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enum\TodoStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = ['label', 'title', 'description', 'status', 'checklist_name', 'checklist_status', 'user_id'];
    
    protected $casts = [
        'checklist_status' => 'boolean', // Cast status checklist sebagai boolean
    ];

    public function setStatusAttribute($value)
    {
        if (!in_array($value, [TodoStatus::TODO, TodoStatus::IN_PROGRESS, TodoStatus::DONE])) {
            throw new \InvalidArgumentException("Invalid status value.");
        }

        $this->attributes['status'] = $value;
    }

    public function getStatusLabelAttribute()
    {
        return TodoStatus::getLabels()[$this->status] ?? 'Unknown';
    }

    public function toggleChecklist()
    {
        $this->checklist_status = !$this->checklist_status; // Toggle nilai boolean
        $this->save();
    }

    public function checklists()
{
    return $this->hasMany(Checklist::class, 'task_id');
}

public function user(): BelongsTo
{
    return $this->belongsTo(User::class);
}


protected static function booted()
{
    static::creating(function ($todo) {
        if (auth()->check()) {
            $todo->user_id = auth()->id(); 
        }
    });
}

public function labels()
{
    return $this->hasMany(Label::class, 'task_id');
}

public function members()
{
    return $this->belongsToMany(User::class, 'members', 'task_id', 'user_id');
}


}
