<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Bastinald\LaravelLivewireForms\Components\Button;
use Bastinald\LaravelLivewireForms\Components\FormComponent;
use Bastinald\LaravelLivewireForms\Components\Input;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class PasswordReset extends FormComponent
{
    public $title = 'Reset Password';
    public $layout = 'layouts.card';

    public function route()
    {
        return Route::get('/password-reset/{token}/{email}', static::class)
            ->name('password.reset')
            ->middleware('guest');
    }

    public function mount($token, $email)
    {
        $this->data = [
            'token' => $token,
            'email' => $email,
        ];
    }

    public function fields()
    {
        return [
            Input::make('email', 'Email')->type('email'),
            Input::make('password', 'Password')->type('password'),
            Input::make('password_confirmation', 'Confirm Password')->type('password'),
        ];
    }

    public function buttons()
    {
        return [
            Button::make('Reset Password')->click('resetPassword'),
        ];
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ];
    }

    public function resetPassword()
    {
        $this->validate();

        $credentials = Arr::only($this->data, ['token', 'email', 'password', 'password_confirmation']);
        $status = Password::reset($credentials, function (User $user) {
            $user->forceFill([
                'password' => Hash::make($this->data('password')),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));

            Auth::login($user);
        });

        if ($status == Password::PASSWORD_RESET) {
            return redirect(RouteServiceProvider::HOME);
        } else {
            $this->addError('email', __($status));
        }
    }
}
