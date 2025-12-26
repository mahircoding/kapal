<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use Illuminate\Http\Request;

class AdminApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = JobApplication::with('user');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $applications = $query->latest()->paginate(10);

        return view('admin.applications.index', compact('applications'));
    }

    public function show(JobApplication $application)
    {
        $application->load('user');
        return view('admin.applications.show', compact('application'));
    }

    public function update(Request $request, JobApplication $application)
    {
        $request->validate([
            'status' => 'required|in:pending,reviewed,accepted,rejected',
            'admin_note' => 'nullable|string',
        ]);

        // Store old values before update
        $oldStatus = $application->status;
        $oldAdminNote = $application->admin_note;

        // Update the application
        $application->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        // Dispatch event if status changed OR admin note was added/updated
        $statusChanged = $oldStatus !== $request->status;
        $noteChanged = $oldAdminNote !== $request->admin_note && !empty($request->admin_note);

        if ($statusChanged || $noteChanged) {
            event(new \App\Events\ApplicationStatusChanged(
                $application->load('user'),
                $oldStatus,
                $request->status,
                $request->admin_note
            ));
        }

        return back()->with('success', 'Application updated successfully.');
    }

    public function downloadPdf(JobApplication $application)
    {
        $application->load('user');
        
        // Get branding settings
        $settings = \App\Models\Settings::pluck('value', 'key');
        $branding = [
            'name' => $settings->get('site_name', config('app.name')),
            'tagline' => $settings->get('site_tagline', ''),
            'logo' => $settings->get('site_logo', ''),
        ];
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('job-application.pdf', compact('application', 'branding'));
        
        return $pdf->download('job-application-' . $application->user->name . '.pdf');
    }
}

