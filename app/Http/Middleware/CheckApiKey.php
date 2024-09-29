<?php

namespace App\Http\Middleware;

use App\Models\Device;
use App\Models\User;
use App\Repositories\DeviceRepository;
use App\Services\Common;
use App\Utils\CacheKey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        try {
            $deviceRepository = new DeviceRepository();
            $user = Cache::remember(CacheKey::USER_BY_API_KEY . $request->api_key, 60 * 60 * 12, fn () => User::where('api_key', $request->api_key)->first());
            $device = Cache::remember(CacheKey::DEVICE_BY_BODY . $request->sender, 60 * 60 * 12, fn () => $deviceRepository->byBody($request->sender)->single());

            if ($device->user_id != $user->id) {
                return response()->json(
                    [
                        'status' => false, 'msg' => __('Invalid api_key or sender,please check again'),
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
            $request->merge(['device' => $device, 'user' => $user]);
            return $next($request);
        } catch (\Throwable $th) {

            return response()->json(
                [
                    'status' => false,
                    'msg' => __('Invali api_key or sender,please check again (2)'),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
}
