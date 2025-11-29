<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Exports\LogsExport;

class ExportController extends Controller
{
    public function exportLogs()
    {
        return Excel::download(new LogsExport, 'auditorias.xlsx');
    }

}
