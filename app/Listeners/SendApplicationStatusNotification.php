<?php

namespace App\Listeners;

use App\Events\ApplicationStatusChanged;
use App\Notifications\ApplicationStatusUpdated;
use App\Services\WhatsAppService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class SendApplicationStatusNotification implements ShouldQueue
{
    use InteractsWithQueue;

    protected WhatsAppService $whatsappService;

    /**
     * Create the event listener.
     */
    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    /**
     * Handle the event.
     */
    public function handle(ApplicationStatusChanged $event): void
    {
        $application = $event->application;
        $user = $application->user;

        // Send email notification
        try {
            $user->notify(new ApplicationStatusUpdated(
                $application,
                $event->oldStatus,
                $event->newStatus,
                $event->adminNote
            ));
        } catch (\Exception $e) {
            Log::error('Failed to send email notification', [
                'application_id' => $application->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }

        // Send WhatsApp notification
        if ($user->whatsapp_number) {
            $this->sendWhatsAppNotification($user, $event);
        }
    }

    /**
     * Send WhatsApp notification
     */
    protected function sendWhatsAppNotification($user, ApplicationStatusChanged $event): void
    {
        $statusLabels = [
            'pending' => 'Menunggu Review',
            'reviewed' => 'Sedang Direview',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
        ];

        $statusLabel = $statusLabels[$event->newStatus] ?? $event->newStatus;

        $message = "*Update Status Lamaran*\n\n";
        $message .= "Halo {$user->name},\n\n";
        $message .= "Status lamaran pekerjaan Anda telah diperbarui.\n\n";
        $message .= "Status Terbaru: *{$statusLabel}*\n";

        if ($event->adminNote) {
            $message .= "\n*Catatan dari HRD:*\n";
            $message .= $event->adminNote . "\n";
        }

        $message .= "\nSilakan login ke sistem untuk melihat detail lamaran Anda.\n";
        $message .= "Link: " . url('/job-application/my-application');

        try {
            $this->whatsappService->sendMessage($user->whatsapp_number, $message);
        } catch (\Exception $e) {
            Log::error('Failed to send WhatsApp notification', [
                'application_id' => $event->application->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
