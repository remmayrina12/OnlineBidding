<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\ReportReviewNotification;
use App\Notifications\ReportStatusNotification;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reported_user_id' => 'required|exists:users,id',
            'reason' => 'required|string|max:255',
        ]);

        Report::create([
            'reported_user_id' => $validated['reported_user_id'],
            'reported_by' => Auth::id(),
            'reason' => $validated['reason'],
        ]);

        $adminName = 'admin';
        $report = User::where('role', 'admin')->where('name', $adminName)->first(); // Replace $specificAdminId with the actual ID or condition
        if ($report) {
            $report->notify(new ReportReviewNotification($report));
        }

        return redirect()->back()->with('success', 'Report submitted successfully.');
    }

    public function index()
    {
        $reports = Report::with(['reportedUser', 'reporter'])->get();
        return view('admin.reportIndex', compact('reports'));
    }

    public function updateStatus(Request $request, $id)
    {
        $report = Report::findOrFail($id);
        $report->status = 'reviewed';
        $report->save();

        $report = $report->reporter;
        if ($report) {
            $report->notify(new ReportStatusNotification($report, 'reviewed'));
        }

        return redirect()->back()->with('success', 'Report status updated.');
    }
}
