<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
		$locale = session('locale');
        session()->flush();
		session(['locale' => $locale]);
		app()->setLocale(session('locale'));
        try {
            Artisan::call('config:clear');
        } catch (\Throwable $th) {
            //throw $th;
        }
        Auth::logout();
        return redirect('/login');
    }
}
