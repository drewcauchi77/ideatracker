<?php

namespace App\Livewire\Traits;

trait WithAuthRedirects
{
    public function redirectToLogin()
    {
        return redirect()->setIntendedUrl(url()->previous())->route('login');
    }

    public function redirectToRegister()
    {
        return redirect()->setIntendedUrl(url()->previous())->route('register');
    }
}
