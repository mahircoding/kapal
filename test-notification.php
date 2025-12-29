#!/usr/bin/env php
<?php

/**
 * Test Application Status Notification
 * 
 * This script tests if notifications (Email & WhatsApp) are sent
 * when application status is updated.
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\JobApplication;
use App\Events\ApplicationStatusChanged;
use Illuminate\Support\Facades\Log;

echo "=== Testing Application Status Notification ===\n\n";

// Get latest application
$application = JobApplication::with('user')->latest()->first();

if (!$application) {
    echo "❌ No application found in database\n";
    exit(1);
}

echo "✓ Found Application:\n";
echo "  - ID: {$application->id}\n";
echo "  - User: {$application->user->name}\n";
echo "  - Email: {$application->user->email}\n";
echo "  - WhatsApp: {$application->user->whatsapp_number}\n";
echo "  - Current Status: {$application->status}\n\n";

// Store old status
$oldStatus = $application->status;
$newStatus = $oldStatus === 'pending' ? 'reviewed' : 'pending';

echo "Testing status change: {$oldStatus} → {$newStatus}\n\n";

// Dispatch event
echo "Dispatching ApplicationStatusChanged event...\n";
event(new ApplicationStatusChanged(
    $application,
    $oldStatus,
    $newStatus,
    "This is a test notification from admin."
));

echo "✓ Event dispatched successfully\n\n";

// Check if jobs are queued
$jobsCount = \Illuminate\Support\Facades\DB::table('jobs')->count();
echo "Jobs in queue: {$jobsCount}\n";

if ($jobsCount > 0) {
    echo "✓ Notification job queued successfully\n\n";
    
    echo "To process the queue, run:\n";
    echo "  php artisan queue:work\n\n";
    
    echo "Or process one job:\n";
    echo "  php artisan queue:work --once\n\n";
} else {
    echo "⚠ No jobs in queue. Check if listener is registered.\n\n";
}

// Check logs
echo "Check logs for details:\n";
echo "  tail -f storage/logs/laravel.log\n\n";

echo "=== Test Complete ===\n";
