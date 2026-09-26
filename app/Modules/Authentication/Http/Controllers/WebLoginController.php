<?php

declare(strict_types=1);

namespace App\Modules\Authentication\Http\Controllers;

use App\Modules\Authentication\Exceptions\EmailNotVerifiedException;
use App\Modules\Authentication\Exceptions\InvalidCredentialsException;
use App\Modules\Authentication\Services\AuthenticationService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class WebLoginController extends Controller
{
    public function __construct(
        private readonly AuthenticationService $authenticationService
    ) {
    }

    public function create(): View
    {
        return view('authentication.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'remember_me' => ['nullable', 'boolean'],
        ]);

        try {
            $user = $this->authenticationService->login(
                $credentials['email'],
                $credentials['password'],
                (bool) ($credentials['remember_me'] ?? false)
            );

            auth()->login(
                    $user,
                    (bool) ($credentials['remember_me'] ?? false)
                );

                $request->session()->regenerate();
            

            return redirect()->intended('/dashboard');
        } catch (InvalidCredentialsException|EmailNotVerifiedException $e) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'email' => $e->getMessage(),
                ]);
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}