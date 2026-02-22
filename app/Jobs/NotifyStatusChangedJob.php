<?php

namespace App\Jobs;

use App\Models\Submission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyStatusChangedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $submission;
    protected $oldStatus;

    public function __construct(Submission $submission, string $oldStatus)
    {
        $this->submission = $submission;
        $this->oldStatus = $oldStatus;
    }

    public function handle(): void
    {
        $user = $this->submission->user;
        
        $statusNames = [
            'draft' => 'Черновик',
            'submitted' => 'На проверке',
            'needs_fix' => 'Требуется доработка',
            'accepted' => 'Принята',
            'rejected' => 'Отклонена'
        ];

        $message = sprintf(
            'Статус вашей работы "%s" изменён с "%s" на "%s"',
            $this->submission->title,
            $statusNames[$this->oldStatus] ?? $this->oldStatus,
            $statusNames[$this->submission->status] ?? $this->submission->status
        );

        // Сохраняем уведомление в БД
        $user->notifications()->create([
            'id' => \Str::uuid(),
            'type' => 'status_change',
            'data' => [
                'message' => $message,
                'submission_id' => $this->submission->id,
                'old_status' => $this->oldStatus,
                'new_status' => $this->submission->status
            ]
        ]);

        Log::info('Status change notification', [
            'user' => $user->email,
            'message' => $message
        ]);
    }
}