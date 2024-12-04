<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Auth::user()->tasks()->with('user')->paginate(10);
        return $this->responsePagination($tasks, TaskResource::collection($tasks));
    }
}
