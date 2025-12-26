<?php

namespace App\Notifications;

use App\Models\JobApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected JobApplication $application;
    protected string $oldStatus;
    protected string $newStatus;
    protected ?string $adminNote;

    /**
     * Create a new notification instance.
     */
    public function __construct(JobApplication $application, string $oldStatus, string $newStatus, ?string $adminNote = null)
    {
        $this->application = $application;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->adminNote = $adminNote;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusLabels = [
            'pending' => 'Menunggu Review',
            'reviewed' => 'Sedang Direview',
            'accepted' => 'Diterima',
            'rejected' => 'Ditolak',
        ];

        $statusLabel = $statusLabels[$this->newStatus] ?? $this->newStatus;
        
        $mailMessage = (new MailMessage)
            ->subject('Update Status Lamaran - ' . site_name())
            ->greeting('Halo ' . $notifiable->name . ',')
            ->line('Status lamaran pekerjaan Anda telah diperbarui.')
            ->line('**Status Sebelumnya:** ' . ($statusLabels[$this->oldStatus] ?? $this->oldStatus))
            ->line('**Status Terbaru:** ' . $statusLabel);

        if ($this->adminNote) {
            $mailMessage->line('**Catatan dari HRD:**')
                ->line($this->adminNote);
        }

        $mailMessage->action('Lihat Detail Lamaran', url('/job-application/my-application'))
            ->line('Terima kasih telah melamar di ' . site_name() . '.')
            ->salutation('Salam, Tim ' . site_name());

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'admin_note' => $this->adminNote,
        ];
    }
}
