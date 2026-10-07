<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $tasks = Task::orderBy('deadline', 'asc')->get();
        return response()->json($tasks, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'    => 'required|string|min:3|max:255',
            'course'   => 'required|string|min:2|max:255',
            'deadline' => 'required|date',
            'status'   => 'nullable|string|max:50',
        ]);

        if (empty($validated['status'])) {
            $validated['status'] = 'Belum Selesai';
        }

        $task = Task::create($validated);

        return response()->json([
            'message' => 'Tugas kuliah berhasil ditambahkan',
            'data'    => $task,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Tugas kuliah tidak ditemukan'], 404);
        }

        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Tugas kuliah tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'title'    => 'required|string|min:3|max:255',
            'course'   => 'required|string|min:2|max:255',
            'deadline' => 'required|date',
            'status'   => 'nullable|string|max:50',
        ]);

        $task->update($validated);

        return response()->json([
            'message' => 'Tugas kuliah berhasil diperbarui',
            'data'    => $task,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json(['message' => 'Tugas kuliah tidak ditemukan'], 404);
        }

        $task->delete();

        return response()->json(['message' => 'Tugas kuliah berhasil dihapus'], 200);
    }
}
