<?php
//// Developed By Magd Almuntaser (Sedekah Jariyah)..

$YOUR_WEBSITE = "http://yoursite/en/send-message"; // Your Message Gateway
$API = "TZvmoMMVwHtvud2Q21drEmOlM3oXA1"; // API KEY
$YOUR_NUMBER = "62812xxxxxxx"; // Sender Number
$ALL_NUMBER = array(
	'6281222xxxxx',
	//'6282xxxxxxx',
);

$TAFSIR = "kemenag"; // kemenag - quraish - jalalayn

$AUDIO = 0; // 0 OFF - 1 ON
$SHAIKH = "alafasy"; // alafasy - ahmedajamy - husarymujawwad - minshawi - muhammadayyoub - muhammadjibreel

// Message Template
$Message = <<<MESG
*Ayat Alquran*

{{arabic}}

{{indonesia}}

*(Qs. {{Q}}:{{S}})*

*Tafsir ({$TAFSIR}):*
{{tafsir}}

*Sedekah Jariyah*
MESG;














function CurlGlobal($url, $post = false) {
    $curl = curl_init();

    $options = array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json'
        )
    );

    if ($post !== false) {
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = $post;
        $options[CURLOPT_CUSTOMREQUEST] = 'POST';
    } else {
        $options[CURLOPT_CUSTOMREQUEST] = 'GET';
    }

    curl_setopt_array($curl, $options);

    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}

$randomAyat = CurlGlobal("https://quran-api-id.vercel.app/random");

if($TAFSIR == "kemenag"){
	$TafsirQuran = $randomAyat['tafsir']['kemenag']['short'];
}else{
	$TafsirQuran = $randomAyat['tafsir'][$TAFSIR];
}
preg_match('/(\d+)_(\d+)\.png$/', $randomAyat['image']['primary'], $matches);
$Message = str_replace("{{arabic}}", $randomAyat['arab'], $Message);
$Message = str_replace("{{indonesia}}", $randomAyat['translation'], $Message);
$Message = str_replace("{{Q}}", $matches[1], $Message);
$Message = str_replace("{{S}}", $matches[2], $Message);
$Message = str_replace("{{tafsir}}", $TafsirQuran, $Message);

foreach($ALL_NUMBER as $number){
	if($AUDIO == 0){
		$data = [
			'api_key'		=>	$API,
			'message'		=>	$Message,
			'number'		=>	$number,
			'sender'		=>	$YOUR_NUMBER,
		];
	}else{
		$YOUR_WEBSITE = str_replace("send-message", "send-media", $YOUR_WEBSITE);
		$data = [
			'api_key'		=>	$API,
			'number'		=>	$number,
			'sender'		=>	$YOUR_NUMBER,
			'media_type'	=>	'audio',
			'caption'		=>	'',
			'url'			=>	$randomAyat['audio'][$SHAIKH],
		];
	}
	CurlGlobal($YOUR_WEBSITE, $data);
}