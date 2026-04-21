<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use OwenIt\Auditing\Models\Audit;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = Audit::with('user')
            ->orderBy('created_at', 'desc');

        // Filter by model type
        if ($request->has('auditable_type') && $request->auditable_type) {
            $query->where('auditable_type', 'App\\Models\\' . $request->auditable_type);
        }

        // Filter by event
        if ($request->has('event') && $request->event) {
            $query->where('event', $request->event);
        }

        // Filter by user
        if ($request->has('user_id') && $request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        // Date range
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $audits = $query->paginate(25);

        $modelTypes = [
            'Book' => 'Books',
            'User' => 'Users',
            'Category' => 'Categories',
            'Order' => 'Orders',
            'Review' => 'Reviews',
        ];

        $events = ['created', 'updated', 'deleted', 'restored'];

        return view('admin.audit-logs.index', compact('audits', 'modelTypes', 'events'));
    }

    public function show(Audit $audit)
    {
        $audit->load('user');
        return view('admin.audit-logs.show', compact('audit'));
    }
}