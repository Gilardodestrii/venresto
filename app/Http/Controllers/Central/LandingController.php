<?php
namespace App\Http\Controllers\Central;
use App\Http\Controllers\Controller;

class LandingController extends Controller
{
    public function __invoke(){
        return view('landing.home');
    }
}
