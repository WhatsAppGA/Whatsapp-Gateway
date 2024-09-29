<?php
/**************************/
/*    Nulled & Decoded    */
/*   By Magd Almuntaser   */
/*         TTMTT          */
/**************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use Carbon\Carbon;

class UserController extends Controller
{
   
    public function settings(){
        return view('theme::pages.user.settings');
    }

    public function changePasswordPost(Request $request)
    {
        

        $request->validate([
            'current' => ['required', 'string' ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $newPassword = bcrypt($request->password);
        $request->user()->fill([
            'password' => $newPassword
        ])->save();

        return back()->with('alert', ['type' => 'success', 'msg' => __('Password Changed') ]);
    }
	
	public function deleteHistory(Request $request)
    {
		$user = Auth::user();
		
		$request->validate([
            'delete_history' => ['required', 'integer'],
        ]);
		$user->delete_history = $request->delete_history;
		$user->save();
		
		return back()->with('alert', ['type' => 'success', 'msg' => __('Delete message history has been updated') ]);
	}

    public function generateNewApiKey(Request $request)
    {
        $newApiKey = Str::random(30);
        $request->user()->fill([
            'api_key' => $newApiKey
        ])->save();
        return back()->with('alert', [
            'type' => 'success',
            'msg' => __('Success set new Api Key')
        ]);
    }
	
	public function toggleTwoFactor(Request $request)
    {
        $user = Auth::user();
        $google2fa = new Google2FA();

        if ($request->action == 'enable') {
            $recoveryCodes = [];
            for ($i = 0; $i < 8; $i++) {
                $recoveryCodes[] = $this->generateNumericCode(6);
            }
            $user->recovery_codes = json_encode($recoveryCodes);
            $user->two_factor_secret = $google2fa->generateSecretKey();
            $user->save();

            return redirect()->route('user.2fa_setup');
        } else {
            $user->two_factor_secret = null;
			$user->recovery_codes = null;
            $user->two_factor_enabled = false;
            $user->save();

            return redirect()->back();
        }
    }

    private function generateNumericCode($length)
    {
        $numbers = '0123456789';
        $code = '';
        for ($i = 0; $i < $length; $i++) {
            $code .= $numbers[mt_rand(0, strlen($numbers) - 1)];
        }
        return $code;
    }

}
?>