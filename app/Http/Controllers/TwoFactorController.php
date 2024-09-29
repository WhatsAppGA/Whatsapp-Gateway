<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Writer;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;

class TwoFactorController extends Controller
{
    public function showSetup()
    {
        $google2fa = new Google2FA();
        $user = Auth::user();
        $secret = $user->two_factor_secret;
        $qrCodeUrl = $google2fa->getQRCodeUrl(
            env('APP_NAME'),
            $user->email,
            $secret
        );

        $renderer = new ImageRenderer(
            new RendererStyle(220),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCodeImage = $writer->writeString($qrCodeUrl);
        $recoveryCodes = json_decode($user->recovery_codes);

		return view('theme::pages.user.2fa_setup', ['qrCodeImage' => $qrCodeImage, 'secret' => $secret, 'recoveryCodes' => $recoveryCodes]);
    }

    public function verify(Request $request)
    {
        $request->validate(['2fa_code' => 'required']);

        $google2fa = new Google2FA();
        $user = Auth::user();

        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->input('2fa_code'));

        if ($valid) {
			$user->two_factor_enabled = true;
			$user->save();
            return redirect('/user/settings');
        } else {
            return redirect()->back()->with('alert', [
            'type' => 'danger',
            'msg' => __('Invalid 2FA code.')
            ]);
        }
    }

    public function verifyLogin(Request $request)
    {
        $request->validate(['2fa_code' => 'required']);

        $google2fa = new Google2FA();
        $user = Auth::user();

        $valid = $google2fa->verifyKey($user->two_factor_secret, $request->input('2fa_code'));

        if (!$valid) {
            $recoveryCodes = json_decode($user->recovery_codes, true);
            if (in_array($request->input('2fa_code'), $recoveryCodes)) {
                $recoveryCodes = array_diff($recoveryCodes, [$request->input('2fa_code')]);
                $user->recovery_codes = json_encode($recoveryCodes);
                $valid = true;
            }
        }
        if ($valid) {
			$request->session()->put('2fa_verified', true);
            return redirect('/home');
        } else {
            return redirect()->back()->with('alert', [
            'type' => 'danger',
            'msg' => __('Invalid 2FA code.')
            ]);
        }
    }

	public function showVerify()
    {
        return view('theme::auth.2fa_verify');
    }
}
