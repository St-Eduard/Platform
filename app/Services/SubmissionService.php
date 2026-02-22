<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\SubmissionComment;
use App\Jobs\NotifyStatusChangedJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SubmissionService
{
    public function create(array $data, int $userId)
    {
        return DB::transaction(function () use ($data, $userId) {
            return Submission::create([
                'contest_id' => $data['contest_id'],
                'user_id' => $userId,
                'title' => $data['title'],
                'description' => $data['description'],
                'status' => Submission::STATUS_DRAFT
            ]);
        });
    }

    public function update(Submission $submission, array $data)
    {
        if (!$submission->canBeEdited()) {
            throw new \Exception('Нельзя редактировать работу в текущем статусе');
        }

        return DB::transaction(function () use ($submission, $data) {
            $submission->update($data);
            return $submission;
        });
    }

    public function submit(Submission $submission)
    {
        if (!$submission->canBeEdited()) {
            throw new \Exception('Нельзя отправить работу в текущем статусе');
        }

        if (!$submission->hasScannedAttachments()) {
            throw new \Exception('Необходимо загрузить хотя бы один проверенный файл');
        }

        return DB::transaction(function () use ($submission) {
            $oldStatus = $submission->status;
            $submission->status = Submission::STATUS_SUBMITTED;
            $submission->save();

            Log::info('Submission submitted', [
                'id' => $submission->id,
                'old_status' => $oldStatus,
                'new_status' => $submission->status
            ]);

            dispatch(new NotifyStatusChangedJob($submission, $oldStatus));

            return $submission;
        });
    }

    public function changeStatus(Submission $submission, string $newStatus)
    {
        $allowedTransitions = Submission::getAllowedStatusTransitions();
        
        if (!in_array($newStatus, $allowedTransitions[$submission->status] ?? [])) {
            throw new \Exception('Недопустимый переход статуса');
        }

        return DB::transaction(function () use ($submission, $newStatus) {
            $oldStatus = $submission->status;
            $submission->status = $newStatus;
            $submission->save();

            Log::info('Status changed', [
                'id' => $submission->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);

            dispatch(new NotifyStatusChangedJob($submission, $oldStatus));

            return $submission;
        });
    }

    public function addComment(Submission $submission, int $userId, string $body)
    {
        return DB::transaction(function () use ($submission, $userId, $body) {
            return SubmissionComment::create([
                'submission_id' => $submission->id,
                'user_id' => $userId,
                'body' => $body
            ]);
        });
    }
}