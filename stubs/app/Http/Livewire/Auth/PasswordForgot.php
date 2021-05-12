<?php

namespace App\Http\Livewire\Auth;

use Bastinald\LaravelLivewireForms\Components\Alert;
use Bastinald\LaravelLivewireForms\Components\Button;
use Bastinald\LaravelLivewireForms\Components\Conditional;
use Bastinald\LaravelLivewireForms\Components\FormComponent;
use Bastinald\LaravelLivewireForms\Components\Input;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;

class PasswordForgot extends FormComponent
{
    public $title = 'Forgot Password';
    public $layout = 'layouts.card';
    public $status;

    public function route()
    {
        return Route::get('/password-forgot', static::class)
            ->name('password.forgot')
            ->middleware('guest');
    }

    public function fields()
    {
        return [
            Conditional::if($this->status, [
                Alert::make($this->status),
            ]),
            Input::make('email', 'Email')->type('email'),
        ];
    }

    public function buttons()
    {
        return [
            Button::make('Send Reset Link')->click('sendResetLink'),
        ];
    }

    public function rules()
    {
        return [
            'email' => ['required', 'email'],
        ];
    }

    public function sendResetLink()
    {
        $this->validate();

        $credentials = Arr::only($this->data, 'email');
        $status = Password::sendResetLink($credentials);

        if ($status == Password::RESET_LINK_SENT) {
            $this->status = __($status);
        } else {
            $this->addError('email', __($status));
        }
    }
}
