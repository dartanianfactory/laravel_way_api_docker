<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $order = $request->get('order', 'DESC');
        $order = in_array(strtoupper($order), ['ASC', 'DESC']) ? strtoupper($order) : 'DESC';
        
        $tasks = Task::orderBy('created_at', $order)->get();
        
        return TaskResource::collection($tasks);
    }

    public function store(StoreTaskRequest $request): TaskResource
    {
        $validated = $request->validated();
        
        if (!isset($validated['status'])) {
            $validated['status'] = TaskStatus::PENDING->value;
        }
        
        $task = Task::create($validated);
        return new TaskResource($task);
    }

    public function show(Task $task): TaskResource
    {
        return new TaskResource($task);
    }

    public function update(UpdateTaskRequest $request, Task $task): TaskResource
    {
        $task->update($request->validated());
        return new TaskResource($task);
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return response()->noContent();
    }
}
