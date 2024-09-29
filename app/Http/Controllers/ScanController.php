<?php

namespace App\Http\Controllers;

use App\Models\Device;

class ScanController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function scan(Device $number)
    {

        return view('theme::scan', [
            'number' => $number
        ]);
    }
    public function code(Device $number)
    {

        return view('theme::connect-via-code', [
            'number' => $number
        ]);
    }
}
