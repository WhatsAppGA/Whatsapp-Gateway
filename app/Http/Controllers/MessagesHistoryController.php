<?php

namespace App\Http\Controllers;

use App\Models\MessageHistory;
use App\Services\WhatsappService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class MessagesHistoryController extends Controller
{
    public function index(Request $request){
        $messages = $request->user()->messageHistories()->with('device')->latest()->paginate(15);
		$userId = $request->user()->id;
        return view('theme::pages.histories.message', compact('messages', 'userId'));
    }
	
	public function deleteAll(Request $request){
		$request->validate(['id' => 'required|integer']);
		try {
			$deviceId = $request->id;
			$deletedCount = DB::table('message_histories')->where('user_id', $deviceId)->delete();
			return response()->json(['success' => true, 'msg' => __(':count message(s) deleted successfully', ['count' => $deletedCount])]);
		} catch (\Throwable $th) {
			return response()->json(['error' => true , 'msg' => __('Something went wrong while delete all messages')]);
		}
	}

    public function resend(Request $request, WhatsappService $wa){
       try {
            $history = MessageHistory::find($request->id);
            $device = $history->device;

            if ($history->status == 'success') {
                return response()->json(['error' => true , 'msg' => __('Message already sent, refresh page to update status')]);
            }

            if ($device->status != 'Connected') {
                return response()->json(['error' => true , 'msg' => __('Device is not connected')]);
            }

            $params = json_decode($history->payload);
            if ($history->type == 'text') {
                $res = $wa->sendText($params, $history->number);
            }else if ($history->type == 'media') {
                $res = $wa->sendMedia($params, $history->number);
            }else if ($history->type == 'button') {
                $res = $wa->sendButton($params, $history->number);
            }else if ($history->type == 'template') {
                $res = $wa->sendTemplate($params, $history->number);
            }else if ($history->type == 'list') {
                $res = $wa->sendList($params, $history->number);
            }


            if($res->status){
                $history->update(['status' => 'success']);
                return response()->json(['error' => false , 'msg' => __('Resend message success')]);
            } else {
                return response()->json(['error' => true , 'msg' => __('Failed to resend message to this number,make sure the receiver is registered on whatsapp')]);
            }
 

       } catch (\Throwable $th) {
        Log::error($th->getMessage());
        return response()->json(['error' => true , 'msg' => __('Something went wrong while resending message')]);
       }
    }
}
