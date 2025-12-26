<x-layouts.app title="Edit Application">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="relative mb-6 w-full">
                        <flux:heading size="xl" level="1">{{ __('Edit Your Application') }}</flux:heading>
                        <flux:subheading size="lg" class="mb-6">{{ __('Update your information based on the feedback below.') }}</flux:subheading>
                        
                        @if($application->admin_note)
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <flux:heading size="md" class="text-blue-800 dark:text-blue-200 mb-2">Feedback from HRD:</flux:heading>
                            <p class="text-blue-700 dark:text-blue-300">{{ $application->admin_note }}</p>
                        </div>
                        @endif
                        
                        <flux:separator variant="subtle" />
                    </div>

                    <form method="POST" action="{{ route('job-application.update') }}" enctype="multipart/form-data" class="w-full max-w-4xl space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- Personal Information -->
                        <div>
                            <flux:heading size="lg" class="mb-4">Personal Information</flux:heading>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <flux:input name="place_of_birth" label="Place of Birth" value="{{ old('place_of_birth', $application->place_of_birth) }}" required />
                                <flux:input type="date" name="date_of_birth" label="Date of Birth" value="{{ old('date_of_birth', $application->date_of_birth) }}" required />
                                
                                <flux:select name="gender" label="Gender" placeholder="Select Gender" required>
                                    <option value="Laki-laki" {{ old('gender', $application->gender) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('gender', $application->gender) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </flux:select>

                                <flux:select name="religion" label="Religion" placeholder="Select Religion" required>
                                    <option value="Islam" {{ old('religion', $application->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ old('religion', $application->religion) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="Hindu" {{ old('religion', $application->religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Budha" {{ old('religion', $application->religion) == 'Budha' ? 'selected' : '' }}>Budha</option>
                                    <option value="Konghucu" {{ old('religion', $application->religion) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    <option value="Lainnya" {{ old('religion', $application->religion) == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </flux:select>
                            </div>
                        </div>

                        <flux:separator variant="subtle" />


                        <!-- Tax Information -->
                        <div class="mb-4 mt-4">
                            <flux:heading size="lg" class="mb-4 mt-4">Tax Information</flux:heading>
                            <flux:input name="npwp" label="NPWP (Nomor Pokok Wajib Pajak)" placeholder="e.g., 12.345.678.9-012.000" value="{{ old('npwp', $application->npwp) }}" />
                            <p class="text-sm text-zinc-500 mt-2">Optional - Enter your tax identification number if available</p>
                        </div>

                        <flux:separator variant="subtle" />

                        <!-- Address -->
                        <div>
                            <flux:heading size="lg" class="mb-4 mt-4">Address</flux:heading>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <flux:textarea name="address_ktp" label="Address (KTP)" required>{{ old('address_ktp', $application->address_ktp) }}</flux:textarea>
                                <flux:textarea name="domicile" label="Current Domicile" required>{{ old('domicile', $application->domicile) }}</flux:textarea>
                            </div>
                        </div>

                        <flux:separator variant="subtle" />

                        <!-- Job Details -->
                        <div>
                            <flux:heading size="lg" class="mb-4 mt-4">Position</flux:heading>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <flux:select name="position" label="Position Applied For" placeholder="Select Position" required>
                                    <option value="Master" {{ old('position', $application->position) == 'Master' ? 'selected' : '' }}>Master</option>
                                    <option value="Chief Officer" {{ old('position', $application->position) == 'Chief Officer' ? 'selected' : '' }}>Chief Officer</option>
                                    <option value="Able Seaman" {{ old('position', $application->position) == 'Able Seaman' ? 'selected' : '' }}>Able Seaman</option>
                                    <option value="Cook" {{ old('position', $application->position) == 'Cook' ? 'selected' : '' }}>Cook</option>
                                </flux:select>
                                
                                <flux:input type="date" name="available_interview_date" label="Available Interview Date" value="{{ old('available_interview_date', $application->available_interview_date) }}" required />
                            </div>
                        </div>

                        <flux:separator variant="subtle" />

                        <!-- Documents -->
                        <div class="mb-4 mt-4">
                            <flux:heading size="lg" class="mb-4 mt-4">Documents</flux:heading>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <flux:input type="file" name="ktp" label="KTP Scan" />
                                    @if($application->ktp_path)
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ Current file: <a href="{{ Storage::url($application->ktp_path) }}" target="_blank" class="underline">{{ basename($application->ktp_path) }}</a>
                                        </p>
                                    @endif
                                    @error('ktp')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <flux:input type="file" name="kk" label="Kartu Keluarga (KK) Scan" />
                                    @if($application->kk_path)
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ Current file: <a href="{{ Storage::url($application->kk_path) }}" target="_blank" class="underline">{{ basename($application->kk_path) }}</a>
                                        </p>
                                    @endif
                                    @error('kk')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <flux:input type="file" name="cv" label="CV / Resume" />
                                    @if($application->cv_path)
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ Current file: <a href="{{ Storage::url($application->cv_path) }}" target="_blank" class="underline">{{ basename($application->cv_path) }}</a>
                                        </p>
                                    @endif
                                    @error('cv')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <flux:input type="file" name="certificate" label="Certificate (COP/COC)" />
                                    @if($application->certificate_path)
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ Current file: <a href="{{ Storage::url($application->certificate_path) }}" target="_blank" class="underline">{{ basename($application->certificate_path) }}</a>
                                        </p>
                                    @endif
                                    @error('certificate')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <flux:input type="file" name="medical_certificate" label="Medical Certificate" />
                                    @if($application->medical_certificate_path)
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ Current file: <a href="{{ Storage::url($application->medical_certificate_path) }}" target="_blank" class="underline">{{ basename($application->medical_certificate_path) }}</a>
                                        </p>
                                    @endif
                                    @error('medical_certificate')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <flux:input type="file" name="buku_pelaut" label="Buku Pelaut (Seaman's Book)" />
                                    @if($application->buku_pelaut_path)
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ Current file: <a href="{{ Storage::url($application->buku_pelaut_path) }}" target="_blank" class="underline">{{ basename($application->buku_pelaut_path) }}</a>
                                        </p>
                                    @endif
                                    @error('buku_pelaut')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <flux:input type="file" name="account_data" label="Account Data" />
                                    @if($application->account_data_path)
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ Current file: <a href="{{ Storage::url($application->account_data_path) }}" target="_blank" class="underline">{{ basename($application->account_data_path) }}</a>
                                        </p>
                                    @endif
                                    @error('account_data')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                    <p class="text-sm text-zinc-500 mt-2">Upload bank book or digital wallet screenshot (e.g., Dana, GoPay)</p>
                                </div>
                            </div>
                        </div>

                        <flux:separator variant="subtle" />

                         <!-- Additional Info -->
                        <!-- <div>
                            <flux:textarea name="cover_letter" label="Cover Letter (Optional)" placeholder="Tell us why you are a great fit..." rows="4" />
                        </div> -->

                        <div class="flex justify-end pt-6 mt-4">
                            <flux:button variant="primary" type="submit" class="w-full md:w-auto">{{ __('Update Application') }}</flux:button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
