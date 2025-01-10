<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Events\EmailVerificationCode;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function index()
    {
        $pageData = [
            'title' => 'All Users',
            'pageName' => 'All Users',
            'breadcrumb' => '<li class="breadcrumb-item"><a href="' . route('dashboard') . '">Dashboard</a></li>
                              <li class="breadcrumb-item active">All Users</li>',
        ];

        return view('pages.user.index')->with($pageData);
    }
    public function verificationForm()
    {
        return view('auth.email-verify');
    }
    public function sendVerificationCode(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255|unique:users,email',
        ]);
        $email = $validated['email'];
        $verificationCode = rand(100000, 999999);

        session(['email' => $email, 'verification_code' => $verificationCode]);
        $emailSent =  event(new EmailVerificationCode($email, $verificationCode));

        return response()->json(['success' => true, 'message' => 'Verification code sent successfully.',]);
    }

    public function verifyEmailCode(Request $request)
    {
        $request->validate([
            'code' => 'required|numeric',
        ]);

        $inputCode = $request->input('code');
        $sessionCode = session('verification_code');

        if ($inputCode == $sessionCode) {
            return redirect()->route('register.form')->with('message', 'Email verified successfully!');
        } else {
            return redirect()->back()->withErrors(['code' => 'The verification code is incorrect.']);
        }
    }

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
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        $email = session('email');
        $user = User::create([
            'name' => $request->name,
            'email' => $email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));
        session()->forget(['email', 'verification_code']);

        return redirect(RouteServiceProvider::HOME)->with('success', 'Account Created SuccessFully!');
    }
}
