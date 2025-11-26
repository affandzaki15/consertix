<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // tampilkan filter form; implementasi query sesuai kebutuhan
        return view('admin.reports.index');
    }

    public function export(Request $request)
    {
        // stub: export CSV/Excel
        return back()->with('success', 'Export dijalankan (stub).');
    }
}