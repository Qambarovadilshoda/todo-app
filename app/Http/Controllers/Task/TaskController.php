<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Filters\TaskFilter;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\FilterTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;

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
    private function findTask($id)
    {
        return Auth::user()->tasks()->find($id);
    }
    public function tasksFilter(FilterTaskRequest $request)
    {
        $filter = new TaskFilter();
        $query = Auth::user()->tasks();
        $filteredQuery = $filter->apply($query, $request->validated());
        $filteredTasks = $filteredQuery->paginate(5);
        if ($filteredTasks->isEmpty()) {
            return $this->error('No task found for these filters', 404);
        }
        return $this->responsePagination($filteredTasks, TaskResource::collection($filteredTasks));
    }
    public function updateStatus(UpdateTaskStatusRequest $request, $id)
    {
        $task = $this->findTask($id);
        if (!$task) {
            return $this->error('Task not found', 404);
        }
        $task->status = $request->status;
        $task->save();
        return $this->success(new TaskResource($task));
    }
}
