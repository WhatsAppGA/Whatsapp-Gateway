<?php
/**************************/
/*    Nulled & Decoded    */
/*   By Magd Almuntaser   */
/*         TTMTT          */
/**************************/

namespace App\Services\Impl;

use App\Services\MessageService;

class MessageServiceImpl implements MessageService
{
    public function formatText($text): array
    {
        return ['text' => $text];
    }
	
	public function formatLocation($latitude, $longitude): array
	{
		return [
			'location' => [
				'degreesLatitude' => $latitude,
				'degreesLongitude' => $longitude,
			],
		];
	}
	
	public function formatVcard($name, $phone): array
	{
		$vcard = 
			"BEGIN:VCARD\n" . 
			"VERSION:3.0\n" . 
			"FN:" . $name . "\n" . 
			"TEL;type=CELL;type=VOICE;waid=" . $phone . ":+" . $phone . "\n" . 
			"END:VCARD";

		return [
			'contacts' => [
				'displayName' => $name,
				'contacts' => [['vcard' => $vcard]]
			]
		];
	}

    public function formatImage($url, $caption = ''): array
    {
        return ['image' => ['url' => $url], 'caption' => $caption];
    }

    // formating buttons
    public function formatButtons($text, $buttons, $urlimage = '', $footer = ''): array
    {
        $optionbuttons = [];
        $i = 1;
        foreach ($buttons as $button) {
            $optionbuttons[] = [
                'buttonId' => "id$i",
                'buttonText' => ['displayText' => $button],
                'type' => 1,
            ];
            $i++;
        }
        $valueForText = $urlimage ? 'caption' : 'text';
        $message = [
            $valueForText => $text,
            'buttons' => $optionbuttons,
            'footer' => $footer,
            'headerType' => 1,
            'viewOnce' => true,
        ];
        if ($urlimage) {
            $message['image'] = ['url' => $urlimage];
        }
        return $message;
    }

    // formating templates
    public function formatTemplates($text, $buttons, $urlimage = '', $footer = ''): array
    {
        $templateButtons = [];
        $i = 1;
        foreach ($buttons as $button) {

            $type = explode('|', $button)[0] . 'Button';
            $textButton = explode('|', $button)[1];
            $urlOrNumber = explode('|', $button)[2];
            $typeIcon = explode('|', $button)[0] === 'url' ? 'url' : 'phoneNumber';
            $templateButtons[] = [
                'index' => $i,
                $type => ['displayText' => $textButton, $typeIcon => $urlOrNumber],
            ];
            $i++;
        }
        $valueForText = $urlimage ? 'caption' : 'text';
        $templateMessage = [
            $valueForText => $text,
            'footer' => $footer,
            'templateButtons' => $templateButtons,
            'viewOnce' => true,
        ];
        //add image to templateMessage if exists
        if ($urlimage) {
            $templateMessage['image'] = ['url' => $urlimage];
        }
        return $templateMessage;
    }

    public function formatLists($text, $lists, $title, $name, $buttonText, $footer = ''): array
    {
        $section = [
            'title' => $title,
        ];
        $i = 1;
        foreach ($lists as $menu) {
            $i++;
            $section['rows'][] = [
                'title' => $menu,
                'rowId' => 'id' . $i,
                'description' => '',
            ];
        }

        $listMessage = [
            'text' => $text,
            'footer' => $footer,
            'title' => $name,
            'buttonText' => $buttonText,
            'sections' => [$section],
          //  'viewOnce' => true,
        ];

        return $listMessage;
    }



    public function format($type, $data): array
    {
        switch ($type) {
            case 'text':
                $reply = $this->formatText($data->message);
                break;
			case 'location':
                $reply = $this->formatLocation($data->latitude, $data->longitude);
                break;
			case 'vcard':
                $reply = $this->formatVcard($data->name, $data->phone);
                break;
            case 'image':
                $reply = $this->formatImage($data->image,  $data->caption);
                break;
            case 'button':
                $buttons = [];
                foreach ($data->button as $button) {
                    $buttons[] = $button;
                }
                $reply = $this->formatButtons($data->message, $buttons, $data->image ? $data->image : '', $data->footer ?? '');
                break;
            case 'template':
                $buttons = [];
                foreach ($data->template as $button) {
                    $buttons[] = $button;
                }
                try {
                    $reply = $this->formatTemplates(
                        $data->message,
                        $buttons,
                        $data->image ? $data->image : '',
                        $data->footer ?? ''
                    );
                } catch (\Throwable $th) {
                    throw new \Exception('Invalid button type');
                }

                break;
            case 'list':
                $reply = $this->formatLists($data->message, $data->list, $data->title, $data->name, $data->buttontext, $data->footer ?? '');

                break;
			case 'sticker':
                $reply = $this->formatSticker($data);
                break;
            case 'media':
                $reply = $this->formatMedia($data);
                break;
            default:
                # code...
                break;
        }

        return $reply;
    }

	private function formatSticker($data)
    {
        //Log::info('data' . json_encode($data));
        $fileName = explode('/', $data->url);
        $fileName = explode('.', end($fileName));
        $fileName = implode('.', $fileName);
        $mediadetail = [
            'type' => 'sticker',
            'url' => $data->url,
            'filename' => $fileName,
        ];

        return $mediadetail;
    }

    private function formatMedia($data)
    {
        //Log::info('data' . json_encode($data));
        $fileName = explode('/', $data->url);
        $fileName = explode('.', end($fileName));
        $fileName = implode('.', $fileName);
        $mediadetail = [
            'type' => $data->media_type,
            'url' => $data->url,
            'caption' => $data->caption ?? '',
			'viewonce' => $data->viewonce ?? '',
            'filename' => $fileName,
        ];

        return $mediadetail;
    }
}
?>