<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-6 rounded-xl p-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-600 dark:to-blue-700 rounded-xl p-8 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Welcome back, {{ auth()->user()->name }}! 👋</h1>
                    <p class="text-lg">{{ now()->format('l, d F Y') }}</p>
                </div>
                <div class="hidden md:block">
                    
                </div>
            </div>
        </div>

        <!-- Quick Stats for Applicants -->
        @if(auth()->user()->hasRole('user'))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                        <flux:icon name="document-text" class="text-blue-600 dark:text-blue-400" size="lg" />
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Status Lamaran</div>
                        <div class="text-xl font-bold">
                            @if(auth()->user()->jobApplication)
                                {{ ucfirst(auth()->user()->jobApplication->status) }}
                            @else
                                Not Submitted
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                        <flux:icon name="check-circle" class="text-green-600 dark:text-green-400" size="lg" />
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <flux:badge size="sm" :color="auth()->user()->hasVerifiedEmail() ? 'green' : 'yellow'" :icon="auth()->user()->hasVerifiedEmail() ? 'check-circle' : 'exclamation-triangle'">
                                Email {{ auth()->user()->hasVerifiedEmail() ? 'Verified' : 'Not Verified' }}
                            </flux:badge>
                        </div>
                        <div class="flex items-center gap-2">
                            <flux:badge size="sm" :color="auth()->user()->hasVerifiedWhatsApp() ? 'green' : 'yellow'" :icon="auth()->user()->hasVerifiedWhatsApp() ? 'check-circle' : 'exclamation-triangle'">
                                Phone {{ auth()->user()->hasVerifiedWhatsApp() ? 'Verified' : 'Not Verified' }}
                            </flux:badge>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="bg-purple-100 dark:bg-purple-900/30 p-3 rounded-lg">
                        <flux:icon name="briefcase" class="text-purple-600 dark:text-purple-400" size="lg" />
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Posisi yang Dilamar</div>
                        <div class="text-lg font-bold">
                            @if(auth()->user()->jobApplication)
                                {{ auth()->user()->jobApplication->position }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Tindakan cepat</h2>
            <div class="flex flex-wrap gap-3">
                @if(!auth()->user()->jobApplication)
                <flux:button href="{{ route('job-application.create') }}" variant="primary" wire:navigate>
                    <flux:icon name="plus" size="sm" /> Submit Application
                </flux:button>
                @else
                <flux:button href="{{ route('job-application.show') }}" variant="primary" wire:navigate>
                    <flux:icon name="eye" size="sm" /> View Application
                </flux:button>
                @endif
                <flux:button href="{{ route('profile.edit') }}" variant="outline" wire:navigate>
                    <flux:icon name="user" size="sm" /> Edit Profil
                </flux:button>
            </div>
        </div>
        @endif

        <!-- Quick Stats for Admin/HRD -->
        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('hrd'))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 dark:bg-blue-900/30 p-3 rounded-lg">
                        <flux:icon name="users" class="text-blue-600 dark:text-blue-400" size="lg" />
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Total Applications</div>
                        <div class="text-2xl font-bold">
                            @php
                                try {
                                    echo \App\Models\JobApplication::count();
                                } catch (\Exception $e) {
                                    echo '0';
                                }
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="bg-yellow-100 dark:bg-yellow-900/30 p-3 rounded-lg">
                        <flux:icon name="clock" class="text-yellow-600 dark:text-yellow-400" size="lg" />
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Pending Review</div>
                        <div class="text-2xl font-bold">
                            @php
                                try {
                                    echo \App\Models\JobApplication::where('status', 'pending')->count();
                                } catch (\Exception $e) {
                                    echo '0';
                                }
                            @endphp
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                        <flux:icon name="check-circle" class="text-green-600 dark:text-green-400" size="lg" />
                    </div>
                    <div>
                        <div class="text-sm text-zinc-500 dark:text-zinc-400">Accepted</div>
                        <div class="text-2xl font-bold">
                            @php
                                try {
                                    echo \App\Models\JobApplication::where('status', 'accepted')->count();
                                } catch (\Exception $e) {
                                    echo '0';
                                }
                            @endphp
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions for Admin/HRD -->
        <div class="bg-white dark:bg-zinc-800 rounded-xl p-6 border border-zinc-200 dark:border-zinc-700 shadow-sm">
            <h2 class="text-lg font-semibold mb-4">Tindakan cepat</h2>
            <div class="flex flex-wrap gap-3">
                <flux:button href="{{ route('admin.applications.index') }}" variant="primary" wire:navigate>
                    <flux:icon name="document-text" size="sm" /> View Applications
                </flux:button>
                @if(auth()->user()->hasRole('admin'))
                <flux:button href="{{ route('admin.settings.index') }}" variant="outline" wire:navigate>
                    <flux:icon name="cog" size="sm" /> Settings
                </flux:button>
                @endif
            </div>
        </div>
        @endif
    </div>
</x-layouts.app>
