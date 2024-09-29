<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Services\Impl\WhatsappServiceImpl;
use App\Utils\CacheKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class DeviceController extends Controller
{



    public function logoutDevice(Request $request)
    {
        $device = Device::whereBody($request->sender)->first();
        if ($device->status != 'Connected') {
            return response()->json(['status' => true, 'message' => __('Connection closed. You are logged out.')]);
        }

        $whatsappService = new WhatsappServiceImpl();
        try {
            //code...
            $respon = $whatsappService->logoutDevice($device->body);
            return $respon;
        } catch (\Throwable $th) {
            throw $th;
            return response()->json(['status' => false, 'message' => __('Whtsapp server unable to reach')]);
        }
    }


    public function deleteDevice(Request $request)
    {
        $device = Device::whereBody($request->sender)->first();
        $isForce =  isset($request->force) && $request->force === true ? true : false;
        if ($device->status === 'Connected' && !$isForce) return backWithFlash('danger', __('Device is connected. Please disconnect first'));
        try {
            if (!empty($device->body)) {
                $path = base_path('credentials/' . $device->body);
                if (file_exists($path))  File::deleteDirectory($path);
            }
            $device->delete();
            Cache::forget(CacheKey::DEVICE_BY_BODY . $device->body);
            Cache::forget(CacheKey::DEVICE_BY_ID . $device->id);
            Cache::forget(CacheKey::USER_BY_API_KEY . $device->api_key);
            return response()->json(['status' => true, 'message' => __('Device deleted')]);
        } catch (\Throwable $th) {
            return backWithFlash('danger', __('Something went wrong'));
        }
    }
}
