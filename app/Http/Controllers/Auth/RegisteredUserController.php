<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
//use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => [
            'required',
            'string',
            'lowercase',
            'email',
            'max:255',
            'unique:'.User::class,
            'regex:/^\d{10}@student\.uitm\.edu\.my$/',
        ],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ], [
        'email.regex' => 'You must register using your UiTM student email (e.g. 2025160255@student.uitm.edu.my).',
    ]);

    $studentId = strstr($request->email, '@', true);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'student_id' => $studentId,
        'password' => Hash::make($request->password),
    ]);

    event(new Registered($user));

    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
}
}
