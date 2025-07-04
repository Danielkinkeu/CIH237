<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public function __construct()
    {
    }
    public function welcome()
    {
        return view('welcome');
    }
}
