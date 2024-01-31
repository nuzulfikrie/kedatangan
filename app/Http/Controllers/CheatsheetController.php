<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheatsheetController extends Controller
{
    //
    public function index()
    {
        return view('cheatsheet.index');
    }
}
