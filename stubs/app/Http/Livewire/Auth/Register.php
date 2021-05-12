<?php

namespace App\Http\Livewire\Auth;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Bastinald\LaravelLivewireForms\Components\Button;
use Bastinald\LaravelLivewireForms\Components\DynamicComponent;
use Bastinald\LaravelLivewireForms\Components\FormComponent;
use Bastinald\LaravelLivewireForms\Components\Input;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Lukeraymonddowning\Honey\Traits\WithHoney;

class Register extends FormComponent
{
    use WithHoney;

    public $title = 'Register';
    public $layout = 'layouts.card';

    public function route()
    {
        return Route::get('/register', static::class)
            ->name('register')
            ->middleware('guest');
    }

    public function fields()
    {
        return [
            Input::make('name', 'Name'),
            Input::make('email', 'Email')->type('email'),
            Input::make('password', 'Password')->type('password'),
            Input::make('password_confirmation', 'Confirm Password')->type('password'),
            DynamicComponent::make('honey'),
        ];
    }

    public function buttons()
    {
        return [
            Button::make('Already registered?', 'link')->route('login'),
            Button::make('Register')->click('register'),
        ];
    }

    public function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ];
    }

    public function register()
    {
        $this->validate();

        if ($this->honeyPasses()) {
            $user = User::create([
                'name' => $this->data('name'),
                'email' => $this->data('email'),
                'password' => Hash::make($this->data('password')),
            ]);

            event(new Registered($user));

            Auth::login($user);

            return redirect(RouteServiceProvider::HOME);
        }
    }
}
