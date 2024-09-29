<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class PasswordResetController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('theme::auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
	{
		$request->validate(['email' => 'required|email']);
		$user = User::where('email', $request->email)->first();

		if (!$user) {
			return back()->with('alert', [
				'type' => 'danger', 
				'msg' => __('Email does not exist.')
			]);
		}

		$token = Password::createToken($user);
		$url = URL::to('/password/reset', $token) . '?email=' . urlencode($request->email);
		
		try {
			Mail::to($request->email)->send(new ResetPasswordMail($url));
		} catch (\Throwable $th) {
			return redirect('login')->with('alert', [
				'type' => 'danger', 
				'msg' => __('There is an issue with your SMTP settings')
			]);
		}

		return redirect('login')->with('alert', [
			'type' => 'success', 
			'msg' => __('We have emailed your password reset link!')
		]);
	}

    public function showResetForm(Request $request, $token = null)
	{
		$email = $request->email;

		$tokenData = DB::table('password_resets')->where('email', $email)->first();

		if (!$tokenData || !Hash::check($token, $tokenData->token)) {
			return redirect('/password/reset')->with('alert', [
				'type' => 'danger', 
				'msg' => __('Invalid token or email')
			]);
		}

		return view('theme::auth.passwords.reset')->with(['token' => $token, 'email' => $email]);
	}


    public function reset(Request $request)
	{
		$request->validate([
			'token' => 'required',
			'email' => 'required|email',
			'password' => 'required|string|min:8|confirmed',
		]);

		$status = Password::reset(
			$request->only('email', 'password', 'password_confirmation', 'token'),
			function ($user, $password) {
				$user->forceFill([
					'password' => Hash::make($password)
				])->setRememberToken(Str::random(60));

				$user->save();
				event(new PasswordReset($user));
				Auth::login($user);
			}
		);

		if ($status === Password::PASSWORD_RESET) {
			return redirect()->route('login')->with('alert', [
				'type' => 'success',
				'msg' => __('Your password has been reset successfully.')
			]);
		} else {
			return back()->with('alert', [
				'type' => 'danger',
				'msg' => __($status)
			]);
		}
	}
}
