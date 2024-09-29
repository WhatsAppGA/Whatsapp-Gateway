<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\Device;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ScheduleCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'schedule:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
      $numbers = Device::whereStatus('Connected')->get();
     
    try {
      $url = env('WA_URL_SERVER').'/backend-initialize';
      Log::info('Auto connect whatsapp running');
      foreach ($numbers as $n) {

        $campaign = $n->campaigns()->where('status','processing')->count();
        if($campaign == 0){

          $result =  Http::withOptions(['verify' => false])->asForm()->post($url,['token' => $n->body]);
          Log::info($result);
        }

       // delay 2 seconds
       
      }
    } catch (\Throwable $th) {
      Log::error($th);
       Log::info('Failed auto connect whatsapp');
    }
     
    }
}
