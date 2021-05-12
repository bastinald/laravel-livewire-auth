<?php

namespace App\Http\Livewire\Auth;

use App\Providers\RouteServiceProvider;
use Bastinald\LaravelLivewireForms\Components\Button;
use Bastinald\LaravelLivewireForms\Components\Checkbox;
use Bastinald\LaravelLivewireForms\Components\FormComponent;
use Bastinald\LaravelLivewireForms\Components\Input;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class Login extends FormComponent
{
    public $title = 'Login';
    public $layout = 'layouts.card';

    public function route()
    {
        return Route::get('/login', static::class)
            ->name('login')
            ->middleware('guest');
    }

    public function fields()
    {
        return [
            Input::make('email', 'Email')->type('email'),
            Input::make('password', 'Password')->type('password'),
            Checkbox::make('remember', 'Remember me'),
        ];
    }

    public function buttons()
    {
        return [
            Button::make('Forgot password?', 'link')->route('password.forgot'),
            Button::make('Login')->click('login'),
        ];
    }

    public function rules()
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function login()
    {
        $this->validate();

        if ($this->ensureIsNotRateLimited() && $this->authenticate()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }
    }

    public function ensureIsNotRateLimited()
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return true;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        $this->addError('email', __('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]));

        return false;
    }

    public function authenticate()
    {
        $credentials = Arr::only($this->data, ['email', 'password']);

        if (Auth::attempt($credentials, $this->data('remember', false))) {
            RateLimiter::clear($this->throttleKey());

            return true;
        }

        RateLimiter::hit($this->throttleKey());

        $this->addError('email', __('auth.failed'));

        return false;
    }

    public function throttleKey()
    {
        return Str::lower($this->data('email')) . '|' . request()->ip();
    }
}
