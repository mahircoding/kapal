<x-layouts.app title="Apply for Job">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="relative mb-6 w-full">
                        <flux:heading size="xl" level="1">{{ __('Kirim Lamaran') }}</flux:heading>
                        <flux:subheading size="lg" class="mb-6">{{ __('Lengkapi profil dan submit dokumen.') }}</flux:subheading>
                        <flux:separator variant="subtle" />
                    </div>

                    <form method="POST" action="{{ route('job-application.review') }}" enctype="multipart/form-data" class="w-full max-w-4xl space-y-8">
                        @csrf

                        <!-- Personal Information -->
                        <div>
                            <flux:heading size="lg" class="mb-4">Informasi Pribadi</flux:heading>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <flux:input name="place_of_birth" label="Tempat Lahir" value="{{ old('place_of_birth') }}" required />
                                <flux:input type="date" name="date_of_birth" label="Tanggal Lahir" value="{{ old('date_of_birth') }}" required />
                                
                                <flux:select name="gender" label="Jenis Kelamin" placeholder="Select Jenis Kelamin" required>
                                    <option value="Laki-laki" {{ old('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </flux:select>

                                <flux:select name="religion" label="Agama" placeholder="Select Agama" required>
                                    <option value="Islam" {{ old('religion') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                    <option value="Kristen" {{ old('religion') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                    <option value="Hindu" {{ old('religion') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                    <option value="Budha" {{ old('religion') == 'Budha' ? 'selected' : '' }}>Budha</option>
                                    <option value="Konghucu" {{ old('religion') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    <option value="Lainnya" {{ old('religion') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </flux:select>
                            </div>
                        </div>

                        <flux:separator variant="subtle" />


                        <!-- Tax Information -->         
                        <div class="mb-4 mt-4">
                            <flux:heading size="lg" class="mb-4 mt-4">Informasi Pajak</flux:heading>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- NPWP Upload -->
                                <div>
                                    <flux:label>NPWP (Upload Foto)</flux:label>
                                    <input type="file" name="npwp" accept="image/*,application/pdf" class="mt-2 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                    @error('npwp')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                    <p class="text-sm text-zinc-500 mt-2">Upload foto NPWP (JPG, PNG, atau PDF)</p>
                                </div>
                            </div>
                        </div>

                        <flux:separator variant="subtle" />

                        <!-- Address -->
                        <div>
                            <flux:heading size="lg" class="mb-4 mt-4">Alamat</flux:heading>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <flux:textarea name="address_ktp" label="Alamat KTP" required>{{ old('address_ktp') }}</flux:textarea>
                                <flux:textarea name="domicile" label="Domisili" required>{{ old('domicile') }}</flux:textarea>
                            </div>
                        </div>

                        <flux:separator variant="subtle" />

                        <!-- Job Details -->
                        <div>
                            <flux:heading size="lg" class="mb-4 mt-4">Posisi</flux:heading>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <flux:select name="position" label="Posisi yang Dilamar" placeholder="Select Position" required>
                                    <option value="Master" {{ old('position') == 'Master' ? 'selected' : '' }}>Master</option>
                                    <option value="Chief Officer" {{ old('position') == 'Chief Officer' ? 'selected' : '' }}>Chief Officer</option>
                                    <option value="Third Officer" {{ old('position') == 'Third Officer' ? 'selected' : '' }}>Third Officer</option>
                                    <option value="Bosun" {{ old('position') == 'Bosun' ? 'selected' : '' }}>Bosun</option>
                                    <option value="Chief Engineer" {{ old('position') == 'Chief Engineer' ? 'selected' : '' }}>Chief Engineer</option>
                                    <option value="Second Engineer" {{ old('position') == 'Second Engineer' ? 'selected' : '' }}>Second Engineer</option>
                                    <option value="Third Engineer" {{ old('position') == 'Third Engineer' ? 'selected' : '' }}>Third Engineer</option>
                                    <option value="Able / Ratings" {{ old('position') == 'Able / Ratings' ? 'selected' : '' }}>Able / Ratings</option>
                                    <option value="Oiler" {{ old('position') == 'Oiler' ? 'selected' : '' }}>Oiler</option>
                                    <option value="Cook" {{ old('position') == 'Cook' ? 'selected' : '' }}>Cook</option>
                                    <option value="Cadet" {{ old('position') == 'Cadet' ? 'selected' : '' }}>Cadet</option>
                                </flux:select>
                                
                                <flux:input type="date" name="available_interview_date" label="Tanggal Waktu Wawancara yang Tersedia" value="{{ old('available_interview_date') }}" required />
                            </div>
                        </div>

                        <flux:separator variant="subtle" />

                        <!-- Documents -->
                        <div class="mb-4 mt-4">
                            <flux:heading size="lg" class="mb-4 mt-4">Dokumen</flux:heading>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <flux:input type="file" name="ktp" label="KTP Scan" :required="!isset($uploadedFiles['ktp_path'])" />
                                    @if(isset($uploadedFiles['ktp_path']))
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ File uploaded: {{ basename($uploadedFiles['ktp_path']) }}
                                        </p>
                                    @endif
                                    @error('ktp')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <flux:input type="file" name="kk" label="Kartu Keluarga (KK) Scan" :required="!isset($uploadedFiles['kk_path'])" />
                                    @if(isset($uploadedFiles['kk_path']))
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ File uploaded: {{ basename($uploadedFiles['kk_path']) }}
                                        </p>
                                    @endif
                                    @error('kk')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <flux:input type="file" name="cv" label="CV / Resume" :required="!isset($uploadedFiles['cv_path'])" />
                                    @if(isset($uploadedFiles['cv_path']))
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ File uploaded: {{ basename($uploadedFiles['cv_path']) }}
                                        </p>
                                    @endif
                                    @error('cv')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <flux:input type="file" name="certificate" label="(COC) Certificate of Competency" />
                                    @if(isset($uploadedFiles['certificate_path']))
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ File uploaded: {{ basename($uploadedFiles['certificate_path']) }}
                                        </p>
                                    @endif
                                    @error('certificate')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <flux:input type="file" name="coe" label="COE (Certificate of Endorsement)" />
                                    @if(isset($uploadedFiles['coe_path']))
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ File uploaded: {{ basename($uploadedFiles['coe_path']) }}
                                        </p>
                                    @endif
                                    @error('coe')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <flux:input type="file" name="medical_certificate" label="Medical Certificate" />
                                    @if(isset($uploadedFiles['medical_certificate_path']))
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ File uploaded: {{ basename($uploadedFiles['medical_certificate_path']) }}
                                        </p>
                                    @endif
                                    @error('medical_certificate')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <flux:input type="file" name="buku_pelaut" label="Buku Pelaut (Seaman's Book)" />
                                    @if(isset($uploadedFiles['buku_pelaut_path']))
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ File uploaded: {{ basename($uploadedFiles['buku_pelaut_path']) }}
                                        </p>
                                    @endif
                                    @error('buku_pelaut')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                </div>
                                
                                <div>
                                    <flux:input type="file" name="account_data" label="Account Bank" />
                                    @if(isset($uploadedFiles['account_data_path']))
                                        <p class="text-sm text-green-600 dark:text-green-400 mt-1">
                                            ✓ File uploaded: {{ basename($uploadedFiles['account_data_path']) }}
                                        </p>
                                    @endif
                                    @error('account_data')
                                        <span class="text-sm text-red-600 dark:text-red-400 mt-1">{{ $message }}</span>
                                    @enderror
                                    <p class="text-sm text-zinc-500 mt-2">Upload screenshot Buku Rekening BCA</p>
                                </div>
                            </div>
                        </div>

                        <flux:separator variant="subtle" />

                         <!-- Additional Info -->
                        <!-- <div>
                            <flux:textarea name="cover_letter" label="Cover Letter (Optional)" placeholder="Tell us why you are a great fit..." rows="4" />
                        </div> -->

                        <div class="flex justify-end pt-6 mt-4">
                            <flux:button variant="primary" type="submit" class="w-full md:w-auto">{{ __('Kirim Lamaran') }}</flux:button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
