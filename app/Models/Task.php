<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{

   
    
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'parent_id',
        'title',
        'description',
        'priority',
        'status',
        'end_date',
    ];

      public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function subtasks()
    {
        return $this->hasMany(Task::class, 'parent_id');
    }

    // Parent of this subtask
    public function parent()
    {
        return $this->belongsTo(Task::class, 'parent_id');
    }

    // Many-to-Many: Hashtags
    public function hashtags()
    {
        return $this->belongsToMany(Hashtag::class, 'task_hashtag');
    }

    // One-to-Many: Files
    public function files()
    {
        return $this->hasMany(TaskFile::class);
    }

    // One-to-Many: Comments
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }


}
