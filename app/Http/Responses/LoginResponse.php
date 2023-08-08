<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class LoginResponse implements LoginResponseContract
{

    /**
     * @param $request
     * @return Redirector|RedirectResponse|Application|Response
     */
    public function toResponse($request): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application|\Symfony\Component\HttpFoundation\Response
    {
        return redirect(route('dashboard'));
    }

}
