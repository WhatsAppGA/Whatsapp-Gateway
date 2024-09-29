<?php
/**************************/
/*    Nulled & Decoded    */
/*   By Magd Almuntaser   */
/*         TTMTT          */
/**************************/

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Services\Impl\WhatsappServiceImpl;
use App\Utils\CacheKey;

class HomeController extends Controller
{
    

    public function index(Request $request){
		if($request->user()->level == 'admin'){
			$currentVersion = config('app.version');
			$checkUrl = "https://onexgen.com/mpwa/check.php?v=$currentVersion&lang=".config('app.locale');
			try {
				$response = Http::timeout(5)->get($checkUrl);
				$data = $response->json();
				if (isset($data['update_available']) && $data['update_available']) {
					$newVersion = $data['new_version'];
				}else{
					$newVersion = false;
				}
			} catch (\Exception $e) {
				$newVersion = false;
			}
		}else{
			$newVersion = false;
		}
		
        $numbers = $request->user()->devices()->latest()->paginate(15);
      
       
        $user = $request->user()->withCount(['devices','campaigns'])->
        withCount(['blasts as blasts_pending' => function($q){
            return $q->where('status', 'pending');
        }])->withCount(['blasts as blasts_success' => function($q){
            return $q->where('status', 'success');
        }])->withCount(['blasts as blasts_failed' => function($q){
            return $q->where('status', 'failed');
        }])->withCount('messageHistories')->find($request->user()->id);

       

        $user['expired_subscription_status'] = $user->expiredSubscription;
        $user['subscription_status'] = $user->isExpiredSubscription ? 'Expired' : $user->active_subscription;
        return view('theme::home', compact('numbers','user','newVersion'));
    }

    public function store(Request $request){
      
       $validate =  validator($request->all(),[
            'sender' => 'required|min:8|max:15|unique:devices,body',
        ]);

        if($request->user()->isExpiredSubscription){
            return back()->with('alert',['type' => 'danger','msg' => __('Your subscription has expired, please renew your subscription.')]);
        }
        if($validate->fails()){
            return back()->with('alert',['type' => 'danger','msg' => $validate->errors()->first()]);
        }

       if($request->user()->limit_device <= $request->user()->devices()->count() ){
            return back()->with('alert',['type' => 'danger','msg' => __('You have reached the limit of devices!')]);
        }
        $request->user()->devices()->create(['body' => $request->sender,'webhook' => $request->urlwebhook]);
        return back()->with('alert',['type' => 'success','msg' => __('Devices Added!')]);
    }


    public function destroy(Request $request){
        try {
            //code...
             $device = $request->user()->devices()->find($request->deviceId);
            Session::forget('selectedDevice');
			$whatsappService = new WhatsappServiceImpl();
			try {
				$whatsappService->logoutDevice($device->body);
			} catch (\Throwable $th) {

			}

			$device->delete();
            return back()->with('alert',['type' => 'success','msg' => __('Devices Deleted!')]);
        } catch (\Throwable $th) {
            throw $th;
            return back()->with('alert',['type' => 'danger','msg' => __('Something went wrong!')]);
        }
    }


    public function setHook(Request $request){
        clearCacheNode();  
        return $request->user()->devices()->whereBody($request->number)->update(['webhook' => $request->webhook]);
    }
	
	public function setDelay(Request $request){
        clearCacheNode();  
        return $request->user()->devices()->whereBody($request->number)->update(['delay' => $request->delay]);
    }
	
	public function setHookRead(Request $request){
        clearCacheNode();  
        $request->user()->devices()->whereBody($request->id)->update(['webhook_read' => $request->webhook_read]);
	    return response()->json(['error' => false, 'msg' => __('Webhook read has been updated')]);
    }
	
	public function setHookReject(Request $request){
        clearCacheNode();  
        $request->user()->devices()->whereBody($request->id)->update(['webhook_reject_call' => $request->webhook_reject_call]);
	    return response()->json(['error' => false, 'msg' => __('Webhook reject call has been updated')]);
    }
	
	public function setHookTyping(Request $request){
        clearCacheNode();  
        $request->user()->devices()->whereBody($request->id)->update(['webhook_typing' => $request->webhook_typing]);
	    return response()->json(['error' => false, 'msg' => __('Webhook typing has been updated')]);
    }
	
	public function setAvailable(Request $request){
        clearCacheNode($request->id);
        $request->user()->devices()->whereBody($request->id)->update(['set_available' => $request->set_available]);
	    return response()->json(['error' => false, 'msg' => __('Available has been updated')]);
    }

    public function setSelectedDeviceSession(Request $request){
        $device = $request->user()->devices()->whereId($request->device)->first();
        if(!$device){
            return response()->json(['error' => true, 'msg' => __('Device not found!')]);
            Session::forget('selectedDevice');
            
        }
        session()->put('selectedDevice', [
            'device_id' => $device->id,
            'device_body' => $device->body,
        ]);
        return response()->json(['error' => false, 'msg' => __('Device selected!')]);
    }


    


    

}
?>