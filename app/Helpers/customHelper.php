<?php

use Illuminate\Support\Facades\Auth;

/**
 * @return int
 */
function checkAuthUserRole (): int
{

    $userInfo = Auth::user();
    if (empty($userInfo['role_id'])) {

        return 1; // Admin
    }
    else {

        return 2; // Other Users
    }
}
