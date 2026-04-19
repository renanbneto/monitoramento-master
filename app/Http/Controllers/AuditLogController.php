<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::orderByDesc('created_at');

        if ($request->filled('acao')) {
            $query->where('acao', $request->acao);
        }
        if ($request->filled('user_rg')) {
            $query->where('user_rg', 'like', '%' . $request->user_rg . '%');
        }
        if ($request->filled('de')) {
            $query->whereDate('created_at', '>=', $request->de);
        }
        if ($request->filled('ate')) {
            $query->whereDate('created_at', '<=', $request->ate);
        }

        $logs   = $query->paginate(50)->withQueryString();
        $acoes  = AuditLog::select('acao')->distinct()->orderBy('acao')->pluck('acao');

        return view('auditoria.lgpd', compact('logs', 'acoes'));
    }
}
