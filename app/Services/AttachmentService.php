<?php

namespace App\Services;

use App\Models\Attachment;
use App\Models\Submission;
use App\Jobs\ScanAttachmentJob;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AttachmentService
{
    protected $disk;

    public function __construct()
    {
        $this->disk = Storage::disk('s3');
        Log::info('Using S3 disk for attachments');
    }

    public function upload(Submission $submission, int $userId, UploadedFile $file)
    {
        try {
            $attachmentsCount = $submission->attachments()->count();
            if ($attachmentsCount >= Attachment::MAX_FILES) {
                throw new \Exception('Максимальное количество файлов: ' . Attachment::MAX_FILES);
            }

            if ($file->getSize() > Attachment::MAX_SIZE) {
                throw new \Exception('Размер файла не должен превышать 10MB');
            }

            $mime = $file->getMimeType();
            $allowedMimes = [
                'application/pdf',
                'application/zip',
                'application/x-zip-compressed',
                'image/png',
                'image/jpeg',
                'image/jpg'
            ];
            
            if (!in_array($mime, $allowedMimes)) {
                throw new \Exception('Разрешены только файлы: PDF, ZIP, PNG, JPG');
            }

            $extension = $file->getClientOriginalExtension();
            $key = 'submissions/' . $submission->id . '/' . Str::uuid() . '.' . $extension;

            Log::info('Uploading to S3 with key: ' . $key);

            $fileContent = file_get_contents($file->getRealPath());
            $result = $this->disk->put($key, $fileContent);

            if (!$result) {
                throw new \Exception('Ошибка загрузки в S3');
            }

            Log::info('File uploaded to S3 successfully');

            $attachment = Attachment::create([
                'submission_id' => $submission->id,
                'user_id' => $userId,
                'original_name' => $file->getClientOriginalName(),
                'mime' => $mime,
                'size' => $file->getSize(),
                'storage_key' => $key,
                'status' => Attachment::STATUS_PENDING
            ]);

            Log::info('Attachment record created with ID: ' . $attachment->id);

            dispatch(new ScanAttachmentJob($attachment));

            return $attachment;

        } catch (\Exception $e) {
            Log::error('Upload error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Получить содержимое файла из S3
     */
    public function getFileContent(Attachment $attachment)
    {
        try {
            if (!$this->disk->exists($attachment->storage_key)) {
                throw new \Exception('Файл не найден в хранилище');
            }

            $content = $this->disk->get($attachment->storage_key);
            Log::info('Retrieved file content from S3: ' . $attachment->storage_key);
            
            return $content;

        } catch (\Exception $e) {
            Log::error('Get file content error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function markScanned(Attachment $attachment)
    {
        $attachment->update([
            'status' => Attachment::STATUS_SCANNED,
            'rejection_reason' => null
        ]);
        
        Log::info('Attachment ' . $attachment->id . ' marked as scanned');
        return $attachment;
    }

    public function reject(Attachment $attachment, string $reason)
    {
        $attachment->update([
            'status' => Attachment::STATUS_REJECTED,
            'rejection_reason' => $reason
        ]);
        
        Log::info('Attachment ' . $attachment->id . ' rejected: ' . $reason);
        return $attachment;
    }

    public function getSignedUrl(Attachment $attachment)
    {
        try {
            $user = auth()->user();
            
            if ($user->isParticipant() && $attachment->user_id !== $user->id) {
                throw new \Exception('Нет доступа к файлу');
            }

            if ($user->isJury() || $user->isAdmin() || $attachment->user_id === $user->id) {
                $url = $this->disk->temporaryUrl($attachment->storage_key, now()->addHour());
                Log::info('Generated signed URL for attachment ' . $attachment->id);
                return $url;
            }

            throw new \Exception('Нет доступа к файлу');

        } catch (\Exception $e) {
            Log::error('Signed URL error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function delete(Attachment $attachment)
    {
        try {
            if ($this->disk->exists($attachment->storage_key)) {
                $this->disk->delete($attachment->storage_key);
                Log::info('Deleted file from S3: ' . $attachment->storage_key);
            }
            
            $attachment->delete();
            Log::info('Deleted attachment record: ' . $attachment->id);
            
            return true;

        } catch (\Exception $e) {
            Log::error('Delete error: ' . $e->getMessage());
            throw $e;
        }
    }
}