<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'task_name',
        'description',
        'status',
        'due_date',
    ];

    protected $casts = [
        'due_date' => 'date',
    ];

    public function isOverdue(): bool
    {
        return $this->status === 'Pending'
        && $this->due_date !== null
        && $this->due_date->isPast();
    }
}