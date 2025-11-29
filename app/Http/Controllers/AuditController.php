<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Audit;

class AuditController extends Controller
{
    public function index()
    {
        $logs = Audit::latest()->paginate(10);
        
        return view('audits.index', compact('logs'));
    }
}
