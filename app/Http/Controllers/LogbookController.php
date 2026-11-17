<?php

namespace App\Http\Controllers;

use App\Models\LabLogbook;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LogbookController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'nullable|string|max:50',
            'purpose' => 'required|string|max:500',
        ]);

        LabLogbook::create([
            'name' => $request->name,
            'nim' => $request->nim,
            'purpose' => $request->purpose,
            'entry_at' => Carbon::now(),
        ]);

        return back()->with('logbook_success', 'Absensi berhasil dicatat! Selamat datang di Lab Bisnis Digital.');
    }
}
