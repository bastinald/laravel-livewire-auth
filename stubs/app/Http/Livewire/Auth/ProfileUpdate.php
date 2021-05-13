<?php

namespace App\Http\Livewire\Auth;

use Bastinald\LaravelLivewireForms\Components\Button;
use Bastinald\LaravelLivewireForms\Components\FormComponent;
use Bastinald\LaravelLivewireForms\Components\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileUpdate extends FormComponent
{
    public $title = 'Update Profile';

    public function mount()
    {
        $this->data = Auth::user()->toArray();
    }

    public function fields()
    {
        return [
            Input::make('name', 'Name'),
            Input::make('email', 'Email')->type('email'),
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignoreModel(Auth::user())],
        ];
    }

    public function save()
    {
        $this->validate();

        Auth::user()->update([
            'name' => $this->data('name'),
            'email' => $this->data('email'),
        ]);

        $this->emit('hideModal');
        $this->emit('$refresh');
    }
}
