<?php

namespace App\Http\Resources;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'title' => $this->title,
            'description' => $this->description,

            'status' => TaskStatus::from($this->status)->label(),
            'priority' => TaskPriority::from($this->priority)->label(),

            'due_date' => $this->due_date,

            'assigned_user' => [
                'id' => $this->assignedUser?->id,
                'name' => $this->assignedUser?->name,
                'email' => $this->assignedUser?->email,
            ],

            'creator' => [
                'id' => $this->creator?->id,
                'name' => $this->creator?->name,
            ],

            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
