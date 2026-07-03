<?php

namespace App\Http\Responses;

use Filament\Auth\Http\Responses\Contracts\LoginResponse as Contract;
use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;

class LoginResponse implements Contract
{
    public function toResponse($request): RedirectResponse | Redirector
    {
        return redirect()->intended(Filament::getHomeUrl());
    }
}
