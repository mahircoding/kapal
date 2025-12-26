<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Job Application - {{ $application->user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .section {
            margin-bottom: 20px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            background-color: #f0f0f0;
            padding: 8px;
            margin-bottom: 10px;
            border-left: 4px solid #333;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            font-weight: bold;
            width: 40%;
            padding: 5px 10px;
            border-bottom: 1px solid #eee;
        }
        .info-value {
            display: table-cell;
            padding: 5px 10px;
            border-bottom: 1px solid #eee;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 11px;
        }
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-reviewed { background-color: #cfe2ff; color: #084298; }
        .status-accepted { background-color: #d1e7dd; color: #0f5132; }
        .status-rejected { background-color: #f8d7da; color: #842029; }
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <!-- Company Branding Header -->
    <div style="text-align: center; margin-bottom: 10px; padding-bottom: 15px; border-bottom: 3px solid #C97C34;">
        @if(!empty($branding['logo']))
            @php
                $logoPath = storage_path('app/public/' . $branding['logo']);
                if (file_exists($logoPath)) {
                    $imageData = base64_encode(file_get_contents($logoPath));
                    $extension = pathinfo($logoPath, PATHINFO_EXTENSION);
                    $mimeType = $extension === 'png' ? 'image/png' : 'image/jpeg';
                    $logoSrc = 'data:' . $mimeType . ';base64,' . $imageData;
                } else {
                    $logoSrc = null;
                }
            @endphp
            @if(isset($logoSrc))
                <img src="{{ $logoSrc }}" style="max-width: 120px; max-height: 50px; margin-bottom: 10px;" alt="{{ $branding['name'] }}">
            @endif
        @endif
        <div style="font-size: 20px; font-weight: bold; color: #C97C34; margin: 5px 0;">{{ $branding['name'] }}</div>
        @if(!empty($branding['tagline']))
            <div style="font-size: 11px; color: #64748b;">{{ $branding['tagline'] }}</div>
        @endif
    </div>

    <div class="header">
        <h1>JOB APPLICATION</h1>
        <p>{{ $application->position }}</p>
        <p>Application Date: {{ $application->created_at->format('d F Y') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Application Status</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Status</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $application->status }}">
                        {{ ucfirst($application->status) }}
                    </span>
                </div>
            </div>
            @if($application->admin_note)
            <div class="info-row">
                <div class="info-label">Admin Note</div>
                <div class="info-value">{{ $application->admin_note }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">Personal Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Full Name</div>
                <div class="info-value">{{ $application->user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $application->user->email }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">WhatsApp Number</div>
                <div class="info-value">{{ $application->user->whatsapp_number ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Place of Birth</div>
                <div class="info-value">{{ $application->place_of_birth }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date of Birth</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($application->date_of_birth)->format('d F Y') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Gender</div>
                <div class="info-value">{{ $application->gender }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Religion</div>
                <div class="info-value">{{ $application->religion }}</div>
            </div>
            @if($application->npwp)
            <div class="info-row">
                <div class="info-label">NPWP</div>
                <div class="info-value">{{ $application->npwp }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="section">
        <div class="section-title">Address</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">KTP Address</div>
                <div class="info-value">{{ $application->address_ktp }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Domicile Address</div>
                <div class="info-value">{{ $application->domicile }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Position & Interview</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Position Applied For</div>
                <div class="info-value">{{ $application->position }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Available Interview Date</div>
                <div class="info-value">{{ \Carbon\Carbon::parse($application->available_interview_date)->format('d F Y') }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Documents</div>
        
        @php
            $imageDocuments = [
                'ktp_path' => 'KTP (Kartu Tanda Penduduk)',
                'kk_path' => 'Kartu Keluarga (KK)',
                'medical_certificate_path' => 'Medical Certificate',
            ];
            
            $pdfDocuments = [
                'cv_path' => 'CV / Resume',
                'certificate_path' => 'Certificate (COP/COC)',
                'buku_pelaut_path' => 'Buku Pelaut',
                'account_data_path' => 'Account Data',
            ];
        @endphp
        
        {{-- Image Documents - Embed in PDF --}}
        @foreach($imageDocuments as $field => $label)
            @if($application->$field)
                @php
                    $filePath = storage_path('app/public/' . $application->$field);
                    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif']);
                @endphp
                
                <div style="margin-bottom: 20px; page-break-inside: avoid;">
                    <div style="font-weight: bold; font-size: 14px; margin-bottom: 10px; color: #C97C34;">{{ $label }}</div>
                    
                    @if($isImage && file_exists($filePath))
                        @php
                            $imageData = base64_encode(file_get_contents($filePath));
                            $mimeType = $extension === 'png' ? 'image/png' : 'image/jpeg';
                            $imageSrc = 'data:' . $mimeType . ';base64,' . $imageData;
                        @endphp
                        <div style="text-align: center; border: 1px solid #e2e8f0; padding: 10px; background-color: #f8fafc;">
                            <img src="{{ $imageSrc }}" style="max-width: 100%; max-height: 400px; height: auto;" alt="{{ $label }}">
                        </div>
                    @else
                        <div style="padding: 10px; background-color: #f0f0f0; border-left: 4px solid #10b981;">
                            ✓ File uploaded ({{ strtoupper($extension) }})
                        </div>
                    @endif
                </div>
            @endif
        @endforeach
        
        {{-- PDF/Document Files - Text indicator only --}}
        @if(collect($pdfDocuments)->filter(fn($label, $field) => $application->$field)->isNotEmpty())
            <div style="margin-top: 20px;">
                <div style="font-weight: bold; font-size: 14px; margin-bottom: 10px; color: #C97C34;">Other Documents</div>
                <div class="info-grid">
                    @foreach($pdfDocuments as $field => $label)
                        @if($application->$field)
                            <div class="info-row">
                                <div class="info-label">{{ $label }}</div>
                                <div class="info-value">Uploaded (PDF)</div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <div class="footer">
        <p>{{ $branding['name'] }} - Job Application System</p>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>Generated on {{ now()->format('d F Y H:i:s') }}</p>
    </div>
</body>
</html>
