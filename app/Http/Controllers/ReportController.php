<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $logs = SystemLog::with('user')->latest()->paginate(15);
        
        return view('reports.index', compact('logs'));
    }
}