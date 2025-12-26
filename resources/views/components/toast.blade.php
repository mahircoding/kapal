@props(['type' => 'success', 'message' => ''])

@php
    $colors = [
        'success' => 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800 text-green-800 dark:text-green-200',
        'error' => 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800 text-red-800 dark:text-red-200',
        'info' => 'bg-blue-50 dark:bg-blue-900/20 border-blue-200 dark:border-blue-800 text-blue-800 dark:text-blue-200',
    ];
    
    $icons = [
        'success' => 'check-circle',
        'error' => 'x-circle',
        'info' => 'information-circle',
    ];
@endphp

<div
    x-data="{ show: true }"
    x-show="show"
    x-init="setTimeout(() => show = false, 5000)"
    x-transition:enter="transform ease-out duration-300 transition"
    x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
    x-transition:enter-end="translate-y-0 opacity-100 sm:translate-x-0"
    x-transition:leave="transition ease-in duration-100"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg border shadow-lg {{ $colors[$type] }}"
    style="display: none;"
>
    <div class="p-4">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <flux:icon :name="$icons[$type]" class="h-6 w-6" />
            </div>
            <div class="ml-3 w-0 flex-1 pt-0.5">
                <p class="text-sm font-medium">
                    {{ $message }}
                </p>
            </div>
            <div class="ml-4 flex flex-shrink-0">
                <button
                    @click="show = false"
                    type="button"
                    class="inline-flex rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 opacity-70 hover:opacity-100"
                >
                    <span class="sr-only">Close</span>
                    <flux:icon name="x-mark" class="h-5 w-5" />
                </button>
            </div>
        </div>
    </div>
</div>
