<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    
    public function index(){
        return view('theme::auth.login');
    }

    public function store(Request $request){
        
     if(Auth::attempt($request->only(['username','password']))){
         return redirect('/home');
     }

     throw ValidationException::withMessages([
         'username' => __('The provided credentials do not match our records.'),
     ]);
    
    }
}
