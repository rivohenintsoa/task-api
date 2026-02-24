<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'assigned_to',
        'created_by',
        'due_date'
    ];

    // Relations
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeStatus($query, ?string $status)
    {
        return $status ? $query->where('status', $status) : $query;
    }

    public function scopePriority($query, ?string $priority)
    {
        return $priority ? $query->where('priority', $priority) : $query;
    }

    public function scopeAssignedTo($query, ?int $userId)
    {
        return $userId ? $query->where('assigned_to', $userId) : $query;
    }

    public function scopeSearch($query, ?string $term)
    {
        return $term ? $query->where('title', 'like', "%{$term}%") : $query;
    }

    public function scopeForUser($query, $user)
    {
        if ($user->role !== UserRole::ADMIN) {
            return $query->where('assigned_to', $user->id);
        }
        return $query;
    }
}
