<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Attendance extends Controller
{
    //
    public function index()
    {
        return view('attendance.index');
    }
}
