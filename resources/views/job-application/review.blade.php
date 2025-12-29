<x-layouts.app title="Review Application">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="relative mb-6 w-full">
                        <flux:heading size="xl" level="1">{{ __('Review Your Application') }}</flux:heading>
                        <flux:subheading size="lg" class="mb-6">{{ __('Please review your details before submitting.') }}</flux:subheading>
                        <flux:separator variant="subtle" />
                    </div>

                    <div class="space-y-6">
                        <!-- Personal Info -->
                        <div>
                            <flux:heading size="lg" class="mb-4">Personal Information</flux:heading>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <flux:label>Place of Birth</flux:label>
                                    <div class="text-sm font-medium">{{ $data['place_of_birth'] }}</div>
                                </div>
                                <div>
                                    <flux:label>Date of Birth</flux:label>
                                    <div class="text-sm font-medium">{{ $data['date_of_birth'] }}</div>
                                </div>
                                <div>
                                    <flux:label>Gender</flux:label>
                                    <div class="text-sm font-medium">{{ $data['gender'] }}</div>
                                </div>
                                <div>
                                    <flux:label>Religion</flux:label>
                                    <div class="text-sm font-medium">{{ $data['religion'] }}</div>
                                </div>
                                @if(!empty($data['npwp']))
                                <div>
                                    <flux:label>NPWP</flux:label>
                                    <div class="text-sm font-medium">{{ $data['npwp'] }}</div>
                                </div>
                                @endif
                            </div>
                        </div>

                        <flux:separator variant="subtle" />

                        <!-- Address -->
                        <div>
                            <flux:heading size="lg" class="mb-4">Address</flux:heading>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <flux:label>Address (KTP)</flux:label>
                                    <div class="text-sm font-medium">{{ $data['address_ktp'] }}</div>
                                </div>
                                <div>
                                    <flux:label>Domicile</flux:label>
                                    <div class="text-sm font-medium">{{ $data['domicile'] }}</div>
                                </div>
                            </div>
                        </div>

                        <flux:separator variant="subtle" />

                        <!-- Position -->
                         <div>
                            <flux:heading size="lg" class="mb-4">Position</flux:heading>
                            <div>
                                <flux:label>Position Applied For</flux:label>
                                <div class="text-sm font-medium">{{ $data['position'] }}</div>
                            </div>
                        </div>

                        <flux:separator variant="subtle" />

                        <!-- Documents -->
                         <div>
                            <flux:heading size="lg" class="mb-4 mt-4">Documents</flux:heading>
                            <ul class="list-disc list-inside text-sm">
                                <li>KTP: {{ ($data['ktp_path'] ?? false) ? 'Uploaded' : 'Pending' }}</li>
                                <li>KK: {{ ($data['kk_path'] ?? false) ? 'Uploaded' : 'Pending' }}</li>
                                <li>CV: {{ ($data['cv_path'] ?? false) ? 'Uploaded' : 'Pending' }}</li>
                                <li>Certificate: {{ ($data['certificate_path'] ?? false) ? 'Uploaded' : 'Not provided' }}</li>
                                <li>Medical Certificate: {{ ($data['medical_certificate_path'] ?? false) ? 'Uploaded' : 'Not provided' }}</li>
                                <li>Buku Pelaut: {{ ($data['buku_pelaut_path'] ?? false) ? 'Uploaded' : 'Not provided' }}</li>
                                <li>Account Bank: {{ ($data['account_data_path'] ?? false) ? 'Uploaded' : 'Not provided' }}</li>
                            </ul>
                        </div>

                        @if(!empty($data['cover_letter']))
                        <flux:separator variant="subtle" />
                        <div>
                            <flux:heading size="lg" class="mb-4">Cover Letter</flux:heading>
                            <div class="text-sm">{{ $data['cover_letter'] }}</div>
                        </div>
                        @endif

                        @if(!empty($data['available_interview_date']))
                        <flux:separator variant="subtle" />
                        <div>
                            <flux:heading size="lg" class="mb-4">Interview Availability</flux:heading>
                            <div>
                                <flux:label>Available Interview Date</flux:label>
                                <div class="text-sm font-medium">{{ \Carbon\Carbon::parse($data['available_interview_date'])->format('d F Y') }}</div>
                            </div>
                        </div>
                        @endif

                    </div>

                    <form method="POST" action="{{ route('job-application.store') }}" class="mt-8 flex justify-end gap-4">
                        @csrf
                        <!-- Hidden inputs for non-file data to be re-sent if strictly needed, 
                             BUT controller retrieves from session/request merge. 
                             Actually controller logic I wrote relies on session for files but Request for text data?
                             Wait, store method uses: 
                             $request->place_of_birth ...
                             So I MUST pass these values again in hidden fields OR store them in session too.
                             The controller logic was:
                             $request->user()->jobApplication()->create(array_merge([ ... $request->place_of_birth ... ], $files));
                             
                             So yes, I need hidden inputs for the text data.
                        -->
                        <input type="hidden" name="place_of_birth" value="{{ $data['place_of_birth'] }}">
                        <input type="hidden" name="date_of_birth" value="{{ $data['date_of_birth'] }}">
                        <input type="hidden" name="gender" value="{{ $data['gender'] }}">
                        <input type="hidden" name="religion" value="{{ $data['religion'] }}">
                        <input type="hidden" name="npwp" value="{{ $data['npwp'] ?? '' }}">
                        <input type="hidden" name="address_ktp" value="{{ $data['address_ktp'] }}">
                        <input type="hidden" name="domicile" value="{{ $data['domicile'] }}">
                        <input type="hidden" name="position" value="{{ $data['position'] }}">
                        <input type="hidden" name="cover_letter" value="{{ $data['cover_letter'] ?? '' }}">
                        <input type="hidden" name="available_interview_date" value="{{ $data['available_interview_date'] ?? '' }}">

                        <flux:button href="{{ route('job-application.create') }}" variant="subtle" wire:navigate>Edit</flux:button>
                        <flux:button variant="primary" type="submit">{{ __('Confirm & Submit') }}</flux:button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
