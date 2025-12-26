<?php

namespace App\Events;

use App\Models\JobApplication;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationStatusChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public JobApplication $application;
    public string $oldStatus;
    public string $newStatus;
    public ?string $adminNote;

    /**
     * Create a new event instance.
     */
    public function __construct(JobApplication $application, string $oldStatus, string $newStatus, ?string $adminNote = null)
    {
        $this->application = $application;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->adminNote = $adminNote;
    }
}
