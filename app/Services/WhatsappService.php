<?php
/**************************/
/*    Nulled & Decoded    */
/*   By Magd Almuntaser   */
/*         TTMTT          */
/**************************/

namespace App\Services;

use App\Services\Impl\WhatsappServiceImpl;

interface WhatsappService
{
    public function fetchGroups($device): object;

    public function startBlast($data): object;

    public function sendText($request, $receiver): object | bool;
	
	public function sendLocation($request, $receiver): object | bool;
	
	public function sendVcard($request, $receiver): object | bool;

    public function sendMedia($request, $receiver): object | bool;
	
	public function sendSticker($request, $receiver): object | bool;

    public function sendButton($request, $receiver): object | bool;

    public function sendTemplate($request, $receiver): object | bool;

    public function sendList($request, $receiver): object | bool;

    public function sendPoll($request, $receiver): object | bool;

    public function logoutDevice($device): object | bool;

    public function checkNumber($device, $number): object | bool;
}
  
?>