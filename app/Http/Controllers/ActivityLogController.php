<?php

namespace App\Http\Controllers;

use App\Http\Requests\IndexActivityLogRequest;
use App\Models\ActivityLog;

class ActivityLogController extends Controller
{
    /**
     * Menampilkan daftar riwayat aktivitas.
     */
    public function index(IndexActivityLogRequest $request)
    {
        // Mengambil input yang sudah divalidasi.
        $validated = $request->validated();

        $search = $validated['search'] ?? null;
        $actionType = $validated['action_type'] ?? null;

        // Mengambil data aktivitas beserta pengguna terkait.
        $activityLogs = ActivityLog::with('user')
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery
                        ->where('description', 'like', "%{$search}%")
                        ->orWhereHas('user', function ($userQuery) use ($search) {
                            $userQuery->where('name', 'like', "%{$search}%");
                        });
                });
            })
            ->when($actionType, function ($query, $actionType) {
                $query->where('action_type', $actionType);
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.activity-logs.index', compact(
            'activityLogs',
            'search',
            'actionType'
        ));
    }
}