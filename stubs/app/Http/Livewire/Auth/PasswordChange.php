<?php

namespace App\Http\Livewire\Auth;

use Bastinald\LaravelLivewireAuth\Rules\CurrentPasswordRule;
use Bastinald\LaravelLivewireForms\Components\Button;
use Bastinald\LaravelLivewireForms\Components\FormComponent;
use Bastinald\LaravelLivewireForms\Components\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordChange extends FormComponent
{
    public $title = 'Change Password';

    public function fields()
    {
        return [
            Input::make('current_password', 'Current Password')->type('password'),
            Input::make('new_password', 'New Password')->type('password'),
            Input::make('new_password_confirmation', 'Confirm New Password')->type('password'),
        ];
    }

    public function buttons()
    {
        return [
            Button::make('Cancel', 'secondary')->click("\$emit('hideModal')"),
            Button::make('Save')->click('save'),
        ];
    }

    public function rules()
    {
        return [
            'current_password' => ['required', 'string', new CurrentPasswordRule],
            'new_password' => ['required', 'string', 'confirmed', 'min:8'],
        ];
    }

    public function save()
    {
        $this->validate();

        Auth::user()->update([
            'password' => Hash::make($this->data('new_password')),
        ]);

        $this->emit('hideModal');
    }
}
