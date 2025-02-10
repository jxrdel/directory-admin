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

    public function import()
    {
        return view('import');
    }

    public function download()
    {
        $records = Directory::all();

        $now = now();
        $fileName = "directory-{$now->format('Y-m-d_H-i-s')}.csv";
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
        ];

        $callback = function () use ($records) {
            $file = fopen('php://output', 'w');

            // Add CSV header with the specified column names
            fputcsv($file, ['Floor', 'Department', 'Group Name', 'Employee', 'Extension Name', 'Extension Number']);

            // Add records
            foreach ($records as $record) {
                fputcsv($file, [
                    $record->location,      // Floor
                    $record->department,    // Department
                    $record->groupname,     // Group Name
                    $record->employee,      // Employee
                    $record->extname,       // Extension Name
                    $record->extno,         // Extension Number
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
