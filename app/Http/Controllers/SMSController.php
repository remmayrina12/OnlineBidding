<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SMSController extends Controller
{
    public function send(Request $request)
    {
        return view('sms');
    }
}
