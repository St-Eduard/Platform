<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Submission;
use App\Services\AttachmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class AttachmentController extends Controller
{
    protected $attachmentService;

    public function __construct(AttachmentService $attachmentService)
    {
        $this->attachmentService = $attachmentService;
    }

    public function upload(Request $request, Submission $submission)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Требуется авторизация'], 401);
            }

            if ($submission->user_id !== Auth::id()) {
                return response()->json(['error' => 'Доступ запрещен'], 403);
            }

            if (!$submission->canBeEdited()) {
                return response()->json(['error' => 'Нельзя загружать файлы в текущем статусе'], 403);
            }

            $request->validate([
                'file' => 'required|file|max:10240|mimes:pdf,zip,png,jpg'
            ]);

            $file = $request->file('file');
            
            if (!$file->isValid()) {
                return response()->json(['error' => 'Файл поврежден'], 400);
            }

            $attachment = $this->attachmentService->upload($submission, Auth::id(), $file);

            return response()->json([
                'success' => true,
                'attachment' => [
                    'id' => $attachment->id,
                    'name' => $attachment->original_name,
                    'size' => $attachment->size,
                    'status' => $attachment->status
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['error' => 'Файл должен быть PDF, ZIP, PNG или JPG, размером до 10MB'], 422);
        } catch (\Exception $e) {
            Log::error('Upload error: ' . $e->getMessage());
            return response()->json(['error' => 'Ошибка загрузки файла: ' . $e->getMessage()], 422);
        }
    }

    public function download(Attachment $attachment)
    {
        try {
            if (!Auth::check()) {
                abort(401);
            }

            // Проверка прав
            $user = Auth::user();
            if ($user->isParticipant() && $attachment->user_id !== $user->id) {
                abort(403);
            }

            // Получаем содержимое файла из S3
            $fileContent = $this->attachmentService->getFileContent($attachment);
            
            // Определяем заголовки для скачивания
            $headers = [
                'Content-Type' => 'application/octet-stream',
                'Content-Disposition' => 'attachment; filename="' . $attachment->original_name . '"',
                'Content-Length' => $attachment->size,
                'Cache-Control' => 'no-cache, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ];

            return Response::make($fileContent, 200, $headers);

        } catch (\Exception $e) {
            Log::error('Download error: ' . $e->getMessage());
            return back()->with('error', 'Ошибка скачивания файла: ' . $e->getMessage());
        }
    }

    public function destroy(Attachment $attachment)
    {
        try {
            if (!Auth::check()) {
                return response()->json(['error' => 'Требуется авторизация'], 401);
            }

            if ($attachment->user_id !== Auth::id()) {
                return response()->json(['error' => 'Доступ запрещен'], 403);
            }

            if (!$attachment->submission->canBeEdited()) {
                return response()->json(['error' => 'Нельзя удалять файлы в текущем статусе'], 403);
            }

            $this->attachmentService->delete($attachment);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Delete error: ' . $e->getMessage());
            return response()->json(['error' => 'Ошибка удаления файла'], 422);
        }
    }
}