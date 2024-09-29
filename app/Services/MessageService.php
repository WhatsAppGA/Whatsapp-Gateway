<?php
/**************************/
/*    Nulled & Decoded    */
/*   By Magd Almuntaser   */
/*         TTMTT          */
/**************************/
 

namespace App\Services;

use App\Services\Impl\MessageServiceImpl;

interface MessageService
{
    public function formatText($text) : array;
	
	public function formatLocation($latitude, $longitude) : array;
	
	public function formatVcard($name, $phone) : array;

    public function formatImage($image, $caption = '') : array;

    public function formatButtons ($text, $buttons , $urlimage = '' ,$footer = '') : array;

    public function formatTemplates ($text, $buttons , $urlimage = '' ,$footer = '') : array;

    public function formatLists ($text,$lists,$title,$name,$buttonText,$footer = '') : array;

    public function format ($type, $data) : array;

    
}

  
?>