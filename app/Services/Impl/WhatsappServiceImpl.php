<?php
/**************************/
/*    Nulled & Decoded    */
/*   By Magd Almuntaser   */
/*         TTMTT          */
/**************************/

namespace App\Services\Impl;
use App\Services\WhatsappService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class WhatsappServiceImpl implements WhatsappService
{
    private $url;
    protected const ROUTE_SEND_TEXT = "/backend-send-text";
    protected const ROUTE_SEND_MEDIA = "/backend-send-media";
	protected const ROUTE_SEND_STICKER = "/backend-send-sticker";
    protected const ROUTE_SEND_BUTTON = "/backend-send-button";
    protected const ROUTE_SEND_TEMPLATE = "/backend-send-template";
    protected const ROUTE_SEND_LIST = "/backend-send-list";
    protected const ROUTE_SEND_POLL = "/backend-send-poll";
	protected const ROUTE_SEND_LOCATION = "/backend-send-location";
	protected const ROUTE_SEND_VCARD = "/backend-send-vcard";
    protected const ROUTE_LOGOUT_DEVICE = "/backend-logout-device";
    protected const ROUTE_CHECK_NUMBER = "/backend-check-number";
    protected const ROUTE_GET_GROUPS = "/backend-getgroups";
    protected const ROUTE_START_BLAST = "/backend-blast";
    public function __construct()
    {
        $this->url = env("WA_URL_SERVER");
    }
    private function sendRequest($route, $data): object
    {
        try {
            $results = Http::withOptions(["verify" => false])
                ->asForm()
                ->post($this->url . $route, $data);
            return json_decode($results->body());
        } catch (\Throwable $th) {
            throw $th;
        }
    }
    public function fetchGroups($device): object
    {
        return $this->sendRequest(self::ROUTE_GET_GROUPS, [
            "token" => $device->body,
        ]);
    }
    public function startBlast($data): object
    {
        return $this->sendRequest(self::ROUTE_START_BLAST, [
            "data" => json_encode($data),
            "delay" => 1,
        ]);
    }
    public function sendText($request, $receiver): object|bool
    {
        return $this->sendRequest(self::ROUTE_SEND_TEXT, [
            "token" => $request->sender,
            "number" => $receiver,
            "text" => $request->message,
        ]);
    }
	public function sendLocation($request, $receiver): object|bool
    {
        return $this->sendRequest(self::ROUTE_SEND_LOCATION, [
            "token" => $request->sender,
            "number" => $receiver,
            "latitude" => $request->latitude,
			"longitude" => $request->longitude,
        ]);
    }
	public function sendVcard($request, $receiver): object|bool
    {
        return $this->sendRequest(self::ROUTE_SEND_VCARD, [
            "token" => $request->sender,
            "number" => $receiver,
            "name" => $request->name,
			"phone" => $request->phone,
        ]);
    }
    public function sendMedia($request, $receiver): object|bool
    {
        // GET FILE NAME from $request->url
        $fileName = explode("/", $request->url);
        $fileName = explode(".", end($fileName));
        $fileName = implode(".", $fileName);
        $data = [
            "token" => $request->sender,
            "url" => $request->url,
            "number" => $receiver,
            "caption" => $request->caption ?? "",
            "filename" => $fileName,
            "type" => $request->media_type,
			"viewonce" => $request->viewonce,
            "ptt" => $request->ptt
                ? ($request->ptt == "vn"
                    ? true
                    : false)
                : false,
        ];
        return $this->sendRequest(self::ROUTE_SEND_MEDIA, $data);
    }
	public function sendSticker($request, $receiver): object|bool
    {
        $fileName = explode("/", $request->url);
        $fileName = explode(".", end($fileName));
        $fileName = implode(".", $fileName);
        $data = [
            "token" => $request->sender,
            "url" => $request->url,
            "number" => $receiver,
            "filename" => $fileName,
            "type" => 'sticker',
        ];
        return $this->sendRequest(self::ROUTE_SEND_STICKER, $data);
    }
    public function sendButton($request, $receiver): object|bool
    {
        $buttons = [];
        foreach ($request->button as $button) {
            $buttons[] = ["displayText" => $button];
        }
        // check url if exists,set to image if not exists cheeck thumbnail if exists set to image
        $image = $request->url
            ? $request->url
            : ($request->image
                ? $request->image
                : "");
        $data = [
            "token" => $request->sender,
            "number" => $receiver,
            "button" => json_encode($buttons),
            "message" => $request->message,
            "footer" => $request->footer ?? "",
            "image" => $image,
        ];
        return $this->sendRequest(self::ROUTE_SEND_BUTTON, $data);
    }
    public function sendTemplate($request, $receiver): object|bool
    {
        $templates = [];
        $ii = 1;
        foreach ($request->template as $template) {
            $ii++;
            $typedest =
                explode("|", $template)[0] == "url"
                    ? "url"
                    : (explode("|", $template)[0] == "call"
                        ? "phoneNumber"
                        : "id");
            $type =
                explode("|", $template)[0] == "id"
                    ? "quickReplyButton"
                    : explode("|", $template)[0] . "Button";
            $templates[] = [
                "index" => $ii,
                $type => [
                    "displayText" => explode("|", $template)[1],
                    $typedest => explode("|", $template)[2],
                ],
            ];
        }
        $image = $request->url
            ? $request->url
            : ($request->image
                ? $request->image
                : "");
        $data = [
            "token" => $request->sender,
            "number" => $receiver,
            "button" => json_encode($templates),
            "text" => $request->message,
            "footer" => $request->footer ?? "",
            "image" => $image,
        ];
        return $this->sendRequest(self::ROUTE_SEND_TEMPLATE, $data);
    }
    public function sendList($request, $receiver): object|bool
    {
        $section["title"] = $request->title;
        $i = 1;
        foreach ($request->list as $menu) {
            $i++;
            $section["rows"][] = [
                "title" => $menu,
                "rowId" => "id" . $i,
                "description" => "",
            ];
        }
        $data = [
            "token" => $request->sender,
            "number" => $receiver,
            "list" => json_encode($section),
            "text" => $request->message,
            "footer" => $request->footer ?? "",
            "title" => $request->title,
            "buttonText" => $request->buttontext,
        ];
        return $this->sendRequest(self::ROUTE_SEND_LIST, $data);
    }
    public function sendPoll($request, $receiver): object|bool
    {
        $optionss = [];
        foreach ($request->option as $opt) {
            $optionss[] = $opt;
        }
        $data = [
            "token" => $request->sender,
            "number" => $receiver,
            "name" => $request->name,
            "options" => json_encode($optionss),
            "countable" => $request->countable === "1" ? true : false,
        ];
        return $this->sendRequest(self::ROUTE_SEND_POLL, $data);
    }
    public function logoutDevice($device): object|bool
    {
        return $this->sendRequest(self::ROUTE_LOGOUT_DEVICE, [
            "token" => $device,
        ]);
    }
    public function checkNumber($device, $number): object|bool
    {
        return $this->sendRequest(self::ROUTE_CHECK_NUMBER, [
            "token" => $device,
            "number" => $number,
        ]);
    }
}
?>