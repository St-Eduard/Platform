<?php

namespace App\Jobs;

use App\Models\Attachment;
use App\Services\AttachmentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ScanAttachmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $attachment;

    public function __construct(Attachment $attachment)
    {
        $this->attachment = $attachment;
    }

    public function handle(AttachmentService $attachmentService): void
    {
        try {
            Log::info('Scanning attachment: ' . $this->attachment->id);
            
            // Проверка имени файла
            if (preg_match('/[^\w\s\.\-\(\)]/u', $this->attachment->original_name)) {
                throw new \Exception('Имя файла содержит недопустимые символы');
            }

            // Проверка MIME типа
            $allowedMimes = [
                'application/pdf',
                'application/zip',
                'application/x-zip-compressed',
                'image/png',
                'image/jpeg',
                'image/jpg'
            ];
            
            if (!in_array($this->attachment->mime, $allowedMimes)) {
                throw new \Exception('Недопустимый тип файла: ' . $this->attachment->mime);
            }

            // Проверка размера
            if ($this->attachment->size > Attachment::MAX_SIZE) {
                throw new \Exception('Превышен максимальный размер файла');
            }

            $attachmentService->markScanned($this->attachment);
            
            Log::info('Attachment scanned successfully', [
                'id' => $this->attachment->id,
                'name' => $this->attachment->original_name
            ]);

        } catch (\Exception $e) {
            $attachmentService->reject($this->attachment, $e->getMessage());
            
            Log::error('Attachment scan failed', [
                'id' => $this->attachment->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}