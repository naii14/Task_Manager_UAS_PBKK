<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // Middleware should already validate token ability.
        $tasks = Auth::user()->tasks()->latest()->get(['id', 'judul', 'status']);

        return response()->json([
            'status' => 'success',
            'data' => $tasks,
        ]);
    }
}

