<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\LabDocument;
use App\Models\LabLogbook;
use App\Models\Laboratory;
use Carbon\Carbon;

class PublicDashboardController extends Controller
{
    public function index()
    {
        $laboratory = Laboratory::where('is_active', true)->first();

        $schedules = Booking::with(['user', 'laboratory'])
            ->where('end_time', '>=', Carbon::now())
            ->whereIn('status', ['approved', 'approved_dosen', 'approved_laboran', 'completed'])
            ->orderBy('start_time', 'asc')
            ->get();

        $todayLogbooks = LabLogbook::whereDate('entry_at', Carbon::today())
            ->orderByDesc('entry_at')
            ->get();

        $documents = LabDocument::where('is_active', true)->latest()->get();

        return view('welcome', compact('laboratory', 'schedules', 'todayLogbooks', 'documents'));
    }
}
