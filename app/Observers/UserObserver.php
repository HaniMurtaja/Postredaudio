<?php

namespace App\Observers;

use App\Models\User;
use Exception;

class UserObserver
{
    public function deleting(User $user)
    {
        if (!(User::where('id', '!=', $user->id)->where('admin', true)->count())) {
            throw new Exception('At least one admin user must be available!');
        }
    }

    public function updating(User $user)
    {
        if (!$user->admin && !User::where('id', '!=', $user->id)->where('admin', true)->count()) {
            throw new Exception('At least one admin user must be available!');
        }
    }
}
