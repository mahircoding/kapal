<x-layouts.app title="Job Applications">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <flux:heading size="xl" level="1">{{ __('Lamaran Pekerjaan') }}</flux:heading>
                            <flux:subheading size="lg">{{ __('Kelola dan tinjau pengajuan lamaran') }}</flux:subheading>
                        </div>
                    </div>

                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.applications.index') }}" class="mb-6 flex gap-4 max-w-4xl">
                        <flux:input icon="magnifying-glass" type="search" name="search" placeholder="Cari berdasarkan nama atau email..." value="{{ request('search') }}" class="w-full" />
                        
                        <div class="w-48">
                            <flux:select placeholder="Status" name="status" :value="request('status', 'all')">
                                <option value="all">Semua Status</option>
                                <option value="pending">Pending</option>
                                <option value="reviewed">Reviewed</option>
                                <option value="accepted">Accepted</option>
                                <option value="rejected">Rejected</option>
                            </flux:select>
                        </div>
                        
                        <div class="w-48">
                            <flux:select placeholder="Posisi" name="position" :value="request('position', 'all')">
                                <option value="all">Semua Posisi</option>
                                <option value="Master">Master</option>
                                <option value="Chief Officer">Chief Officer</option>
                                <option value="Third Officer">Third Officer</option>
                                <option value="Bosun">Bosun</option>
                                <option value="Chief Engineer">Chief Engineer</option>
                                <option value="Second Engineer">Second Engineer</option>
                                <option value="Third Engineer">Third Engineer</option>
                                <option value="Able / Ratings">Able / Ratings</option>
                                <option value="Oiler">Oiler</option>
                                <option value="Cook">Cook</option>
                                <option value="Cadet">Cadet</option>
                            </flux:select>
                        </div>
                        
                        <flux:button type="submit" variant="primary">Filter</flux:button>
                    </form>

                    <flux:separator variant="subtle" class="mb-6" />

                    <!-- Table -->
                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-zinc-800 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Pelamar</th>
                                    <th scope="col" class="px-6 py-3">Posisi</th>
                                    <th scope="col" class="px-6 py-3">Tanggal</th>
                                    <th scope="col" class="px-6 py-3">Status</th>
                                    <th scope="col" class="px-6 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($applications as $application)
                                    <tr class="bg-white border-b dark:bg-zinc-900 dark:border-zinc-700">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900 dark:text-white">{{ $application->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $application->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4">{{ $application->position }}</td>
                                        <td class="px-6 py-4">{{ $application->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4">
                                            @php
                                                $badgeClasses = match($application->status) {
                                                    'accepted' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
                                                    'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
                                                    'reviewed' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
                                                    default => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
                                                };
                                            @endphp
                                            <span class="{{ $badgeClasses }} text-xs font-medium me-2 px-2.5 py-0.5 rounded">{{ ucfirst($application->status) }}</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <flux:button variant="ghost" size="sm" icon="eye" :href="route('admin.applications.show', $application)">Lihat</flux:button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-zinc-900 dark:border-zinc-700">
                                        <td colspan="5" class="px-6 py-4 text-center">No applications found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        {{ $applications->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
