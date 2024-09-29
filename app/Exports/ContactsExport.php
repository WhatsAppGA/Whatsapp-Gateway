<?php

namespace App\Exports;

use App\Models\Contact;
use Maatwebsite\Excel\Concerns\FromCollection;

class ContactsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */

    protected $tag;
    protected $user;
    public function __construct($tag,$userid)
    {
        $this->tag = $tag;
        $this->user = $userid;
    }
    public function collection()
    {
        return Contact::whereUserId($this->user)->whereTagId($this->tag)->get(['name','number']);
        
    }
}
