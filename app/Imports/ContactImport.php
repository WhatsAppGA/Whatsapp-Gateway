<?php

namespace App\Imports;

use App\Models\Contact;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class ContactImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
        protected $tag;
        protected $user;
    public function __construct($tag)
    {
        $this->tag = $tag;
        $this->user = Auth::user()->id;
    }
    public function collection(Collection $collection)
    {
   
Log::info($collection);
        foreach($collection as $row){
            if(strtoupper($row[0]) == 'NAME' && strtoupper($row[1]) == 'NUMBER') continue;
            if($row[0] == null || $row[1] == null) continue;

            Contact::create([
                'user_id' => $this->user,
                'tag_id' => $this->tag,
                'name' => $row[0],
                'number' => (string)$row[1] 
            ]);
        }
      
    }
}
