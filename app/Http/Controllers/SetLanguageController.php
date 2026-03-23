<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Mail\Transport\ResendTransport;

class SetLanguageController extends Controller
{
    public function __invoke(string $lang){
        session()->put('lang',$lang);
        app()->setLocale($lang);
        return redirect()->back();
    }
}
