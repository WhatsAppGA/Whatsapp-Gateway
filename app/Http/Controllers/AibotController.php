<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;

class AibotController extends Controller
{
    public function index(Request $request)
    {
		$selectedDevice = session()->get('selectedDevice');
        $device = Device::find($selectedDevice['device_id']);
        return view('theme::pages.aibot', compact('device'));
    }
	
	public function store(Request $request)
    {
		$device_id = session()->get('selectedDevice')['device_id'];
        $device = Device::find($device_id);
		$device->typebot = $request->input('typebot');
		$device->reject_call = $request->has('reject_call');
		$device->reject_message = $request->input('reject_message');
		$device->can_read_message = $request->has('can_read_message');
		$device->bot_typing = $request->has('bot_typing');
		$device->reply_when = $request->input('reply_when');
		$device->chatgpt_name = $request->input('chatgpt_name');
		$device->chatgpt_api = $request->input('chatgpt_api');
		$device->gemini_name = $request->input('gemini_name');
		$device->gemini_api = $request->input('gemini_api');
		$device->claude_name = $request->input('claude_name');
		$device->claude_api = $request->input('claude_api');
		$device->dalle_name = $request->input('dalle_name');
		$device->dalle_api = $request->input('dalle_api');

		$device->save();
		
		clearCacheNode(); 

		return redirect()->route('aibot')->with('alert', [
			'type' => 'success',
			'msg' => __('Bot configuration saved successfully.')
		]);
    }
}
