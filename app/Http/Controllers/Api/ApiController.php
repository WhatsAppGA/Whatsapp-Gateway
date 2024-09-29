<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MessageHistory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Device;
use App\Repositories\DeviceRepository;
use App\Services\WhatsappService;
use App\Utils\CacheKey;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ApiController extends Controller
{
    protected WhatsappService $wa;
    protected DeviceRepository $deviceRepository;
    protected $extendedDataNeeded = [
        'text' => ['message', 'number'],
        'media' => ['number', 'media_type', 'url'],
		'sticker' => ['number', 'url'],
        'button' => ['number', 'button', 'message'],
        'template' => ['number', 'template', 'message'],
        'list' => ['number', 'name', 'title', 'buttontext', 'message', 'list'],
        'poll' => ['number', 'name', 'option', 'countable'],
		'location' => ['number', 'latitude', 'longitude'],
		'vcard' => ['number', 'name', 'phone'],
    ];

    protected $allowedMediaType = ['image', 'video', 'audio', 'document'];
    public function __construct(WhatsappService $wa, DeviceRepository $deviceRepository)
    {
		$this->RESPON_SUCCESS = __("Message sent successfully!");
		$this->RESPON_FAILED = __("Failed to send message!, Check your connection!");
		$this->RESPON_INVALID_PARAMS = __("Invalid parameters, please check your input!");
        $this->wa = $wa;
        $this->deviceRepository = $deviceRepository;
    }

    private function getUniqueReceivers($request)
    {
        return array_unique(explode('|', $request->number));
    }

    private function throwInvalidParams()
    {
        return response()->json([
            'status' => false,
            'msg' => __("Invalid parameters!")
        ], 400);
    }

    private function isValidParams($request)
    {
        $type = $request->type;
        if (!in_array($type, array_keys($this->extendedDataNeeded))) return false;
        foreach ($this->extendedDataNeeded[$type] as $key) {
            if (!$request->has($key)) return false;
        }
        return true;
    }


    private function createDataForBatchInput($request, $number, $messageSent)
    {
        return [
            'user_id' => $request->user->id,
            'device_id' => $request->device->id,
            'number' => $number,
            'message' => $request->message ? $request->message : ($request->caption ? $request->caption : ''),
            'payload' => json_encode($request->all()),
            'status' => $messageSent->status ? 'success' : 'failed',
            'type' => $request->type,
            'send_by' => 'api',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    private function insertAndIncrement($prepareHistoryMessage, $success)
    {
        $device = request()->device;
        MessageHistory::insert($prepareHistoryMessage);
        $this->deviceRepository->incrementMessageSent($device->id, $success);
    }

    public function messageText(Request $request)
    {

        $request->merge(['type' => 'text']);
        if (!$this->isValidParams($request)) return $this->throwInvalidParams();
        $receivers = $this->getUniqueReceivers($request);
        $success = 0;
        $prepareHistoryMessage = [];
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendText($request, $number);
                $prepareHistoryMessage[] = $this->createDataForBatchInput($request, $number, $sendMessage);
                $success = $sendMessage->status ? $success + 1 : $success;
            }
            $this->insertAndIncrement($prepareHistoryMessage, $success);
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->sendFailResponse($this->RESPON_FAILED);
        }
    }
	
	public function messageLocation(Request $request)
    {

        $request->merge(['type' => 'location']);
        if (!$this->isValidParams($request)) return $this->throwInvalidParams();
        $receivers = $this->getUniqueReceivers($request);
        $success = 0;
        $prepareHistoryMessage = [];
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendLocation($request, $number);
                $prepareHistoryMessage[] = $this->createDataForBatchInput($request, $number, $sendMessage);
                $success = $sendMessage->status ? $success + 1 : $success;
            }
            $this->insertAndIncrement($prepareHistoryMessage, $success);
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->sendFailResponse($this->RESPON_FAILED);
        }
    }
	
	public function messageVcard(Request $request)
    {

        $request->merge(['type' => 'vcard']);
        if (!$this->isValidParams($request)) return $this->throwInvalidParams();
        $receivers = $this->getUniqueReceivers($request);
        $success = 0;
        $prepareHistoryMessage = [];
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendVcard($request, $number);
                $prepareHistoryMessage[] = $this->createDataForBatchInput($request, $number, $sendMessage);
                $success = $sendMessage->status ? $success + 1 : $success;
            }
            $this->insertAndIncrement($prepareHistoryMessage, $success);
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->sendFailResponse($this->RESPON_FAILED);
        }
    }

    public function messageMedia(Request $request)
    {
        $request->merge(['type' => 'media']);
        if (!$this->isValidParams($request)) return $this->sendFailResponse($this->RESPON_INVALID_PARAMS);
        if (!in_array($request->media_type, $this->allowedMediaType)) return $this->sendFailResponse( __('Invalid media type! Allowed types: :types', [ 'types' => implode(', ', $this->allowedMediaType) ]) );
        $receivers = $this->getUniqueReceivers($request);
        $success = 0;
        $prepareHistoryMessage = [];
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendMedia($request, $number);
                $prepareHistoryMessage[] = $this->createDataForBatchInput($request, $number, $sendMessage);
                $success = $sendMessage->status ? $success + 1 : $success;
            }
            $this->insertAndIncrement($prepareHistoryMessage, $success);
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            return  $this->sendFailResponse($this->RESPON_FAILED);
        }
    }

	public function messageSticker(Request $request)
    {
        $request->merge(['type' => 'sticker']);
        if (!$this->isValidParams($request)) return $this->sendFailResponse($this->RESPON_INVALID_PARAMS);
        $receivers = $this->getUniqueReceivers($request);
        $success = 0;
        $prepareHistoryMessage = [];
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendSticker($request, $number);
                $prepareHistoryMessage[] = $this->createDataForBatchInput($request, $number, $sendMessage);
                $success = $sendMessage->status ? $success + 1 : $success;
            }
            $this->insertAndIncrement($prepareHistoryMessage, $success);
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            return  $this->sendFailResponse($this->RESPON_FAILED);
        }
    }

    public function messageButton(Request $request)
    {
        $request->merge(['type' => 'button']);
        if (!$this->isValidParams($request)) return $this->sendFailResponse($this->RESPON_INVALID_PARAMS);
        if ($request->isMethod('get'))  $request->merge(['button' => explode(',', $request->button)]);
        if (!is_array($request->button)) return $this->sendFailResponse('Invalid button format!');
        $receivers = $this->getUniqueReceivers($request);
        $success = 0;
        $prepareHistoryMessage = [];
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendButton($request, $number);
                $success = $sendMessage->status ? $success + 1 : $success;
                $prepareHistoryMessage[] = $this->createDataForBatchInput($request, $number, $sendMessage);
            }
            $this->insertAndIncrement($prepareHistoryMessage, $success);
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            Log::error($th);
            return $this->sendFailResponse($this->RESPON_FAILED);
        }
    }

    public function messageTemplate(Request $request)
    {
        $request->merge(['type' => 'template']);
        if (!$this->isValidParams($request)) return $this->sendFailResponse($this->RESPON_INVALID_PARAMS);
        if ($request->isMethod('get'))  $request->merge(['template' => explode(',', $request->template)]);
        if (!is_array($request->template)) return $this->sendFailResponse('Invalid template format!');
		$call = array_filter($request->template, function($item) {
			return strpos($item, 'call') !== false;
		});
		$url = array_filter($request->template, function($item) {
			return strpos($item, 'url') !== false;
		});
        if (!empty($call) || !empty($url)){}else{return $this->sendFailResponse(__('Invalid template format!'));}
        $receivers = $this->getUniqueReceivers($request);
        $success = 0;
        $prepareHistoryMessage = [];
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendTemplate($request, $number);
                $success = $sendMessage->status ? $success + 1 : $success;
                $prepareHistoryMessage[] = $this->createDataForBatchInput($request, $number, $sendMessage);
            }
            $this->insertAndIncrement($prepareHistoryMessage, $success);
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            return $this->sendFailResponse($this->RESPON_FAILED);
        }
    }

    public function messageList(Request $request)
    {
        $request->merge(['type' => 'list']);
        if (!$this->isValidParams($request)) return $this->sendFailResponse($this->RESPON_INVALID_PARAMS);
        if (!is_array($request->list)) return $this->sendFailResponse(__('Invalid list format!'));
        if ($request->isMethod('get'))  $request->merge(['list' => explode(',', $request->list)]);
        $receivers = $this->getUniqueReceivers($request);
        $success = 0;
        $prepareHistoryMessage = [];
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendList($request, $number);
                $success = $sendMessage->status ? $success + 1 : $success;
                $prepareHistoryMessage[] = $this->createDataForBatchInput($request, $number, $sendMessage);
            }
            $this->insertAndIncrement($prepareHistoryMessage, $success);
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            return $this->sendFailResponse($this->RESPON_FAILED);
        }
    }
    public function messagePoll(Request $request)
    {
        $request->merge(['type' => 'poll']);
        if (!$this->isValidParams($request)) return $this->sendFailResponse($this->RESPON_INVALID_PARAMS);
        if ($request->isMethod('get'))   $request->merge(['option' => explode(',', $request->option)]);
        if (!is_array($request->option)) return $this->sendFailResponse(__('Invalid option format!'));
        $receivers = $this->getUniqueReceivers($request);
        $success = 0;
        $prepareHistoryMessage = [];
        try {
            foreach ($receivers as $number) {
                $sendMessage = $this->wa->sendPoll($request, $number);
                $success = $sendMessage->status ? $success + 1 : $success;
                $prepareHistoryMessage[] = $this->createDataForBatchInput($request, $number, $sendMessage);
            }
            $this->insertAndIncrement($prepareHistoryMessage, $success);
            return $this->handleResponse($success);
        } catch (\Throwable $th) {
            return $this->sendFailResponse($this->RESPON_FAILED);
        }
    }


    private function handleResponse($success)
    {
        if ($success > 0) return response()->json(['status' => true, 'msg' => __('Message sent successfully!')], Response::HTTP_OK);
        return response()->json(['status' => false, 'msg' => __('Failed to send message!')], Response::HTTP_BAD_REQUEST);
    }

    private function sendFailResponse($message)
    {
        return response()->json(['status' => false, 'msg' => $message], Response::HTTP_BAD_REQUEST);
    }



    public function generateQr(Request $request)
    {

        if (!$request->has('api_key') || !$request->has('device')) return $this->sendFailResponse('Invalid parameters!');
        $user = Cache::remember(CacheKey::USER_BY_API_KEY . $request->api_key, 60 * 60 * 12, fn () => User::whereApiKey($request->api_key)->first());
        if (!$user) return $this->sendFailResponse('Invalid api key!');
        $device = Cache::remember(CacheKey::DEVICE_BY_BODY . $request->device, 60 * 60 * 12, fn () => $this->deviceRepository->byBody($request->device)->single());
        if (!$device) {
            if (!$request->has('force') || !$request->force) return $this->sendFailResponse(__('Device not found!'));
            $device = $this->deviceRepository->create(['body' => $request->device, 'user_id' => $user->id]);
        }
        if ($device->status == 'Connected')  return $this->sendFailResponse(__('Device already connected!'));
        try {
            $post = Http::withOptions(['verify' => false])->asForm()->post(env('WA_URL_SERVER') . '/backend-generate-qr', ['token' => $request->device,]);
        } catch (\Throwable $th) {
            return $this->sendFailResponse($this->RESPON_FAILED);
        }
        return response()->json(json_decode($post->body()), Response::HTTP_OK);
    }
	
	public function createUser(Request $request)
    {
        if (!$request->has('api_key')) return $this->sendFailResponse(__('Invalid parameters!'));
		try {
			$user = Cache::remember(CacheKey::USER_BY_API_KEY . $request->api_key, 60 * 60 * 12, fn () => User::where('api_key', $request->api_key)->first());
			if ($user->level != 'admin') {
                return response()->json(
                    [
                        'status' => false, 'msg' => __('You do not have permission to create a user'),
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }
			try {
				$request->validate([
                    'username' => 'unique:users|min:4|required',
                    'email' => 'unique:users|email|required',
                    'password'  => 'required|min:6'
                ]);
		
                if ($request->has('username') ||
				    $request->has('email') ||
				    $request->has('password') ||
				    $request->has('expire')) {
			        User::create(
                        [
                            'username' => $request->username,
                            'email' => $request->email,
                            'password' => bcrypt($request->password),
                            'api_key' =>  Str::random(30),
                            'chunk_blast' => 0,
                            'subscription_expired' => Carbon::now()->addDays($request->expire),
                            'active_subscription' => 'active',
                            'limit_device' => ($request->limit_device ? $request->limit_device : 10),
                        ]
                    );
					return response()->json(
                        [
                            'status' => true,
                            'message' => __('User :username successfully created', ['username' => $request->username]),
                        ],
                        Response::HTTP_OK
                    );
                }
			} catch (\Throwable $th) {
				return response()->json(
                    [
                        'status' => false,
                        'msg' => __('There is an error in the variables, please check all inputs'),
                    ],
                    Response::HTTP_BAD_REQUEST
                );
			}
		} catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => false,
                    'msg' => __('Invali api_key or sender,please check again (3)'),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        return response()->json(json_decode($post->body()), Response::HTTP_OK);
    }
	
	public function infoUser(Request $request)
    {
        if (!$request->has('api_key')) return $this->sendFailResponse(__('Invalid parameters!'));
		try {
			$user = Cache::remember(CacheKey::USER_BY_API_KEY . $request->api_key, 60 * 60 * 12, fn () => User::where('api_key', $request->api_key)->first());
			if ($user->level != 'admin') {
                return response()->json(
                    [
                        'status' => false, 'msg' => __('You do not have permission to show a user'),
                    ],
                    Response::HTTP_BAD_REQUEST
                );
            }

			try {
				$UserInfo = User::where('username', $request->username)->first();
                if ($request->has('username') && $UserInfo) {
					return response()->json(
                        [
                            'status' => true,
                            'info' => $UserInfo,
                        ],
                        Response::HTTP_OK
                    );
                }else{
					return response()->json(
						[
							'status' => false,
							'msg' => __('There is no user with this username'),
						],
						Response::HTTP_BAD_REQUEST
					);
				}
			} catch (\Throwable $th) {
				return response()->json(
                    [
                        'status' => false,
                        'msg' => __('There is an error in the variables, please check all inputs'),
                    ],
                    Response::HTTP_BAD_REQUEST
                );
			}
		} catch (\Throwable $th) {
            return response()->json(
                [
                    'status' => false,
                    'msg' => __('Invali api_key or sender,please check again (3)'),
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        return response()->json(json_decode($post->body()), Response::HTTP_OK);
    }
	
	public function infoDevices(Request $request)
	{
		if (!$request->has('api_key')) {
			return $this->sendFailResponse(__('Invalid parameters!'));
		}

		try {
			$user = Cache::remember(CacheKey::USER_BY_API_KEY . $request->api_key, 60 * 60 * 12, fn () => User::where('api_key', $request->api_key)->first());

			$CheckUserDevice = $this->getDevices($request, $user);

			if ($CheckUserDevice->isEmpty()) {
				return response()->json([
					'status' => false,
					'msg' => __('The number you are trying to reach does not exist, or you do not have permission.'),
				], Response::HTTP_BAD_REQUEST);
			}

			return response()->json([
				'status' => true,
				'info' => $CheckUserDevice,
			], Response::HTTP_OK);

		} catch (\Throwable $th) {
			return response()->json([
				'status' => false,
				'msg' => __('Invalid api_key or sender, please check again (3)'),
			], Response::HTTP_BAD_REQUEST);
		}
	}

	private function getDevices(Request $request, $user)
	{
		if ($user->level == 'admin') {
			if ($request->has('number')) {
				return Device::where('body', $request->number)->get();
			}
			return Device::all();
		}

		if ($request->has('number')) {
			return Device::where('user_id', $user->id)->where('body', $request->number)->get();
		}

		return Device::where('user_id', $user->id)->get();
	}


    public function checkNumber(Request $request)
    {
        if (!$request->has('number')) return $this->sendFailResponse(__('Invalid parameters!'));
        try {
            $req = $this->wa->checkNumber($request->device->body, $request->number);
            return response()->json(['status' => true, 'msg' => $req->active], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->sendFailResponse(__("Failed to check number!,check your connection!"));
        }
    }
}
