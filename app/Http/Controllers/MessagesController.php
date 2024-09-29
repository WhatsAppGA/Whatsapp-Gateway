<?php
/**************************/
/*    Nulled & Decoded    */
/*   By Magd Almuntaser   */
/*         TTMTT          */
/**************************/

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Models\MessageHistory;
use App\Repositories\DeviceRepository;
use App\Services\WhatsappService;
use App\Utils\CacheKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MessagesController extends Controller
{
    protected WhatsappService $whatsappService;
    protected DeviceRepository $deviceRepository;
    protected $processor = [
        'text' => 'sendText',
        'media' => 'sendMedia',
		'sticker' => 'sendSticker',
        'button' => 'sendButton',
        'template' => 'sendTemplate',
        'list' => 'sendList',
        'poll' => 'sendPoll',
		'location' => 'sendLocation',
		'vcard' => 'sendVcard',
    ];

    public function __construct(WhatsappService $whatsappService, DeviceRepository $deviceRepository)
    {
        $this->whatsappService = $whatsappService;
        $this->deviceRepository = $deviceRepository;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $devices = $request->user()->devices()->latest()->paginate(10);
        return view('theme::pages.messagetest', compact('devices'));
    }

    /**
     * Sending and storing message successfully
     */
    public function store(SendMessageRequest $request)
    {

        $receivers = explode('|', $request->number);
        $unique = array_unique($receivers);
        $type = $request->type;

        $success = 0;
        $dataForBatchInput = [];
        $device = Cache::remember(CacheKey::DEVICE_BY_BODY . $request->sender, 60 * 60 * 12, fn () => $this->deviceRepository->byBody($request->sender)->single());

        foreach ($unique as $number) {
            try {
                $method = $this->processor[$type];
                $messageSent = $this->whatsappService->$method($request, $number);

                $dataForBatchInput[] = [
                    'user_id' => $request->user()->id,
                    'device_id' => $device->id,
                    'number' => $number,
                    'message' => $request->message ? $request->message : ($request->caption ? $request->caption : ''),
                    'payload' => json_encode($request->all()),
                    'status' => $messageSent->status ? 'success' : 'failed',
                    'type' => $request->type,
                    'send_by' => 'web',
					'created_at' => now(),
					'updated_at' => now(),
                ];

                $success = $messageSent->status ? $success + 1 : $success;
            } catch (\Exception $e) {
                return backWithFlash('danger', __('Failed to send message to all number,check you whatsapp connection and try again.'));
                Log::error('Error sending message to ' . $number .  ': ' . $e->getMessage());
            }
        }

        MessageHistory::insert($dataForBatchInput);
        $this->deviceRepository->incrementMessageSent($device->id, $success);
        return backWithFlash($success > 0 ? 'success' : 'danger', $success > 0 ? __('Message sent to :success number', ['success' => $success]) : __("Failed to send message to all number,check you whatsapp connection and try again."));
    }
}
?>