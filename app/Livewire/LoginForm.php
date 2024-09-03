<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use LdapRecord\Container;
use Livewire\Component;

class LoginForm extends Component
{
    public $username;
    public $password;
    public $user;

    public function render()
    {
        return view('livewire.login-form');
    }

    public function login(){
        // dd(OpenLDAPUser::where('uid', 'Jardel Regis')->get());

        $connection = Container::getConnection('default');
        $this->user = User::where('Username', $this->username)->first(); //Gets user
        // dd($this->user);

        if ($this->user){ //If user is found..
            $ADuser = $connection->query()->where('samaccountname', '=', $this->username)->first(); //Gets user from AD
            // dd($ADuser);
            if ($connection->auth()->attempt($ADuser['distinguishedname'][0], $this->password)){ //If password is correct and user is not locked out
                // dd('Success');
                Auth::login($this->user);
                redirect()->route('/');
            }else {
                $this->resetValidation();
                $this->addError('password', 'Incorrect password');
                $this->password = null;
            }
        }
        else{ //Display error if no user is found
            $this->addError('username', 'User does not have access to this system');
        }
    }
}
