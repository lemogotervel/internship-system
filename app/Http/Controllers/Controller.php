<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Return the authenticated user typed as App\Models\User.
     *
     * Auth::user() returns Authenticatable|null which Intelephense cannot
     * resolve to your custom methods (isStudent, isCoordinator, etc.).
     * Using $this->user() everywhere in subclasses fixes P1013 permanently.
     */
    protected function user(): User
    {
        /** @var User $user */
        $user = Auth::user();
        return $user;
    }
}