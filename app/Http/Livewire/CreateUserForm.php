<?php

namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;

class CreateUserForm extends Component
{
    public $currentPage = 1;
    public $success;

    // Form properties
    public $firstName;
    public $lastName;
    public $email;
    public $password;
    public $confirmPassword;

    public $pages = [
        1 => [
            'heading' => 'Personal Information',
            'subheading' => 'Enter your name and email to get started.',
        ],
        2 => [
            'heading' => 'Password',
            'subheading' => 'Create a password for the new account.',
        ],
    ];

    private $validationRules = [
        1 => [
            'firstName' => ['required', 'min:3'],
            'lastName' => ['required', 'min:3'],
            'email' => ['required', 'email', 'unique:users,email'],
        ],
        2 => [
            'password' => ['required', 'string', 'min:8'],
            'confirmPassword' => ['required', 'string', 'same:password', 'min:8'],
        ],
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName, $this->validationRules[$this->currentPage]);
    }

    public function goToNextPage()
    {
        $this->validate($this->validationRules[$this->currentPage]);
        $this->currentPage++;
    }

    public function goToPreviousPage()
    {
        $this->currentPage--;
    }

    public function resetSuccess()
    {
        $this->reset('success');
    }

    public function submit()
    {
        $rules = collect($this->validationRules)->collapse()->toArray();

        $this->validate($rules);

        User::create([
            'name' => "{$this->firstName} {$this->lastName}",
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);

        $this->reset();
        $this->resetValidation();

        $this->success = 'User created successfully!';
    }

    public function render()
    {
        return view('livewire.create-user-form');
    }
}
