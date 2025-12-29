<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobApplicationController extends Controller
{
    public function create()
    {
        // If user already has an application, redirect to show page
        if (auth()->user()->jobApplication()->exists()) {
            return redirect()->route('job-application.show');
        }
        
        $uploadedFiles = session('job_application_files', []);
        return view('job-application.create', compact('uploadedFiles'));
    }

    public function review(Request $request)
    {
        $data = $request->validate([
            'place_of_birth' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'religion' => 'required|in:Islam,Kristen,Hindu,Budha,Konghucu,Lainnya',
            'npwp' => 'nullable|string|max:20',
            'address_ktp' => 'required|string',
            'domicile' => 'required|string',
            'ktp' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'kk' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'certificate' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
            'buku_pelaut' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'account_data' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'position' => 'required|in:Master,Chief Officer,Able Seaman,Cook',
            'cover_letter' => 'nullable|string',
            'available_interview_date' => 'required|date',
        ]);

        $files = ['ktp', 'kk', 'cv', 'certificate', 'medical_certificate', 'buku_pelaut', 'account_data'];
        $paths = session('job_application_files', []);

        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                $paths[$file . '_path'] = $request->file($file)->store('documents/' . $file . 's', 'public');
            }
        }
        
        // Store files in session for persistence across preview steps
        session(['job_application_files' => $paths]);

        // Merge file paths into data for preview
        $previewData = array_merge($data, $paths);
        
        return view('job-application.review', ['data' => $previewData]);
    }

    public function store(Request $request)
    {
        // Re-validate strictly necessary data being present either in request or session
        $files = session('job_application_files', []);
        
        if (empty($files['ktp_path']) || empty($files['kk_path']) || empty($files['cv_path'])) {
             return redirect()->route('job-application.create')->withErrors(['cv' => 'Required documents are missing. Please upload them again.']);
        }

        $request->user()->jobApplication()->create(array_merge([
            'place_of_birth' => $request->place_of_birth,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'religion' => $request->religion,
            'npwp' => $request->npwp,
            'address_ktp' => $request->address_ktp,
            'domicile' => $request->domicile,
            'position' => $request->position,
            'cover_letter' => $request->cover_letter,
            'available_interview_date' => $request->available_interview_date,
        ], $files));

        session()->forget('job_application_files');

        return redirect()->route('job-application.show')->with('success', 'Application submitted successfully!');
    }

    public function show()
    {
        $application = auth()->user()->jobApplication;
        if (!$application) {
            return redirect()->route('job-application.create');
        }
        return view('job-application.show', compact('application'));
    }

    public function downloadPdf()
    {
        $application = auth()->user()->jobApplication;
        
        if (!$application) {
            return redirect()->route('job-application.create');
        }

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

    public function edit()
    {
        $application = auth()->user()->jobApplication;
        
        if (!$application) {
            return redirect()->route('job-application.create');
        }

        // Only allow editing if status is 'reviewed' (needs revision)
        if ($application->status !== 'reviewed') {
            return redirect()->route('job-application.show')
                ->with('error', 'You can only edit your application when it needs revision.');
        }

        return view('job-application.edit', compact('application'));
    }

    public function update(Request $request)
    {
        $application = auth()->user()->jobApplication;

        if (!$application || $application->status !== 'reviewed') {
            return redirect()->route('job-application.show')
                ->with('error', 'You cannot update this application.');
        }

        $data = $request->validate([
            'place_of_birth' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'religion' => 'required|in:Islam,Kristen,Hindu,Budha,Konghucu,Lainnya',
            'npwp' => 'nullable|string|max:20',
            'address_ktp' => 'required|string',
            'domicile' => 'required|string',
            'ktp' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'kk' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'cv' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'certificate' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
            'medical_certificate' => 'nullable|file|mimes:pdf,jpeg,png|max:2048',
            'buku_pelaut' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'account_data' => 'nullable|file|mimes:jpeg,png,pdf|max:2048',
            'position' => 'required|in:Master,Chief Officer,Able Seaman,Cook',
            'cover_letter' => 'nullable|string',
            'available_interview_date' => 'required|date',
        ]);

        // Handle file uploads - keep old files if not replaced
        $files = ['ktp', 'kk', 'cv', 'certificate', 'coe', 'medical_certificate', 'buku_pelaut', 'account_data'];
        $filePaths = [];

        foreach ($files as $file) {
            if ($request->hasFile($file)) {
                // Delete old file if exists
                if ($application->{$file . '_path'}) {
                    \Storage::disk('public')->delete($application->{$file . '_path'});
                }
                $filePaths[$file . '_path'] = $request->file($file)->store('documents/' . $file . 's', 'public');
            }
        }

        // Update application
        $application->update(array_merge([
            'place_of_birth' => $data['place_of_birth'],
            'date_of_birth' => $data['date_of_birth'],
            'gender' => $data['gender'],
            'religion' => $data['religion'],
            'npwp' => $data['npwp'],
            'address_ktp' => $data['address_ktp'],
            'domicile' => $data['domicile'],
            'position' => $data['position'],
            'cover_letter' => $data['cover_letter'],
            'available_interview_date' => $data['available_interview_date'],
            'status' => 'pending', // Reset to pending for re-review
            'admin_note' => null, // Clear admin note
        ], $filePaths));

        return redirect()->route('job-application.show')
            ->with('success', 'Application updated successfully! Your application will be reviewed again.');
    }
}
