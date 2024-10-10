<?php

namespace App\Http\Controllers\Auth;

use App\Actions\RegisterUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\VaccineCenter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function __construct(private RegisterUserAction $registerUserAction) {}

    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $vaccine_centers = VaccineCenter::select('id', 'name')->get();

        return view('auth.register', ['vaccine_centers' => $vaccine_centers]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): RedirectResponse
    {
        try {
            $status = $this->registerUserAction->execute($request->validated());

            return $status
                ? redirect()->route('register')->with('success', 'Registration successful')
                : redirect()->route('register')->withErrors('Registration failed');

        } catch (\Exception $e) {
            Log::error('Registration failed: '.$e->getMessage());

            // Redirect with a generic error message
            return redirect()->route('register')->withErrors('Registration failed');
        }
    }
}
