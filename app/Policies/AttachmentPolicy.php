<?php

namespace App\Policies;

use App\Models\Attachment;
use App\Models\User;

class AttachmentPolicy
{
    public function view(User $user, Attachment $attachment)
    {
        if ($user->isAdmin() || $user->isJury()) {
            return true;
        }
        
        return $user->id === $attachment->user_id;
    }

    public function delete(User $user, Attachment $attachment)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $attachment->user_id && 
               $attachment->submission->canBeEdited();
    }
}