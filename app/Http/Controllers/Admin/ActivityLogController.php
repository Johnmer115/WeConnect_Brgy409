<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $module = $request->query('module', '');
        $action = $request->query('action', '');

        $query = ActivityLog::query()->latest();

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($module !== '') {
            $query->where('module', $module);
        }

        if ($action !== '') {
            $query->where('action', $action);
        }

        // Get unique options from existing records for simple dynamic filters
        $modules = ActivityLog::select('module')->distinct()->orderBy('module')->pluck('module');
        $actions = ActivityLog::select('action')->distinct()->orderBy('action')->pluck('action');

        return view('admin.management.activity.index', [
            'logs' => $query->paginate(15)->withQueryString(),
            'modules' => $modules,
            'actions' => $actions,
            'selectedModule' => $module,
            'selectedAction' => $action,
            'search' => $search,
        ]);
    }
}
