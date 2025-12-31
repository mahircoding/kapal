<x-layouts.app title="Lamaran Saya">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="relative mb-6 w-full">
                        <flux:heading size="xl" level="1">{{ __('Status Lamaran Saya') }}</flux:heading>
                        <flux:subheading size="lg" class="mb-6">{{ __('Pantau perkembangan lamaran.') }}</flux:subheading>
                        <flux:separator variant="subtle" />
                    </div>

                    <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-6 border border-zinc-200 dark:border-zinc-700">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-semibold">{{ $application->position }}</h3>
                                <p class="text-sm text-gray-500">Lamaran masuk {{ $application->created_at->format('d M Y') }}</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($application->status === 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($application->status === 'reviewed') bg-blue-100 text-blue-800
                                    @elseif($application->status === 'accepted') bg-green-100 text-green-800
                                    @elseif($application->status === 'rejected') bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($application->status) }}
                                </span>
                                
                                <flux:button variant="outline" size="sm" :href="route('job-application.download-pdf')" icon="arrow-down-tray">
                                    Download PDF
                                </flux:button>
                                
                                {{-- <flux:button variant="outline" size="sm" onclick="window.print()" icon="printer">
                                    Print
                                </flux:button> --}}
                            </div>
                        </div>

                        @if($application->admin_note)
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <flux:heading size="md" class="text-blue-800 dark:text-blue-200 mb-2">Feedback from HRD:</flux:heading>
                                    <p class="text-blue-700 dark:text-blue-300">{{ $application->admin_note }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($application->status === 'reviewed')
                        <div class="mb-6">
                            <flux:button variant="primary" :href="route('job-application.edit')" wire:navigate>
                                Edit Application
                            </flux:button>
                        </div>
                        @endif

                        @if($application->cover_letter)
                        <flux:separator variant="subtle" />
                        <div>
                            <flux:heading size="lg" class="mb-4">Cover Letter</flux:heading>
                            <div class="text-sm whitespace-pre-wrap">{{ $application->cover_letter }}</div>
                        </div>
                        @endif

                        @if(!empty($application->available_interview_date))
                        <flux:separator variant="subtle" />
                        <div>
                            <flux:heading size="lg" class="mb-4">Interview Availability</flux:heading>
                            <div>
                                <flux:label>Available Interview Date</flux:label>
                                <div class="text-sm font-medium">{{ \Carbon\Carbon::parse($application->available_interview_date)->format('d F Y') }}</div>
                            </div>
                        </div>
                        @endif

                        <flux:separator variant="subtle" class="my-4"/>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium mb-2 mt-2">Informasi Pribadi</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><strong>Name:</strong> {{ auth()->user()->name }}</li>
                                    <li><strong>Email:</strong> {{ auth()->user()->email }}</li>
                                    <li><strong>Tempat Lahir:</strong> {{ $application->place_of_birth }}</li>
                                    <li><strong>Tanggal Lahir:</strong> {{ $application->date_of_birth }}</li>
                                    <li><strong>Jenis Kelamin:</strong> {{ $application->gender }}</li>
                                    <li><strong>Agama:</strong> {{ $application->religion }}</li>
                                    @if($application->npwp)
                                    <li><strong>NPWP:</strong> {{ $application->npwp }}</li>
                                    @endif
                                    @if(!empty($application->available_interview_date))
                                    <li><strong>Available Interview Date:</strong> {{ \Carbon\Carbon::parse($application->available_interview_date)->format('d M Y') }}</li>
                                    @endif
                                </ul>
                            </div>
                            <div>
                                <h4 class="font-medium mb-2 mt-2">Alamat</h4>
                                <ul class="space-y-2 text-sm">
                                    <li><strong>KTP:</strong> {{ $application->address_ktp }}</li>
                                    <li><strong>Domisili:</strong> {{ $application->domicile }}</li>
                                </ul>
                            </div>
                        </div>

                        <flux:separator variant="subtle" class="my-4 mt-2"/>
                        
                             <div>
                                 <h4 class="font-medium mb-2 mt-2">Documents</h4>
                                 <div class="flex gap-4 flex-wrap">
                                @if($application->cv_path)
                                <a href="{{ Storage::url($application->cv_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm flex items-center gap-1">
                                    <flux:icon name="document" size="sm" /> CV
                                </a>
                                @endif
                                @if($application->ktp_path)
                                <a href="{{ Storage::url($application->ktp_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm flex items-center gap-1">
                                    <flux:icon name="identification" size="sm" /> KTP
                                </a>
                                @endif
                                @if($application->kk_path)
                                <a href="{{ Storage::url($application->kk_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm flex items-center gap-1">
                                    <flux:icon name="users" size="sm" /> KK
                                </a>
                                @endif
                                @if($application->certificate_path)
                                <a href="{{ Storage::url($application->certificate_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm flex items-center gap-1">
                                    <flux:icon name="document-check" size="sm" /> COC (Certificate of Competency)
                                </a>
                                @endif
                                @if($application->coe_path)
                                <a href="{{ Storage::url($application->coe_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm flex items-center gap-1">
                                    <flux:icon name="document-check" size="sm" /> COE (Certificate of Endorsement)
                                </a>
                                @endif
                                @if($application->medical_certificate_path)
                                <a href="{{ Storage::url($application->medical_certificate_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm flex items-center gap-1">
                                    <flux:icon name="heart" size="sm" /> Medical
                                </a>
                                @endif
                                @if($application->buku_pelaut_path)
                                <a href="{{ Storage::url($application->buku_pelaut_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm flex items-center gap-1">
                                    <flux:icon name="document-text" size="sm" /> Buku Pelaut
                                </a>
                                @endif
                                @if($application->account_data_path)
                                <a href="{{ Storage::url($application->account_data_path) }}" target="_blank" class="text-blue-600 hover:underline text-sm flex items-center gap-1">
                                    <flux:icon name="credit-card" size="sm" /> Account Bank
                                </a>
                                @endif
                                 </div>
                            </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
