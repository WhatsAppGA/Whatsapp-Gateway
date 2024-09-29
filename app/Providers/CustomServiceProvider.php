<?php

namespace App\Providers;

use App\Services\Impl\MessageServiceImpl;
use App\Services\Impl\WhatsappServiceImpl;
use App\Services\MessageService;
use App\Services\WhatsappService;
use Illuminate\Support\ServiceProvider;

class CustomServiceProvider extends ServiceProvider  
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
          MessageService::class,
        MessageServiceImpl::class
        );

        $this->app->bind(
          WhatsappService::class,
        WhatsappServiceImpl::class
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
       
    }
}
