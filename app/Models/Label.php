<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class label extends Model
{
    use HasFactory;
     protected $fillable = ['task_id', 'name', 'color'];

     public function todos()
     {
         return $this->belongsTo(Todo::class, 'task_id');
     }
     

}
