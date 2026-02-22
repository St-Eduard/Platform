<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\User;

class SubmissionPolicy
{
    public function view(User $user, Submission $submission)
    {
        if ($user->isAdmin() || $user->isJury()) {
            return true;
        }
        
        return $user->id === $submission->user_id;
    }

    public function create(User $user)
    {
        return $user->isParticipant();
    }

    public function update(User $user, Submission $submission)
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isJury()) {
            return in_array($submission->status, ['submitted', 'needs_fix']);
        }

        return $user->id === $submission->user_id && $submission->canBeEdited();
    }

    public function delete(User $user, Submission $submission)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $submission->user_id && $submission->status === 'draft';
    }
}