<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks()->with('user')->paginate(10);
        return $this->responsePagination($tasks, TaskResource::collection($tasks));
    }
    public function show($id)
    {
        $task = $this->findTask($id);
        if (!$task) {
            return $this->error('Task not found', 404);
        }
        return $this->success(new TaskResource($task->load('user')));
    }
    public function store(StoreTaskRequest $request)
    {
        $task = new Task();
        $task->user_id = Auth::id();
        $task->title = $request->title;
        $task->description = $request->description;
        $task->status = $request->status;
        $task->save();
        return $this->success(new TaskResource($task->load('user')), 'Task created', 201);
    }
    public function update(UpdateTaskRequest $request, $id)
    {
        $task = $this->findTask($id);
        if (!$task) {
            return $this->error('Task not found', 404);
        }
        $task->update($request->validated());
        return $this->success(new TaskResource($task), 'Task updated');
    }
    public function destroy($id)
    {
        $task = $this->findTask($id);
        if (!$task) {
            return $this->error('Task not found', 404);
        }
        $task->delete();
        return $this->success([], 'Task deleted', 204);
    }
}
