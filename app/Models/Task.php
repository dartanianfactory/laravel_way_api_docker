<?php

namespace App\Models;

use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description', 
        'status'
    ];

    protected $casts = [
        'status' => TaskStatus::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopePending($query)
    {
        return $query->where('status', TaskStatus::PENDING->value);
    }

    public function scopeDone($query)
    {
        return $query->where('status', TaskStatus::DONE->value);
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', TaskStatus::CANCELED->value);
    }

    public function scopeLost($query)
    {
        return $query->where('status', TaskStatus::LOST->value);
    }

    public function isPending(): bool
    {
        return $this->status === TaskStatus::PENDING;
    }

    public function isDone(): bool
    {
        return $this->status === TaskStatus::DONE;
    }

    public function isCanceled(): bool
    {
        return $this->status === TaskStatus::CANCELED;
    }

    public function isLost(): bool
    {
        return $this->status === TaskStatus::LOST;
    }
}
