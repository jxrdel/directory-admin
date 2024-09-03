<?php

namespace App\Http\Controllers;

use App\Models\Directory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class Controller
{
    public function index()
    {
        return view('directory');
    }

    public function login()
    {
        return view('login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function directory()
    {
        return view('directory');
    }

    public function getDirectory()
    {
        $query = Directory::all();

        return DataTables::of($query)->make(true);
    }

    public function users()
    {
        return view('users');
    }

    public function getUsers()
    {
        $query = User::all();

        return DataTables::of($query)->make(true);
    }
}
