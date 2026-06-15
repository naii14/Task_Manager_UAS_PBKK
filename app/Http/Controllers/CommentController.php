<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use OpenApi\Attributes as OA;

class CommentController extends Controller
{
    #[OA\Get(
        path: "/tasks/{taskId}/comments",
        summary: "List Comments",
        description: "Mengambil semua komentar diskusi pada tugas tertentu.",
        tags: ["Comments"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(
        response: 200,
        description: "Daftar komentar berhasil diambil"
    )]
    public function index($taskId)
    {
        $task = Task::findOrFail($taskId);
        $comments = $task->comments()->with('user')->orderBy('created_at', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $comments
        ]);
    }

    #[OA\Post(
        path: "/tasks/{taskId}/comments",
        summary: "Post Comment",
        description: "Menambahkan komentar baru ke tugas. Memicu reload real-time di client lain.",
        tags: ["Comments"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(
        response: 201,
        description: "Komentar berhasil ditambahkan"
    )]
    public function store(Request $request, $taskId)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $task = Task::findOrFail($taskId);

        $comment = TaskComment::create([
            'task_id' => $task->id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        // Remove typing indicator immediately once comment is posted
        Cache::forget("typing_{$taskId}_" . Auth::id());

        // Invalidate cached task details
        Cache::forget("task_detail_{$taskId}");

        // Trigger real-time task update so other clients reload/receive the new comment
        Cache::put('tasks_last_updated', microtime(true), 600);

        return response()->json([
            'status' => 'success',
            'message' => 'Komentar berhasil ditambahkan',
            'data' => $comment->load('user')
        ], 201);
    }

    #[OA\Post(
        path: "/tasks/{taskId}/comments/typing",
        summary: "Trigger Typing State",
        description: "Mempublikasikan indikator bahwa user saat ini sedang aktif mengetik di input kolom tugas ini.",
        tags: ["Real-time Sync"],
        security: [["bearerAuth" => []]]
    )]
    #[OA\Response(
        response: 200,
        description: "Indikator terkirim"
    )]
    public function typing(Request $request, $taskId)
    {
        $userId = Auth::id();
        $userName = Auth::user()->name;

        // Save typing state in Cache for 4 seconds
        Cache::put("typing_{$taskId}_{$userId}", [
            'name' => $userName,
            'time' => time()
        ], 4);

        return response()->json([
            'status' => 'success',
            'message' => 'Typing registered'
        ]);
    }
}
