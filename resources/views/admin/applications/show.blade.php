<x-layouts.app title="Application Details">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Applicant Details -->
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <flux:heading size="lg">{{ $application->user->name }}</flux:heading>
                                <flux:subheading>{{ $application->user->email }}</flux:subheading>
                            </div>
                            <div class="flex items-center gap-3">
                                <flux:badge size="lg">{{ $application->position }}</flux:badge>
                                <flux:button variant="outline" size="sm" :href="route('admin.applications.download-pdf', $application)" icon="arrow-down-tray">
                                    Download PDF
                                </flux:button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <flux:label>Place & Date of Birth</flux:label>
                                <div class="font-medium mt-1">{{ $application->place_of_birth }}, {{ \Carbon\Carbon::parse($application->date_of_birth)->format('d M Y') }}</div>
                            </div>
                            <div>
                                <flux:label>Gender</flux:label>
                                <div class="font-medium mt-1">{{ $application->gender }}</div>
                            </div>
                            <div>
                                <flux:label>Religion</flux:label>
                                <div class="font-medium mt-1">{{ $application->religion }}</div>
                            </div>
                            <div>
                                <flux:label>WhatsApp Number</flux:label>
                                <div class="font-medium mt-1">{{ $application->user->whatsapp_number ?? '-' }}</div>
                            </div>
                            <div>
                                <flux:label>NPWP</flux:label>
                                <div class="font-medium mt-1">{{ $application->npwp ?? '-' }}</div>
                            </div>
                            @if(!empty($application->available_interview_date))
                            <div>
                                <flux:label>Available Interview Date</flux:label>
                                <div class="font-medium mt-1">{{ \Carbon\Carbon::parse($application->available_interview_date)->format('d F Y') }}</div>
                            </div>
                            @endif
                            <div class="md:col-span-2">
                                <flux:label>KTP Address</flux:label>
                                <div class="font-medium mt-1">{{ $application->address_ktp }}</div>
                            </div>
                            <div class="md:col-span-2">
                                <flux:label>Domicile Address</flux:label>
                                <div class="font-medium mt-1">{{ $application->domicile }}</div>
                            </div>
                        </div>

                        <flux:separator class="my-6" />

                        <flux:heading size="md" class="mb-4">Documents</flux:heading>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                        @foreach(['ktp', 'kk', 'cv', 'certificate', 'coe', 'medical_certificate', 'buku_pelaut', 'account_data'] as $doc)
                                @if($application->{$doc . '_path'})
                                    <a href="{{ Storage::url($application->{$doc . '_path'}) }}" target="_blank" class="flex items-center gap-3 p-3 border rounded-lg hover:bg-zinc-50 dark:hover:bg-zinc-800 transition">
                                        <flux:icon name="document-text" class="text-zinc-400" />
                                        <div class="flex-1 min-w-0">
                                            <div class="font-medium truncate text-sm uppercase">{{ str_replace('_', ' ', $doc) }}</div>
                                            <div class="text-xs text-zinc-500">View Document</div>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>

                        @if($application->cover_letter)
                            <flux:separator class="my-6" />
                            <flux:heading size="md" class="mb-2">Cover Letter</flux:heading>
                            <div class="prose dark:prose-invert max-w-none text-zinc-600 dark:text-zinc-400">
                                {{ $application->cover_letter }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Sidebar -->
                <div class="space-y-6">
                    <div class="bg-white dark:bg-zinc-900 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <flux:heading size="md" class="mb-4">Internal Review</flux:heading>
                        
                        <form action="{{ route('admin.applications.update', $application) }}" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')

                            <flux:select name="status" label="Status" :value="$application->status">
                                <option value="pending">Pending</option>
                                <option value="reviewed">Reviewed (Needs Revision)</option>
                                <option value="accepted">Accepted</option>
                                <option value="rejected">Rejected</option>
                            </flux:select>

                            <flux:textarea name="admin_note" label="Notes / Feedback" placeholder="Add internal notes or feedback for the applicant..." rows="6">{{ $application->admin_note }}</flux:textarea>

                            <flux:button variant="primary" type="submit" class="w-full">Update Application</flux:button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
